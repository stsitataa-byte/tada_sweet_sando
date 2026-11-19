<?php

namespace App\Support;

/**
 * Keranjang berbasis Laravel Session.
 *
 * Yang disimpan di session HANYA: slug, ukuran, jumlah.
 * Nama, foto, dan HARGA selalu diambil ulang dari config/sando.php di server,
 * jadi harga dari browser tidak pernah dipercaya.
 */
class Cart
{
    public const SESSION_KEY = 'tada_cart';

    public const SIZES = [
        'small'   => 'Small',
        'regular' => 'Regular',
    ];

    public static function products(): array
    {
        return config('sando.products', []);
    }

    public static function product(string $slug): ?array
    {
        $products = self::products();

        return $products[$slug] ?? null;
    }

    public static function money(int $amount): string
    {
        return config('sando.currency', 'Rp') . number_format($amount, 0, ',', '.');
    }

    /** Harga resmi dari config. null = belum diisi. */
    public static function priceFor(array $product, string $size): ?int
    {
        $value = $product['price_' . $size] ?? null;

        return (is_numeric($value) && $value > 0) ? (int) $value : null;
    }

    /** URL foto jika file-nya benar-benar ada di folder public, selain itu null. */
    public static function imageUrl(?string $path): ?string
    {
        if (empty($path) || ! file_exists(public_path($path))) {
            return null;
        }

        return asset($path);
    }

    /** Data produk lengkap untuk dipakai Blade dan JavaScript. */
    public static function catalog(): array
    {
        $catalog = [];

        foreach (self::products() as $slug => $product) {
            $prices = [
                'small'   => self::priceFor($product, 'small'),
                'regular' => self::priceFor($product, 'regular'),
            ];

            $filled = array_filter($prices, fn ($p) => $p !== null);

            $product['slug']          = $slug;
            $product['title']         = $product['title'] ?? $product['name'];
            $product['image_url']     = self::imageUrl($product['image'] ?? null);
            $product['prices']        = $prices;
            $product['prices_text']   = [
                'small'   => $prices['small'] !== null ? self::money($prices['small']) : null,
                'regular' => $prices['regular'] !== null ? self::money($prices['regular']) : null,
            ];
            $product['price_from']    = $filled ? self::money(min($filled)) : null;
            $product['available']     = $product['available'] ?? true;
            $product['color_right']   = $product['color_right'] ?? '#ffffff';
            $product['note']          = $product['note'] ?? config('sando.product_note');

            $catalog[$slug] = $product;
        }

        return $catalog;
    }

    public static function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public static function key(string $slug, string $size): string
    {
        return $slug . '|' . $size;
    }

    /** Tambah item. Kombinasi slug+ukuran yang sama menambah jumlah di baris yang sama. */
    public static function add(string $slug, string $size, int $qty): void
    {
        $cart = self::raw();
        $key  = self::key($slug, $size);

        $current = $cart[$key]['qty'] ?? 0;

        $cart[$key] = [
            'slug' => $slug,
            'size' => $size,
            'qty'  => min(99, $current + $qty),
        ];

        session([self::SESSION_KEY => $cart]);
    }

    /** Set jumlah. Jumlah 0 = hapus item. */
    public static function setQty(string $key, int $qty): void
    {
        $cart = self::raw();

        if (! isset($cart[$key])) {
            return;
        }

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = min(99, $qty);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public static function remove(string $key): void
    {
        $cart = self::raw();
        unset($cart[$key]);
        session([self::SESSION_KEY => $cart]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /** Ringkasan keranjang, semua angka dihitung di server. */
    public static function summary(): array
    {
        $raw     = self::raw();
        $cleaned = $raw;
        $items   = [];
        $count   = 0;
        $total   = 0;

        foreach ($raw as $key => $row) {
            $product = self::product($row['slug'] ?? '');
            $size    = $row['size'] ?? '';
            $price   = $product ? self::priceFor($product, $size) : null;

            // Buang item yang produknya sudah dihapus / harganya dikosongkan.
            if (! $product || ! isset(self::SIZES[$size]) || $price === null) {
                unset($cleaned[$key]);
                continue;
            }

            $qty      = max(1, (int) $row['qty']);
            $subtotal = $price * $qty;

            $items[] = [
                'key'            => $key,
                'slug'           => $row['slug'],
                'name'           => $product['name'],
                'size'           => $size,
                'size_label'     => self::SIZES[$size],
                'image_url'      => self::imageUrl($product['image'] ?? null),
                'color'          => $product['color'] ?? '#fff',
                'unit_price'     => $price,
                'unit_text'      => self::money($price),
                'qty'            => $qty,
                'subtotal'       => $subtotal,
                'subtotal_text'  => self::money($subtotal),
            ];

            $count += $qty;
            $total += $subtotal;
        }

        if ($cleaned !== $raw) {
            session([self::SESSION_KEY => $cleaned]);
        }

        return [
            'items'      => $items,
            'count'      => $count,
            'total'      => $total,
            'total_text' => self::money($total),
        ];
    }
}
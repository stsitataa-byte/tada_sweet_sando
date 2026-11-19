<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Support\Cart;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['catalog' => Cart::catalog()]);
=======
use App\Models\Product;
use Illuminate\Support\Str;

class PageController extends Controller
{
    private const PRODUCT_DETAILS = [
        'mango' => [
            'short' => 'Mango',
            'bg' => '#fff1b0',
            'accent' => '#ffb627',
            'head' => '#ffe27a',
            'blurb' => 'Mangga matang yang manis legit, lembut, dan wangi tropis.',
            'taste' => 'Manis legit, lembut, tropis',
            'fruits' => ['mango'],
            'ingredients' => [
                ['mango', 'Mangga matang segar'],
                ['bread', 'Roti susu lembut'],
                ['cream', 'Dairy whipping cream'],
                ['sugar', 'Gula halus'],
                ['vanilla', 'Ekstrak vanila'],
            ],
            'photo' => 'assets/mangga-sando.png',
        ],
        'strawberry' => [
            'short' => 'Strawberry',
            'bg' => '#ffd3dd',
            'accent' => '#ff7a9a',
            'head' => '#ffdfe9',
            'blurb' => 'Stroberi segar dengan rasa manis-asam yang lembut dan menyegarkan.',
            'taste' => 'Manis, asam segar, lembut',
            'fruits' => ['strawberry'],
            'ingredients' => [
                ['strawberry', 'Stroberi segar'],
                ['bread', 'Roti susu lembut'],
                ['cream', 'Dairy whipping cream'],
                ['sugar', 'Gula halus'],
                ['vanilla', 'Ekstrak vanila'],
            ],
            'photo' => 'assets/strawberry-sando.png',
            'detail' => [
                'rating' => 4,
                'nutrition' => [
                    ['Energi', '220–250 kkal'],
                    ['Karbohidrat', '25–30 g'],
                    ['Protein', '4–5 g'],
                    ['Lemak', '11–14 g'],
                    ['Serat', '2–3 g'],
                    ['Gula', '10–14 g'],
                ],
                'note' => "untuk usia 5 tahun ke atas.\ntidak untuk di konsumsi berlebihan.",
            ],
        ],
        'grape' => [
            'short' => 'Grape',
            'bg' => '#e3d6f7',
            'accent' => '#8e5bb5',
            'head' => '#d9c9f2',
            'blurb' => 'Anggur manis dan juicy berpadu dengan krim lembut serta roti susu empuk.',
            'taste' => 'Manis, juicy, menyegarkan',
            'fruits' => ['grape'],
            'ingredients' => [
                ['grape', 'Anggur segar tanpa biji'],
                ['bread', 'Roti susu lembut'],
                ['cream', 'Dairy whipping cream'],
                ['sugar', 'Gula halus'],
                ['vanilla', 'Ekstrak vanila'],
            ],
            'photo' => 'assets/grape-sando.png',
        ],
        'mix' => [
            'short' => 'Mix',
            'bg' => '#cfeccf',
            'accent' => '#ff8fb8',
            'head' => '#bfe8c4',
            'blurb' => 'Tiga buah favorit dalam satu sandwich: stroberi, mangga, dan anggur.',
            'taste' => 'Manis, asam segar, tropis',
            'fruits' => ['strawberry', 'mango', 'grape'],
            'ingredients' => [
                ['strawberry', 'Stroberi segar'],
                ['mango', 'Mangga segar'],
                ['grape', 'Anggur segar tanpa biji'],
                ['bread', 'Roti susu lembut'],
                ['cream', 'Dairy whipping cream'],
                ['sugar', 'Gula halus'],
                ['vanilla', 'Ekstrak vanila'],
            ],
            'photo' => '',
        ],
    ];

    private function products(): array
    {
        return Product::query()
            ->orderBy('id')
            ->get()
            ->filter(fn (Product $product): bool => (bool) ($product->getAttribute('is_active') ?? true))
            ->mapWithKeys(function (Product $product): array {
                $artSlug = $this->artSlug($product);
                $details = self::PRODUCT_DETAILS[$artSlug] ?? self::PRODUCT_DETAILS['strawberry'];
                $description = $product->description ?: $details['blurb'];
                $photo = $product->getAttribute('image')
                    ?: $product->getAttribute('image_url')
                    ?: $details['photo'];

                $data = [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'artSlug' => $artSlug,
                    'name' => $product->name,
                    'short' => $product->short_name ?: $details['short'],
                    'tab' => $artSlug === 'mix' ? 'mix' : 'single',
                    'bg' => $product->color ?: $details['bg'],
                    'accent' => $product->accent_color ?: $details['accent'],
                    'head' => $product->header_color ?: $details['head'],
                    'price' => (float) $product->price,
                    'stock' => (int) $product->stock,
                    'blurb' => $product->tagline ?: $description,
                    'taste' => $product->tasting_notes ?: $details['taste'],
                    'fruits' => $product->fruits ?: $details['fruits'],
                    'ingredients' => $product->ingredients ?: $details['ingredients'],
                ];

                if ($artSlug === 'strawberry' && ($product->getAttribute('detail_enabled') ?? true)) {
                    $data['detail'] = [
                        'photo' => $photo,
                        'title' => "FRUIT SWEET\nSANDO",
                        'name' => $product->short_name ?: $details['short'],
                        'rating' => (int) ($product->rating ?: $details['detail']['rating']),
                        'desc' => $description,
                        'nutrition' => $product->nutrition ?: $details['detail']['nutrition'],
                        'bahan' => array_column($product->ingredients ?: $details['ingredients'], 1),
                        'note' => $product->note ?: $details['detail']['note'],
                    ];
                }

                return [$product->slug => $data];
            })
            ->all();
    }

    private function artSlug(Product $product): string
    {
        $slug = Str::lower($product->slug.' '.$product->name);

        foreach (['strawberry', 'mango', 'grape', 'mix'] as $candidate) {
            if (Str::contains($slug, $candidate)) {
                return $candidate;
            }
        }

        if (Str::contains($slug, ['anggur'])) {
            return 'grape';
        }

        if (Str::contains($slug, ['mangga'])) {
            return 'mango';
        }

        return 'strawberry';
    }

    public function home()
    {
        return view('home', ['products' => $this->products()]);
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
    }

    public function shop()
    {
<<<<<<< HEAD
        return view('shop', ['catalog' => Cart::catalog()]);
=======
        return view('shop', ['products' => $this->products()]);
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
    }

    public function about()
    {
<<<<<<< HEAD
        return view('about', ['catalog' => Cart::catalog()]);
    }

    /** Halaman menu tiga kolom. /menu = varian pertama, /menu/{slug} = varian tertentu. */
    public function product(?string $slug = null)
    {
        $catalog = Cart::catalog();

        if (empty($catalog)) {
            abort(404);
        }

        $slug = $slug ?? array_key_first($catalog);

        if (! isset($catalog[$slug])) {
            abort(404);
        }

        return view('product', [
            'catalog' => $catalog,
            'current' => $catalog[$slug],
        ]);
    }
}
=======
        return view('about', ['products' => $this->products()]);
    }

    public function product(string $slug)
    {
        $products = $this->products();

        if (! isset($products[$slug])) {
            $slug = collect($products)
                ->first(fn (array $product): bool => $product['artSlug'] === $slug)['slug'] ?? $slug;
        }

        abort_unless(isset($products[$slug]), 404);

        return view('product', ['products' => $products, 'current' => $slug]);
    }
}
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be

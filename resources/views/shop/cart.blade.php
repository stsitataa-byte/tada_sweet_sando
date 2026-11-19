@extends('layouts.store')

@section('title', 'Keranjang')

@section('content')
    <div class="section-head"><div><div class="eyebrow">Hampir jadi milikmu</div><h1 style="margin:5px 0 0;font-size:32px">Keranjang belanja</h1></div><a class="btn btn-light" href="{{ route('home') }}">← Lanjut belanja</a></div>
    @if ($items->isEmpty())
        <div class="empty"><div class="emoji">🧺</div><h2>Keranjangmu masih kosong</h2><p class="muted">Yuk, pilih sandwich favoritmu dulu.</p><a class="btn" href="{{ route('home') }}">Lihat menu</a></div>
    @else
        <div class="split" style="align-items:start">
            <section class="panel">
                <div class="table-wrap"><table>
                    <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr></thead>
                    <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><strong>{{ $item['product']->name }}</strong><div class="muted small">Stok {{ $item['product']->stock }}</div></td>
                            <td>Rp{{ number_format((float) $item['product']->price, 0, ',', '.') }}</td>
                            <td><form class="row" method="POST" action="{{ route('cart.update', $item['product']) }}">@csrf @method('PATCH')<input class="input" style="width:76px" type="number" name="quantity" min="1" max="{{ min(99, $item['product']->stock) }}" value="{{ $item['quantity'] }}" aria-label="Jumlah {{ $item['product']->name }}"><button class="btn btn-light btn-sm" type="submit">Ubah</button></form></td>
                            <td><strong>Rp{{ number_format((float) $item['subtotal'], 0, ',', '.') }}</strong></td>
                            <td><form method="POST" action="{{ route('cart.destroy', $item['product']) }}">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit" aria-label="Hapus {{ $item['product']->name }}">Hapus</button></form></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            </section>
            <aside class="panel">
                <h2>Ringkasan pesanan</h2>
                <div class="row" style="justify-content:space-between;margin:20px 0"><span class="muted">Total</span><strong style="font-size:22px">Rp{{ number_format((float) $total, 0, ',', '.') }}</strong></div>
                @auth
                    <form method="POST" action="{{ route('cart.checkout') }}">@csrf
                        <div class="field"><label for="customer_phone">Nomor telepon</label><input class="input" id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone') }}" maxlength="30" autocomplete="tel" required></div>
                        <div class="field"><label for="customer_address">Alamat (opsional)</label><textarea id="customer_address" name="customer_address" maxlength="2000" autocomplete="street-address">{{ old('customer_address') }}</textarea></div>
                        <div class="field"><label for="payment_method">Metode pembayaran</label><select id="payment_method" name="payment_method" required><option value="COD">Bayar di tempat (COD)</option><option value="QRIS">QRIS</option></select></div>
                        <button class="btn" style="width:100%" type="submit">Buat pesanan</button>
                    </form>
                @else
                    <p class="muted small">Masuk atau daftar untuk menyelesaikan pesanan.</p>
                    <a class="btn" style="width:100%" href="{{ route('login', ['redirect' => 'cart']) }}">Masuk untuk checkout</a>
                @endauth
            </aside>
        </div>
    @endif
@endsection

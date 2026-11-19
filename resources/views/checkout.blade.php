@extends('layouts.app')

@section('title', 'Checkout | TADA SWEETSANDO')

@section('content')
<section class="checkout">
    <h1 class="display-title display-title--checkout">{{ $confirmed ? 'RINGKASAN PESANAN' : 'CHECKOUT' }}</h1>

    @if(empty($cart['items']))
        <div class="checkout__empty">
            <p>Keranjang masih kosong.</p>
            <a class="btn btn--pink" href="{{ route('shop') }}">Mulai belanja</a>
        </div>
    @else
        <div class="checkout__grid">

            {{-- FORM / DATA PEMESAN --}}
            <div class="checkout__form">
                @if($confirmed)
                    <h2>Data pemesan</h2>
                    <dl>
                        <dt>Nama</dt><dd>{{ $order['name'] }}</dd>
                        <dt>Telepon</dt><dd>{{ $order['phone'] }}</dd>
                        <dt>Alamat</dt><dd>{{ $order['address'] }}</dd>
                        @if(! empty($order['notes']))<dt>Catatan</dt><dd>{{ $order['notes'] }}</dd>@endif
                    </dl>

                    <div class="checkout__info">
                        <b>Pembayaran belum terintegrasi.</b>
                        <p>Pesanan ini belum diproses dan belum ada pembayaran yang terjadi. Website ini belum terhubung ke payment gateway.</p>
                        @if($waUrl)
                            <a class="btn btn--pink" href="{{ $waUrl }}" target="_blank" rel="noopener">Kirim pesanan ke WhatsApp</a>
                        @else
                            <p class="muted">Nomor WhatsApp penjual belum diisi di config/sando.php.</p>
                        @endif
                    </div>
                @else
                    <h2>Data pemesan</h2>
                    <form method="POST" action="{{ route('checkout.store') }}" novalidate>
                        @csrf
                        <label for="name">Nama lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" required>
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror

                        <label for="phone">Nomor telepon</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required>
                        @error('phone')<p class="field-error">{{ $message }}</p>@enderror

                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')<p class="field-error">{{ $message }}</p>@enderror

                        <label for="notes">Catatan (opsional)</label>
                        <textarea id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes')<p class="field-error">{{ $message }}</p>@enderror

                        <button class="btn btn--pink btn--block" type="submit">Lihat ringkasan pesanan</button>
                        <p class="muted">Belum ada pembayaran online. Langkah berikutnya hanya menampilkan ringkasan pesanan.</p>
                    </form>
                @endif
            </div>

            {{-- RINGKASAN KERANJANG (dari session, dihitung server) --}}
            <div class="checkout__summary">
                <h2>Pesanan Anda</h2>
                @foreach($cart['items'] as $item)
                    <div class="sum-row">
                        <div class="sum-row__thumb" style="background: {{ $item['color'] }}">
                            @if($item['image_url'])<img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}">@endif
                        </div>
                        <div class="sum-row__info">
                            <b>{{ $item['name'] }}</b>
                            <span>{{ $item['size_label'] }} &times; {{ $item['qty'] }}</span>
                        </div>
                        <b>{{ $item['subtotal_text'] }}</b>
                    </div>
                @endforeach
                <div class="sum-total"><span>Total</span><b>{{ $cart['total_text'] }}</b></div>
                @if(! $confirmed)
                    <button type="button" class="btn btn--ghost btn--block" data-cart-open>Ubah isi keranjang</button>
                @endif
            </div>

        </div>
    @endif
</section>
@endsection
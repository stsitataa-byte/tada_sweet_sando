@extends('layouts.store')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="section-head"><div><div class="eyebrow">Terima kasih sudah memilih Tada</div><h1 style="margin:5px 0 0;font-size:32px">Pesanan saya</h1></div><a class="btn btn-light" href="{{ route('home') }}">Belanja lagi</a></div>
    @forelse ($orders as $order)
        <article class="order-card">
            <div class="order-title">
                <div><strong>Pesanan #{{ $order->id }}</strong><div class="muted small">{{ $order->created_at->format('d M Y, H:i') }}</div></div>
                <span class="badge status-{{ $order->status }}">{{ ['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'][$order->status] }}</span>
            </div>
            <div class="order-items">@foreach ($order->items as $item){{ $item->product_name }} × {{ $item->quantity }}@if (! $loop->last) · @endif @endforeach</div>
            @if ($order->admin_note)<p class="muted small">Catatan toko: {{ $order->admin_note }}</p>@endif
            <strong>Total Rp{{ number_format((float) $order->total, 0, ',', '.') }}</strong>
        </article>
    @empty
        <div class="empty"><div class="emoji">🥪</div><h2>Belum ada pesanan</h2><p class="muted">Pesanan yang kamu buat akan muncul di sini.</p><a class="btn" href="{{ route('home') }}">Jelajahi menu</a></div>
    @endforelse
@endsection

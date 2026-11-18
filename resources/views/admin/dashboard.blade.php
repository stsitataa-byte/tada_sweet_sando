@extends('layouts.store')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Ringkasan toko</div><h1 style="margin:5px 0 0;font-size:32px">Dashboard admin</h1><p class="muted" style="margin:4px 0 0">Halo, {{ auth()->user()->name }}. Berikut kondisi toko bulan ini.</p></div>
        <span class="badge">{{ $pendingOrders }} pesanan menunggu</span>
    </div>

    <section class="stats">
        <article class="card"><div class="card-label">Pemasukan bulan ini</div><div class="card-value">Rp{{ number_format((float) $income, 0, ',', '.') }}</div><div class="card-note">Pesanan selesai + pemasukan manual</div></article>
        <article class="card"><div class="card-label">Pengeluaran bulan ini</div><div class="card-value">Rp{{ number_format((float) $expenses, 0, ',', '.') }}</div><div class="card-note">Dari catatan arus kas</div></article>
        <article class="card"><div class="card-label">Selisih bulan ini</div><div class="card-value">Rp{{ number_format((float) $net, 0, ',', '.') }}</div><div class="card-note">Pemasukan dikurangi pengeluaran</div></article>
        <article class="card"><div class="card-label">Persentase terjual</div><div class="card-value">{{ number_format($salesRate, 1, ',', '.') }}%</div><div class="card-note">Unit terjual dibanding stok + terjual</div></article>
    </section>

    <section class="panel">
        <div class="panel-head"><div><h2>Persediaan produk</h2><div class="muted small">Perbarui stok yang tersedia untuk dipesan.</div></div></div>
        <div class="table-wrap"><table>
            <thead><tr><th>Produk</th><th>Harga</th><th>Stok saat ini</th><th>Terjual</th><th>Persentase terjual</th><th>Perbarui stok</th></tr></thead>
            <tbody>
            @forelse ($products as $product)
                @php($productRate = $product->sold_quantity + $product->stock > 0 ? round($product->sold_quantity / ($product->sold_quantity + $product->stock) * 100, 1) : 0)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td><td>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</td><td>{{ $product->stock }}</td><td>{{ $product->sold_quantity }}</td><td>{{ number_format($productRate, 1, ',', '.') }}%</td>
                    <td><form class="row" method="POST" action="{{ route('admin.products.stock', $product) }}">@csrf @method('PATCH')<input class="input" style="width:95px" type="number" name="stock" min="0" max="1000000" value="{{ $product->stock }}" aria-label="Stok {{ $product->name }}" required><button class="btn btn-sm" type="submit">Simpan</button></form></td>
                </tr>
            @empty<tr><td colspan="6" class="muted">Belum ada produk.</td></tr>@endforelse
            </tbody>
        </table></div>
    </section>

    <div class="split" style="align-items:start">
        <section class="panel">
            <div class="panel-head"><div><h2>Catat arus kas</h2><div class="muted small">Tambahkan pemasukan atau pengeluaran toko.</div></div></div>
            <form method="POST" action="{{ route('admin.cash-flows.store') }}">@csrf
                <div class="field"><label for="type">Jenis transaksi</label><select id="type" name="type" required><option value="income">Pemasukan</option><option value="expense">Pengeluaran</option></select></div>
                <div class="field"><label for="amount">Nominal (Rp)</label><input class="input" id="amount" name="amount" type="number" min="1" step="1" max="9999999999" required></div>
                <div class="field"><label for="description">Keterangan</label><input class="input" id="description" name="description" maxlength="255" required placeholder="Contoh: Belanja bahan baku"></div>
                <button class="btn" type="submit">Simpan transaksi</button>
            </form>
        </section>
        <section class="panel">
            <div class="panel-head"><div><h2>Transaksi terbaru</h2><div class="muted small">Pemasukan pesanan dicatat di bagian pesanan selesai.</div></div></div>
            @forelse ($cashFlows as $flow)
                <div class="row" style="justify-content:space-between;border-bottom:1px solid #f0e9e2;padding:11px 0">
                    <div><strong>{{ $flow->description }}</strong><div class="muted small">{{ $flow->created_at->format('d M Y') }} · {{ $flow->user?->name ?? 'Admin' }}</div></div>
                    <strong style="color:{{ $flow->type === 'income' ? '#3d7750' : '#a64135' }}">{{ $flow->type === 'income' ? '+' : '−' }}Rp{{ number_format((float) $flow->amount, 0, ',', '.') }}</strong>
                </div>
            @empty<p class="muted small">Belum ada transaksi manual.</p>@endforelse
        </section>
    </div>

    <section class="panel">
        <div class="panel-head"><div><h2>Pesanan terbaru</h2><div class="muted small">Perbarui status dan keterangan pesanan pelanggan.</div></div></div>
        @forelse ($orders as $order)
            <article class="order-card">
                <div class="order-title">
                    <div><strong>Pesanan #{{ $order->id }} · {{ $order->user?->name ?? 'Pelanggan' }}</strong><div class="muted small">{{ $order->created_at->format('d M Y, H:i') }} · {{ $order->user?->email }}</div></div>
                    <span class="badge status-{{ $order->status }}">{{ ['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'][$order->status] }}</span>
                </div>
                <div class="order-items">@foreach ($order->items as $item){{ $item->product_name }} × {{ $item->quantity }}@if (! $loop->last) · @endif @endforeach · <strong>Rp{{ number_format((float) $order->total, 0, ',', '.') }}</strong></div>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">@csrf @method('PATCH')
                    <div class="split">
                        <div class="field"><label for="status-{{ $order->id }}">Status</label><select id="status-{{ $order->id }}" name="status" @disabled(in_array($order->status, ['completed', 'cancelled'], true))>
                            @foreach (['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                                <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>@if (in_array($order->status, ['completed', 'cancelled'], true))<input type="hidden" name="status" value="{{ $order->status }}">@endif</div>
                        <div class="field"><label for="note-{{ $order->id }}">Keterangan untuk pelanggan</label><input class="input" id="note-{{ $order->id }}" name="admin_note" maxlength="1000" value="{{ $order->admin_note }}" placeholder="Contoh: Pesanan sedang disiapkan"></div>
                    </div>
                    <button class="btn btn-sm" type="submit">Simpan perubahan</button>
                </form>
            </article>
        @empty<div class="empty"><div class="emoji">🧾</div><p class="muted">Belum ada pesanan masuk.</p></div>@endforelse
    </section>
@endsection

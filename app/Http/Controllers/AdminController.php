<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->orderBy('name')->get();
        $orders = Order::query()->with(['user', 'items'])->latest()->limit(20)->get();
        $month = now()->startOfMonth();

        $salesIncome = Order::query()
            ->where('order_status', 'completed')
            ->where('updated_at', '>=', $month)
            ->sum('total');
        $manualIncome = CashFlow::query()->where('type', 'income')->where('created_at', '>=', $month)->sum('amount');
        $expenses = CashFlow::query()->where('type', 'expense')->where('created_at', '>=', $month)->sum('amount');
        $sold = $products->sum('sold_quantity');
        $salesRate = $sold + $products->sum('stock') > 0
            ? round($sold / ($sold + $products->sum('stock')) * 100, 1)
            : 0;

        return view('admin.dashboard', [
            'products' => $products,
            'orders' => $orders,
            'income' => $salesIncome + $manualIncome,
            'expenses' => $expenses,
            'net' => $salesIncome + $manualIncome - $expenses,
            'salesRate' => $salesRate,
            'pendingOrders' => Order::query()->where('order_status', 'pending')->count(),
            'cashFlows' => CashFlow::query()->with('user')->latest()->limit(10)->get(),
        ]);
    }

    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
        ], [
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari nol.',
        ]);

        $product->update(['stock' => $data['stock']]);

        return back()->with('success', "Stok {$product->name} berhasil diperbarui.");
    }

    public function storeCashFlow(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'description' => ['required', 'string', 'max:255'],
        ], [
            'type.required' => 'Jenis transaksi wajib dipilih.',
            'amount.required' => 'Nominal wajib diisi.',
            'amount.gt' => 'Nominal harus lebih besar dari nol.',
            'description.required' => 'Keterangan wajib diisi.',
        ]);

        CashFlow::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Transaksi berhasil dicatat.');
    }

    public function updateOrder(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'admin_note.max' => 'Keterangan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use ($order, $data) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);

            if (in_array($order->status, ['completed', 'cancelled'], true) && $order->status !== $data['status']) {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan selesai atau dibatalkan tidak dapat diubah kembali.',
                ]);
            }

            if ($order->status !== 'cancelled' && $data['status'] === 'cancelled') {
                foreach ($order->items()->with('product')->get() as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            if ($order->status !== 'completed' && $data['status'] === 'completed') {
                foreach ($order->items()->with('product')->get() as $item) {
                    if ($item->product) {
                        $item->product->increment('sold_quantity', $item->quantity);
                    }
                }
            }

            $order->update([
                'order_status' => $data['status'],
                'admin_note' => $data['admin_note'] ?? null,
            ]);
        });

        return back()->with('success', "Pesanan #{$order->id} berhasil diperbarui.");
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $orders = Order::doesntHave('transaction')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:cash,qris',
            'amount_paid' => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = Order::findOrFail($request->order_id);

        Transaction::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'change' => max(0, $request->amount_paid - $order->total),
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $rules = [
            'payment_method' => 'required|in:cash,qris',
            'amount_paid' => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $orderTotal = $transaction->order->total;

        $transaction->update([
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'change' => max(0, $request->amount_paid - $orderTotal),
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    public function printAll(Request $request)
    {
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : now()->startOfDay();
        $end   = $request->end ? Carbon::parse($request->end)->endOfDay() : now()->endOfDay();

        $transactions = Transaction::with('order')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'ASC')
            ->get();

        if ($transactions->isEmpty()) {
            return redirect()->route('transactions.index')
                ->with('error', 'No transaction was found.');
        }

        $total = $transactions->sum(fn($t) => $t->order->total);

        $pdf = Pdf::loadView('pdf.laporan', [
            'transactions' => $transactions,
            'total' => $total,
            'start' => $start->format('d-m-Y'),
            'end' => $end->format('d-m-Y'),
        ]);

        return $pdf->download('Laporan_Keuangan_' . now()->format('d-m-Y') . '.pdf');
    }

    public function generateReceipt($id)
    {
        $transaction = Transaction::find($id);
        $pdf = Pdf::loadView('pdf.receipt', compact('transaction'));
        return $pdf->download('receipt_transaction_' . $transaction->id . '.pdf');
    }
}

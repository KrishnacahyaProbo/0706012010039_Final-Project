<?php

namespace App\Http\Controllers;

use App\Models\BalanceNominal;
use App\Models\Testimony;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.order.index');
    }

    public function data(Request $request)
    {
        // Ambil data order
        $transaction = Transaction::where('customer_id', Auth::user()->id)
            ->join('transactions_detail', 'transactions.id', '=', 'transactions_detail.transaction_id')
            ->join('menu', 'transactions_detail.menu_id', '=', 'menu.id')
            ->join('users', 'transactions_detail.vendor_id', '=', 'users.id')
            ->where('transactions_detail.status', '=', $request->status)
            ->select('transactions.*', 'menu.*', 'users.name', 'transactions_detail.*', 'transactions_detail.id as detail_id', 'users.id as vendor_id')
            ->get();

        // Hanya bisa 1x testimoni per order
        foreach ($transaction as $key => $value) {
            $value->testimony = Testimony::where('transactions_detail_id', $value->detail_id)->count();
        }

        return DataTables::of($transaction)
            ->make(true);
    }

    public function cancelOrder(Request $request)
    {
        // Ubah status item
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'customer_canceled';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been cancelled'
        ]);
    }

    public function receiveOrder(Request $request)
    {
        // Ubah status item
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'customer_received';
        $transaction->save();

        // Tambah credit vendor
        $vendor = BalanceNominal::where('user_id', $transaction->vendor_id)->first();
        $vendor->credit += $transaction->total_price;
        $vendor->save();

        return response()->json([
            'message' => 'Order has been received'
        ]);
    }
}

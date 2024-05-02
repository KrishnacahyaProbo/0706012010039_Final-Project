<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.order.index');
    }

    public function data()
    {
        $transaction = Transaction::where('customer_id', Auth::user()->id)
            ->join('transactions_detail', 'transactions.id', '=', 'transactions_detail.transaction_id')
            ->join('menu', 'transactions_detail.menu_id', '=', 'menu.id')
            ->join('users', 'transactions_detail.vendor_id', '=', 'users.id')
            ->get();

        // dd($transaction);
        return DataTables::of($transaction)
            ->make(true);
    }

    public function cancelOrder(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->status = 'customer_canceled';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been cancelled'
        ]);
    }

    public function receiveOrder()
    {
        
    }
}

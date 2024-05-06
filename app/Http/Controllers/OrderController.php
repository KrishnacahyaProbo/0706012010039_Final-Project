<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use App\Models\Transaction;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use App\Models\BalanceHistory;
use App\Models\BalanceNominal;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.order.index');
    }

    public function detailOrder(string $id)
    {
        dd($id);
    }

    public function requestOrder(Request $request)
    {
        // Ambil data order untuk customer
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
            $vendorRule = UserSetting::where('vendor_id', $value->vendor_id)->first();

            // Jika pada hari-H telah melewati batas waktu (confirmation_days) berdasarkan aturan vendor, maka status order customer_paid tidak dapat dibatalkan menjadi customer_canceled
            if ($value->status == 'customer_paid' && $vendorRule->confirmation_days < now()->diffInDays($value->schedule_date)) {
                $value->rule = 1;
            } else {
                $value->rule = 0;
            }
        }

        return DataTables::of($transaction)
            ->make(true);
    }

    public function incomingOrder(Request $request)
    {
        // Ambil data order untuk vendor
        $transaction = TransactionDetail::where('transactions_detail.vendor_id', Auth::user()->id)
            ->join('transactions', 'transactions.id', '=', 'transactions_detail.transaction_id')
            ->join('menu', 'transactions_detail.menu_id', '=', 'menu.id')
            ->join('users', 'transactions.customer_id', '=', 'users.id')
            ->where('transactions_detail.status', '=', $request->status);

        if (isset($request->schedule_date)) $transaction->where('transactions_detail.schedule_date', $request->schedule_date);

        $transaction = $transaction->select('transactions.*', 'menu.*', 'users.name', 'transactions_detail.*', 'transactions_detail.id as detail_id', 'users.id as customer_id')
            ->get();

        return DataTables::of($transaction)
            ->make(true);
    }

    public function cancelOrder(Request $request)
    {
        // Ubah status item
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'customer_canceled';
        $transaction->save();

        // Kembalikan credit customer seperti semula setelah berhasil cancel order
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();
        $balance->credit += $transaction->total_price;
        $balance->save();

        $ship = BalanceNominal::where('user_id', $transaction->vendor_id)->first();

        $isiUlang = ['user_id' => Auth::user()->id, 'credit' => $transaction->total_price + $ship->shipping_costs, 'category' => 'customer_income'];
        BalanceHistory::create($isiUlang);

        return response()->json([
            'message' => 'Order has been cancelled'
        ]);
    }

    public function processOrder(Request $request)
    {
        // Ubah status item
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'vendor_packing';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been packed'
        ]);
    }

    public function deliverOrder(Request $request)
    {
        // Ubah status item
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'vendor_delivering';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been delivered'
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

        $ship = BalanceNominal::where('user_id', $transaction->vendor_id)->first();

        // Simpan riwayat penjualan sebagai kategori pemasukan vendor
        $penjualan = ['user_id' => $transaction->vendor_id, 'credit' => $transaction->total_price + $ship->shipping_costs, 'category' => 'vendor_income'];
        BalanceHistory::create($penjualan);

        return response()->json([
            'message' => 'Order has been received'
        ]);
    }

    public function refundReason(Request $request)
    {
        // Ubah status item
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'customer_refund';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been refunded'
        ]);
        // try {
        //     $testimony = [
        //         'transactions_detail_id' => $request->addTestimonyId,
        //         'vendor_id' => $request->vendorId,
        //         'customer_id' => Auth::user()->id,
        //         'rating' => $request->rating,
        //         'description' => $request->description,
        //     ];

        //     if ($request->hasFile('testimony_photo')) {
        //         $image = $request->file('testimony_photo');
        //         $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
        //         Storage::disk('public_uploads_testimony_photo')->put($imageName, file_get_contents($image));
        //         $testimony['testimony_photo'] = $imageName;
        //     }

        //     Testimony::create($testimony);

        //     return back();
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        // }
    }
}

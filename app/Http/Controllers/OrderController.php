<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use App\Models\Transaction;
use App\Models\UserSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BalanceHistory;
use App\Models\BalanceNominal;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        // Mendapatkan data order untuk customer
        $transactionQuery = Transaction::where('customer_id', Auth::user()->id)
            ->join('transactions_detail', 'transactions.id', '=', 'transactions_detail.transaction_id')
            ->join('menu', 'transactions_detail.menu_id', '=', 'menu.id')
            ->join('users', 'transactions_detail.vendor_id', '=', 'users.id')
            ->select('transactions.*', 'menu.*', 'users.name', 'transactions_detail.*', 'transactions_detail.id as detail_id', 'users.id as vendor_id');

        // Filter berdasarkan status order
        if ($request->has('status') && !empty($request->status)) {
            $transactionQuery->where('transactions_detail.status', '=', $request->status);
        }

        $transaction = $transactionQuery->get();

        foreach ($transaction as $key => $value) {
            // Hanya bisa 1x testimoni per order item
            $value->testimony = Testimony::where('transactions_detail_id', $value->detail_id)->count();
            $vendorRule = UserSetting::where('vendor_id', $value->vendor_id)->first();

            // Jika pada hari-H telah melewati batas waktu (confirmation_days) berdasarkan aturan vendor, maka status order customer_paid tidak dapat dibatalkan menjadi customer_canceled
            if ($value->status == 'customer_paid' && $vendorRule->confirmation_days < now()->diffInDays($value->schedule_date)) {
                $value->rule = 1;
            } else {
                $value->rule = 0;
            }
        }

        return DataTables::of($transaction)->make(true);
    }

    public function incomingOrder(Request $request)
    {
        // Mendapatkan data order untuk vendor
        $transactionQuery = TransactionDetail::where('transactions_detail.vendor_id', Auth::user()->id)
            ->join('transactions', 'transactions.id', '=', 'transactions_detail.transaction_id')
            ->join('menu', 'transactions_detail.menu_id', '=', 'menu.id')
            ->join('users', 'transactions.customer_id', '=', 'users.id')
            ->with('testimonies');

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status)) {
            $transactionQuery->where('transactions_detail.status', '=', $request->status);
        }

        // Filter berdasarkan tanggal pemesanan
        if ($request->has('schedule_date')) {
            $transactionQuery->whereDate('transactions_detail.schedule_date', $request->schedule_date);
        }

        // Tampilkan data order
        $transaction = $transactionQuery->select('transactions.*', 'menu.*', 'users.name', 'transactions_detail.*', 'transactions_detail.id as detail_id', 'users.id as customer_id')->get();

        // Cek ketersediaan testimoni pada order
        foreach ($transaction as $key => $value) {
            $value->testimony = Testimony::where('transactions_detail_id', $value->detail_id)->count();
        }

        return DataTables::of($transaction)->make(true);
    }

    public function cancelOrder(Request $request)
    {
        // Ubah status item menjadi customer_canceled
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'customer_canceled';
        $transaction->save();

        // Kembalikan credit customer seperti semula setelah berhasil cancel order
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();
        $balance->credit += $transaction->total_price;
        $balance->save();

        $ship = BalanceNominal::where('user_id', $transaction->vendor_id)->first();

        $isiUlang = ['user_id' => Auth::user()->id, 'credit' => $transaction->total_price + $ship->shipping_costs, 'category' => 'customer_transaction_canceled'];
        BalanceHistory::create($isiUlang);

        return response()->json([
            'message' => 'Order has been cancelled'
        ]);
    }

    public function processOrder(Request $request)
    {
        // Ubah status item menjadi vendor_packing
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'vendor_packing';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been packed'
        ]);
    }

    public function deliverOrder(Request $request)
    {
        // Ubah status item menjadi vendor_delivering
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'vendor_delivering';
        $transaction->save();

        return response()->json([
            'message' => 'Order has been delivered'
        ]);
    }

    public function receiveOrder(Request $request)
    {
        // Ubah status item menjadi customer_received
        $transaction = TransactionDetail::find($request->id);
        $transaction->status = 'customer_received';
        $transaction->save();

        // Tambah credit vendor
        $vendor = BalanceNominal::where('user_id', $transaction->vendor_id)->first();
        $vendor->credit += $transaction->total_price;
        $vendor->save();

        $ship = BalanceNominal::where('user_id', $transaction->vendor_id)->first();

        // Simpan riwayat penjualan sebagai vendor_income
        $penjualan = ['user_id' => $transaction->vendor_id, 'credit' => $transaction->total_price + $ship->shipping_costs, 'category' => 'vendor_income'];
        BalanceHistory::create($penjualan);

        return response()->json([
            'message' => 'Order has been received'
        ]);
    }

    public function viewTestimony(Request $request)
    {
        // Mendapatkan data testimoni
        $transactions = TransactionDetail::where('transactions_detail.vendor_id', Auth::user()->id)
            ->where('transactions_detail.status', $request->status)
            ->where('transactions_detail.schedule_date', $request->schedule_date)
            ->with(['transaction', 'menu', 'customer', 'testimonies'])
            ->get();

        return response()->json($transactions);
    }

    public function refundReason(Request $request, string $transaction_uid)
    {
        try {
            // Keluhan pada pengajuan komplain
            $complain = [
                'refund_reason' => $request->refund_reason === "Lainnya" ?  $request->refund_other_reason : $request->refund_reason,
                'status' => 'customer_complain'
            ];

            if ($request->file('reason_proof') != null) {
                $image = $request->file('reason_proof');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                Storage::disk('public_uploads_reason_proof')->put($imageName, file_get_contents($image));
                $complain['reason_proof'] = $imageName;
            }

            // Simpan alasan refund dan bukti alasan pada TransactionDetail
            $transaction = TransactionDetail::find($transaction_uid);
            $transaction->update($complain);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function complainRefund(string $transaction_id)
    {
        $dataRequest = request()->all();

        try {
            if ($dataRequest['action'] === "reject") {
                // Jika vendor menolak pengajuan komplain, maka status order menjadi customer_received
                $payload = ['status' => 'customer_received'];
                TransactionDetail::where('id', $transaction_id)->update($payload);
            } else {
                // Jika vendor menerima pengajuan komplain, maka status order menjadi vendor_approved_complain
                $payload = ['status' => 'vendor_approved_complain'];
                $temp = TransactionDetail::where('id', $transaction_id)->with('transaction.customer')->first();
                TransactionDetail::where('id', $transaction_id)->update($payload);

                $includeShippingCost = $temp->total_price;
                if ($dataRequest['refund_value'] == 1) {
                    $includeShippingCost += $temp->transaction->shipping_costs;
                }

                // Kurangi credit vendor
                $balanceVendor = BalanceNominal::where('user_id', $temp->vendor_id)->first();
                $resultCreditVendor = $balanceVendor->credit - $includeShippingCost;
                $balanceVendor->update(['credit' => $resultCreditVendor]);
                $dataVendor = [
                    'credit' => $includeShippingCost,
                    'category' => 'customer_transaction_canceled',
                    'user_id' => $temp->vendor_id
                ];
                BalanceHistory::create($dataVendor);

                // Tambah credit customer
                $balanceCustomer = BalanceNominal::where('user_id', $temp?->transaction?->customer_id)->first();
                $resultCredit = $includeShippingCost + $balanceCustomer->credit;
                $balanceCustomer->update(['credit' => $resultCredit]);
                $dataCustomer = [
                    'credit' => $includeShippingCost,
                    'category' => 'customer_transaction_refund',
                    'user_id' => $temp->transaction->customer_id
                ];
                BalanceHistory::create($dataCustomer);
            }

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}

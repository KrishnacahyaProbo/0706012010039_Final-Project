<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Transaction;
use App\Models\UserSetting;
use App\Models\BalanceHistory;
use App\Models\BalanceNominal;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        // Mendapatkan Cart dari pelanggan yang sedang Log In dengan relasi Menu dan MenuDetail
        $cart = Cart::where('customer_id', Auth::user()->id)->with('menu', 'menu.menuDetail')->orderBy('schedule_date', 'asc')->get();

        $total = 0;
        $shipping_costs = [];

        foreach ($cart as $key => $value) {
            // Mendapatkan vendor yang menjual menu tersebut beserta ongkos kirimnya
            $user = User::find($value->menu->vendor_id);
            $delivery = Delivery::where('vendor_id', $value->menu->vendor_id)->first();

            $menu_total_price = 0;

            foreach ($value->menu->menuDetail as $key => $menuDetail) {
                if ($menuDetail->size == $value->portion) {
                    // Kalkulasi total harga untuk menu tersebut
                    $menu_total_price = $menuDetail->price * $value->quantity;
                    $value->price = $menuDetail->price;
                    // Berhentikan looping setelah menemukan menuDetail
                    break;
                }
            }

            // Menambahkan total harga menu tersebut ke total keseluruhan
            $total += $menu_total_price;

            // Menambahkan ongkos kirim ke total ongkos kirim
            $vendor_id = $value->menu->vendor_id;
            if (!isset($shipping_costs[$vendor_id])) {
                $shipping_costs[$vendor_id] = $delivery->shipping_cost;
            }
            $value->menu->vendor = $user;
        }

        // Menghitung total ongkos kirim
        $total_shipping_costs = array_sum($shipping_costs);

        // Mendapatkan BalanceNominal dari pelanggan yang sedang Log In
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

        // Mengumpulkan data ke array asosiatif
        $data = [
            'cart' => $cart,
            'shipping_costs' => $total_shipping_costs,
            'balance' => $balance
        ];

        return view('pages.cart.checkout', $data);
    }

    public function pay()
    {
        // Mendapatkan Cart dari pelanggan yang sedang Log In dengan relasi Menu dan MenuDetail
        $cart = Cart::where('customer_id', Auth::user()->id)->with('menu', 'menu.menuDetail')->orderBy('schedule_date', 'asc')->get();

        $total = 0;
        $shipping_costs = [];

        foreach ($cart as $key => $value) {
            // Mendapatkan vendor yang menjual menu tersebut beserta ongkos kirimnya
            $user = User::find($value->menu->vendor_id);
            $delivery = Delivery::where('vendor_id', $value->menu->vendor_id)->first();

            // Kalkulasi total harga untuk menu tersebut
            $menu_total_price = 0;

            foreach ($value->menu->menuDetail as $key => $menuDetail) {
                if ($menuDetail->size == $value->portion) {
                    // Kalkulasi total harga untuk menu tersebut
                    $menu_total_price = $menuDetail->price * $value->quantity;
                    $value->price = $menuDetail->price;
                    // Berhentikan looping setelah menemukan menuDetail
                    break;
                }
            }

            // Menambahkan total harga menu tersebut ke total keseluruhan
            $total += $menu_total_price;

            // Menambahkan ongkos kirim ke total ongkos kirim
            $vendor_id = $value->menu->vendor_id;
            if (!isset($shipping_costs[$vendor_id])) {
                $shipping_costs[$vendor_id] = $delivery->shipping_cost;
            }
            $value->menu->vendor = $user;
        }

        // Menghitung total ongkos kirim
        $total_shipping_costs = array_sum($shipping_costs);

        // Mendapatkan UserSetting dari pelanggan yang sedang Log In
        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();

        if ($user_setting) {
            Auth::user()->address = $user_setting->address;
            Auth::user()->longitude = $user_setting->longitude;
            Auth::user()->latitude = $user_setting->latitude;
        }

        // Membuat entri baru pada Transaction
        $transaction = new Transaction();
        $transaction->customer_id = Auth::user()->id;
        $transaction->subtotal = $total;
        $transaction->address = Auth::user()->address;
        $transaction->longitude = Auth::user()->longitude;
        $transaction->latitude = Auth::user()->latitude;
        $transaction->shipping_costs = $total_shipping_costs;

        // Menyimpan riwayat belanja sebagai customer_outcome
        $isiUlang = ['user_id' => Auth::user()->id, 'credit' => $transaction->subtotal + $transaction->shipping_costs, 'category' => 'customer_outcome'];
        BalanceHistory::create($isiUlang);
        $transaction->save();

        // Membuat entri baru pada TransactionDetail
        foreach ($cart as $key => $value) {
            $transactionDetail = new TransactionDetail();
            $transactionDetail->transaction_id = $transaction->id;
            $transactionDetail->vendor_id = $value->menu->vendor_id;
            $transactionDetail->menu_id = $value->menu_id;
            $transactionDetail->note = $value->note;
            $transactionDetail->schedule_date = $value->schedule_date;
            $transactionDetail->portion = $value->portion;
            $transactionDetail->quantity = $value->quantity;
            $transactionDetail->price = $value->price;
            $transactionDetail->total_price = $value->price * $value->quantity;
            $transactionDetail->status = 'customer_paid';
            $transactionDetail->refund_reason = null;
            $transactionDetail->save();
        }

        // Kurangi credit customer
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

        // Setelah berhasil checkout, maka credit customer berkurang dan hapus cart item
        $balance->credit -= $total + $total_shipping_costs;
        $balance->save();
        Cart::where('customer_id', Auth::user()->id)->delete();

        return redirect()->route('order.index');
    }
}

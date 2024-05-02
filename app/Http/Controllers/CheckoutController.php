<?php

namespace App\Http\Controllers;

use App\Models\BalanceNominal;
use App\Models\Cart;
use App\Models\Delivery;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $cart = Cart::where('customer_id', Auth::user()->id)
            ->with('menu', 'menu.menuDetail')
            ->orderBy('schedule_date', 'asc')
            ->get();

        $total = 0;
        $shipping_costs = [];

        foreach ($cart as $key => $value) {
            $user = User::find($value->menu->vendor_id);
            $delivery = Delivery::where('vendor_id', $value->menu->vendor_id)->first();

            $menu_total_price = 0; // Total price for this menu item

            foreach ($value->menu->menuDetail as $key => $menuDetail) {
                if ($menuDetail->size == $value->portion) {
                    $menu_total_price = $menuDetail->price * $value->quantity; // Calculate total price for this menu item
                    $value->price = $menuDetail->price; // Store the price for display purposes
                    break; // Exit the loop once the correct menuDetail is found
                }
            }

            $total += $menu_total_price; // Add the total price for this menu item to the overall total

            $vendor_id = $value->menu->vendor_id;
            if (!isset($shipping_costs[$vendor_id])) {
                $shipping_costs[$vendor_id] = $delivery->shipping_cost;
            }
            $value->menu->vendor = $user;
        }

        $total_shipping_costs = array_sum($shipping_costs);

        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

        $data = [
            'cart' => $cart,
            'shipping_costs' => $total_shipping_costs,
            'balance' => $balance
        ];

        return view('pages.cart.checkout', $data);
    }

    public function pay()
    {
        $cart = Cart::where('customer_id', Auth::user()->id)
            ->with('menu', 'menu.menuDetail')
            ->orderBy('schedule_date', 'asc')
            ->get();

        $total = 0;
        $shipping_costs = [];

        foreach ($cart as $key => $value) {
            $user = User::find($value->menu->vendor_id);
            $delivery = Delivery::where('vendor_id', $value->menu->vendor_id)->first();

            $menu_total_price = 0; // Total price for this menu item

            foreach ($value->menu->menuDetail as $key => $menuDetail) {
                if ($menuDetail->size == $value->portion) {
                    $menu_total_price = $menuDetail->price * $value->quantity; // Calculate total price for this menu item
                    $value->price = $menuDetail->price; // Store the price for display purposes
                    break; // Exit the loop once the correct menuDetail is found
                }
            }

            $total += $menu_total_price; // Add the total price for this menu item to the overall total

            $vendor_id = $value->menu->vendor_id;
            if (!isset($shipping_costs[$vendor_id])) {
                $shipping_costs[$vendor_id] = $delivery->shipping_cost;
            }
            $value->menu->vendor = $user;
        }

        $total_shipping_costs = array_sum($shipping_costs);

        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($user_setting) {
            Auth::user()->address = $user_setting->address;
            Auth::user()->longitude = $user_setting->longitude;
            Auth::user()->latitude = $user_setting->latitude;
        }

        $transaction = new Transaction();
        $transaction->customer_id = Auth::user()->id;
        $transaction->subtotal = $total;
        $transaction->address = Auth::user()->address;
        $transaction->longitude = Auth::user()->longitude;
        $transaction->latitude = Auth::user()->latitude;
        $transaction->distance_between = 0;
        $transaction->shipping_costs = $total_shipping_costs;
        $transaction->status = 'customer_unpaid';
        $transaction->save();

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
            $transactionDetail->refund_reason = null;
            $transactionDetail->save();
        }

        return redirect()->route('order.index');
    }
}

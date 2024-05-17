<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CartController extends Controller
{
    public function index()
    {
        // Mendapatkan Cart dari user yang sedang Log In dengan relasi Menu dan MenuDetail
        $cart = Cart::where('customer_id', Auth::user()->id)->with('menu', 'menu.menuDetail')->orderBy('schedule_date', 'asc')->get();

        foreach ($cart as $key => $value) {
            // Konversi schedule_date ke DateTime object untuk dibandingkan dengan tanggal saat ini
            $scheduleDate = new \DateTime($value->schedule_date);
            // Mendapatkan tanggal saat ini
            $currentDate = new \DateTime();

            // Jika schedule_date telah berlalu, maka hapus Cart item
            if ($scheduleDate < $currentDate) {
                $value->delete();
                unset($cart[$key]);
            } else {
                // Jika schedule_date belum berlalu, maka tambahkan ke Cart item
                $user = User::find($value->menu->vendor_id);
                $value->menu->vendor_name = $user->name;
            }
        }

        return view('pages.cart.index', ['cart' => $cart]);
    }

    public function data()
    {
        try {
            // Mendapatkan Cart dari user yang sedang Log In dengan relasi Menu dan MenuDetail
            $cart = Cart::where('customer_id', Auth::user()->id)->with('menu', 'menu.menuDetail')->get();

            foreach ($cart as $key => $value) {
                $user = User::find($value->menu->vendor_id);
                $value->menu->vendor_name = $user->name;
            }

            return response()->json(['cart' => $cart], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve data: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input data
            $validatedData = $request->validate([
                'menuId' => 'required|numeric',
                'menuDate' => 'required',
                'previousSelectedOption' => 'required|string',
                'currentQuantity' => 'required|numeric',
                'notes' => 'nullable|string'
            ]);

            // Mencari Cart item berdasarkan customer_id, menu_id, dan portion
            $cartItem = Cart::where('customer_id', Auth::user()->id)->where('menu_id', $validatedData['menuId'])->where('portion', $validatedData['previousSelectedOption'])->first();

            if ($cartItem) {
                // Jika item sudah ada, akumulasi kuantitasnya
                $cartItem->portion = $validatedData['previousSelectedOption'];
                $cartItem->quantity += $validatedData['currentQuantity'];
                $cartItem->note = $validatedData['notes'];
            } else {
                // Jika item belum ada, buat entri baru
                $cartItem = new Cart();
                $cartItem->customer_id = Auth::user()->id;
                $cartItem->menu_id = $validatedData['menuId'];
                $cartItem->schedule_date = $validatedData['menuDate'];
                $cartItem->portion = $validatedData['previousSelectedOption'];
                $cartItem->quantity = $validatedData['currentQuantity'];
                $cartItem->note = $validatedData['notes'];
            }

            // Menyimpan entri
            $cartItem->save();

            return response()->json(['message' => 'Item added to cart successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validasi input data
            $validatedData = $request->validate([
                'portion' => 'nullable|string',
                'quantity' => 'nullable|numeric',
                'note' => 'nullable|string'
            ]);

            // Mencari Cart item berdasarkan cart_menu_id dan memperbarui entri
            $cartItem = Cart::find($request->input('cart_menu_id'));
            $cartItem->portion = $validatedData['portion'];
            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->note = $validatedData['note'];

            // Menyimpan entri
            $cartItem->save();

            return response()->json([
                'message' => 'Cart item updated successfully',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            // Menghapus Cart item berdasarkan id
            $id = $request->input('id');
            Cart::where('id', $id)->delete();

            return response()->json([
                'message' => 'Cart item deleted successfully',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the Cart item'], 500);
        }
    }
}

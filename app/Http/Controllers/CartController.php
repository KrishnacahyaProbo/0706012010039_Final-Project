<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception; // Import the Exception class

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('customer_id', Auth::user()->id)
            ->with('menu', 'menu.menuDetail')
            ->orderBy('schedule_date', 'asc')
            ->get();

        foreach ($cart as $key => $value) {
            $user = User::find($value->menu->vendor_id);
            $value->menu->vendor_name = $user->name;
        }

        return view('pages.cart.index', ['cart' => $cart]);
    }

    public function data()
    {
        try {
            // Retrieve data
            $cart = Cart::where('customer_id', Auth::user()->id)
                ->with('menu', 'menu.menuDetail')
                ->get();

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
            // Validate and sanitize the input data
            $validatedData = $request->validate([
                'menuId' => 'required|numeric',
                'menuDate' => 'required',
                'previousSelectedOption' => 'required|string',
                'currentQuantity' => 'required|numeric',
                'notes' => 'nullable|string'
            ]);

            // Find if the item already exists in the cart for the authenticated user
            $cartItem = Cart::where('customer_id', Auth::user()->id)
                ->where('menu_id', $validatedData['menuId'])
                ->where('portion', $validatedData['previousSelectedOption'])
                ->first();

            if ($cartItem) {
                // If the item exists, update its details
                $cartItem->portion = $validatedData['previousSelectedOption'];
                $cartItem->quantity += $validatedData['currentQuantity'];
                $cartItem->note = $validatedData['notes'];
            } else {
                // If the item doesn't exist, create a new entry in the cart
                $cartItem = new Cart();
                $cartItem->customer_id = Auth::user()->id;
                $cartItem->menu_id = $validatedData['menuId'];
                $cartItem->schedule_date = $validatedData['menuDate'];
                $cartItem->portion = $validatedData['previousSelectedOption'];
                $cartItem->quantity = $validatedData['currentQuantity'];
                $cartItem->note = $validatedData['notes'];
            }

            // Save the cart item
            $cartItem->save();

            // Return a response indicating success
            return response()->json(['message' => 'Item added to cart successfully'], 200);
        } catch (Exception $e) {
            // Handle any exceptions that occur during the database operation
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validate and sanitize the input data
            $validatedData = $request->validate([
                'portion' => 'nullable|string',
                'quantity' => 'nullable|numeric',
                // 'notes' => 'nullable|string'
            ]);

            // Find the cart item
            $cartItem = Cart::find($request->input('cart_menu_id'));

            // Update the cart item details
            $cartItem->portion = $validatedData['portion'];
            $cartItem->quantity = $validatedData['quantity'];
            // $cartItem->note = $validatedData['notes'];

            // Save the updated cart item
            $cartItem->save();

            // Return a response indicating success
            return response()->json([
                'message' => 'Cart item updated successfully',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the database operation
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
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

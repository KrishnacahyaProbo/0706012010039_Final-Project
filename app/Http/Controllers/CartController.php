<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Exception; // Import the Exception class
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('customer_id', Auth::user()->id)
            ->with('menu', 'menu.menuDetail')
            ->get();
        return view('pages.cart.index', compact('cart'));
    }

    public function data()
    {
        try {
            // Retrieve data
            $cart = Cart::where('customer_id', Auth::user()->id)
                ->with('menu', 'menu.menuDetail')
                ->get();

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
                'previousSelectedOption' => 'required|string',
                'currentQuantity' => 'required|numeric',
                'notes' => 'nullable|string'
            ]);

            // Find if the item already exists in the cart for the authenticated user
            $cartItem = Cart::where('customer_id', Auth::user()->id)
                ->where('menu_id', $validatedData['menuId'])
                ->where('status', 'customer_unpaid')
                ->where('portion', $validatedData['previousSelectedOption'])
                ->first();

            if ($cartItem) {
                // If the item exists, update its details
                $cartItem->portion = $validatedData['previousSelectedOption'];
                $cartItem->quantity = $validatedData['currentQuantity'];
                $cartItem->note = $validatedData['notes'];
            } else {
                // If the item doesn't exist, create a new entry in the cart
                $cartItem = new Cart();
                $cartItem->customer_id = Auth::user()->id;
                $cartItem->menu_id = $validatedData['menuId'];
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Exception; // Import the Exception class
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller
{
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

            // Create a new entry in the cart table or model
            // Create a new Cart instance
            $cartItem = new Cart();
            $cartItem->customer_id = Auth::user()->id;
            $cartItem->menu_id = $validatedData['menuId'];
            $cartItem->portion = $validatedData['previousSelectedOption'];
            $cartItem->quantity = $validatedData['currentQuantity'];
            $cartItem->note = $validatedData['notes'];

            // Save the new cart item
            $cartItem->save();

            // Check if the status is 'unpaid_customer'
            if ($cartItem->status === 'unpaid_customer') {
                // Retrieve the cart item with the same menu ID, 'unpaid_customer' status, and owned by the same customer
                $existingCartItem = Cart::where('menu_id', $validatedData['menuId'])
                    ->where('status', 'unpaid_customer')
                    ->where('customer_id', Auth::user()->id)
                    ->first();

                if ($existingCartItem) {
                    // Update the quantity of the existing cart item
                    $existingCartItem->quantity += $validatedData['currentQuantity'];
                    $existingCartItem->save();
                }
            }
            // Add any additional fields you may have in your Cart model

            // Save the cart item to the database
            $cartItem->save();

            // Optionally, you can return a response indicating success
            return response()->json(['message' => 'Item added to cart successfully'], 200);
        } catch (Exception $e) {
            // Handle any exceptions that occur during the database operation
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function settings(Request $request)
    {
        try {
            // Mendapatkan entri Delivery ataupun membuat entri baru jika belum ada
            $delivery = Delivery::firstOrNew(['vendor_id' => Auth::user()->id]);

            // Memperbarui entity
            $delivery->vendor_id = Auth::user()->id;
            $delivery->distance_between = $request->distance_between;
            $delivery->shipping_cost = $request->shipping_cost;

            // Menyimpan entri
            $delivery->save();

            return response()->json(['message' => 'Data inserted/updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to insert/update data: ' . $e->getMessage()], 500);
        }
    }
}

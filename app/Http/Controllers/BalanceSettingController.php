<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BalanceNominal;
use Illuminate\Support\Facades\Auth;

class BalanceSettingController extends Controller
{
    public function settings(Request $request)
    {
        try {
            // Retrieve the user setting record or create a new one if it doesn't exist
            $balance = BalanceNominal::firstOrNew(['user_id' => Auth::user()->id]);

            // Update the attributes
            $balance->user_id = Auth::user()->id;
            $balance->bank_name = $request->bank_name;
            $balance->account_number = $request->account_number;
            $balance->account_holder_name = $request->account_holder_name;

            // Save the record
            $balance->save();

            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}

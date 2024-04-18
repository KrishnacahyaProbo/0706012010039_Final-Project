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
            $ballance = BalanceNominal::firstOrNew(['user_id' => Auth::user()->id]);

            // Update the attributes
            $ballance->user_id = Auth::user()->id;
            $ballance->bank_name = $request->bank_name;
            $ballance->account_number = $request->account_number;
            $ballance->account_holder_name = $request->account_holder_name;

            // Save the record
            $ballance->save();

            // Optionally, you can return a success response or perform additional actions
            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}

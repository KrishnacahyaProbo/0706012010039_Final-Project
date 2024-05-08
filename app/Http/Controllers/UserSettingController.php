<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use App\Models\BalanceNominal;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    public function index()
    {
        $delivery = Delivery::where('vendor_id', Auth::user()->id)->first();
        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();
        return view('pages.users.settings.index', compact('user_setting', 'delivery', 'balance'));
    }

    public function data()
    {
        try {
            // Retrieve data
            $delivery = Delivery::where('vendor_id', Auth::user()->id)->first();
            $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
            $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

            return response()->json(['delivery' => $delivery, 'user_setting' => $user_setting, 'balance' => $balance], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve data: ' . $e->getMessage()], 500);
        }
    }

    public function about(Request $request)
    {
        try {
            // Retrieve the user setting record or create a new one if it doesn't exist
            $user_setting = UserSetting::firstOrNew(['vendor_id' => Auth::user()->id]);

            // Update the attributes
            $user_setting->vendor_id = Auth::user()->id;
            $user_setting->about_us = $request->about_us;

            // Save the record
            $user_setting->save();

            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function order(Request $request)
    {
        try {
            // Retrieve the user setting record or create a new one if it doesn't exist
            $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
            if (!$user_setting) {
                $user_setting = new UserSetting();
            }

            // Update the attributes
            $user_setting->vendor_id = Auth::user()->id;
            $user_setting->confirmation_days = $request->confirmation_days;
            if (isset($user_setting->latitude)) $user_setting->latitude = $request->latitude;
            if (isset($user_setting->longitude)) $user_setting->longitude = $request->longitude;
            if (isset($user_setting->address)) $user_setting->address = $request->address;

            // Save the record
            $user_setting->save();

            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}

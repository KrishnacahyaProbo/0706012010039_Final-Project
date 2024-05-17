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
        // Mendapatkan UserSetting, Delivery, BalanceNominal dari user yang sedang Log In
        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
        $delivery = Delivery::where('vendor_id', Auth::user()->id)->first();
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

        return view('pages.users.settings.index', compact('user_setting', 'delivery', 'balance'));
    }

    public function data()
    {
        try {
            // Mendapatkan UserSetting, Delivery, dan BalanceNominal dari user yang sedang Log In
            $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
            $delivery = Delivery::where('vendor_id', Auth::user()->id)->first();
            $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

            return response()->json(['delivery' => $delivery, 'user_setting' => $user_setting, 'balance' => $balance], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve data: ' . $e->getMessage()], 500);
        }
    }

    public function about(Request $request)
    {
        try {
            // Mendapatkan entri UserSetting ataupun membuat entri baru jika belum ada
            $user_setting = UserSetting::firstOrNew(['vendor_id' => Auth::user()->id]);

            // Memperbarui entity
            $user_setting->vendor_id = Auth::user()->id;
            $user_setting->about_us = $request->about_us;

            // Menyimpan entri
            $user_setting->save();

            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function order(Request $request)
    {
        try {
            // Mendapatkan entri UserSetting ataupun membuat entri baru jika belum ada
            $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
            if (!$user_setting) {
                $user_setting = new UserSetting();
            }

            // Memperbarui entity
            $user_setting->vendor_id = Auth::user()->id;
            $user_setting->confirmation_days = $request->confirmation_days;
            if (isset($request->latitude)) $user_setting->latitude = $request->latitude;
            if (isset($request->longitude)) $user_setting->longitude = $request->longitude;
            if (isset($request->address)) $user_setting->address = $request->address;

            // Menyimpan entri
            $user_setting->save();

            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}

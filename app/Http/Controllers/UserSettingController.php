<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use App\Models\Delivery;
use App\Http\Requests\StoreUserSettingRequest;
use App\Http\Requests\UpdateUserSettingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $delivery = Delivery::where('vendor_id', Auth::user()->id)->first();
        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
        return view('pages.users.settings.index', compact('user_setting', 'delivery'));
    }

    public function getDataSettings()
    {
        try {
            // Retrieve data
            $delivery = Delivery::where('vendor_id', Auth::user()->id)->first();
            $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();

            // Optionally, you can return the retrieved data or perform additional actions
            return response()->json(['delivery' => $delivery, 'user_setting' => $user_setting], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Failed to retrieve data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserSettingRequest $request)
    {
        //
    }

    public function settingsPemesanan(Request $request)
    {
        try {
            // Retrieve the user setting record or create a new one if it doesn't exist
            $user_setting = UserSetting::firstOrNew(['vendor_id' => Auth::user()->id]);

            // Update the attributes
            $user_setting->vendor_id = Auth::user()->id;
            $user_setting->confirmation_days = $request->confirmation_days;
            $user_setting->latitude = $request->latitude;
            $user_setting->longitude = $request->longitude;
            $user_setting->address = $request->address;

            // Save the record
            $user_setting->save();

            // Optionally, you can return a success response or perform additional actions
            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSetting $userSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserSetting $userSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserSettingRequest $request, UserSetting $userSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSetting $userSetting)
    {
        //
    }
}

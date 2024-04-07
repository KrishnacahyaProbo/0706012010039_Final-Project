<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
        dd($request->all());
    }

    public function settingsDelivery(Request $request){
        try {
            // Attempt to find the delivery record
            $delivery = Delivery::firstOrNew(['vendor_id' => Auth::user()->id]);

            // Update the attributes
            $delivery->vendor_id = Auth::user()->id;
            $delivery->distance_between = $request->distance_between;
            $delivery->shipping_cost = $request->shipping_cost; // Corrected shipping_cost assignment

            // Save the record
            $delivery->save();

            // Optionally, you can return a success message or perform additional actions
            return response()->json(['message' => 'Data inserted/updated successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Failed to insert/update data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}

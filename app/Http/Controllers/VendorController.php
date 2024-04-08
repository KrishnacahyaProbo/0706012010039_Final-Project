<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    //
    public function data(Request $request)
    {
        try {
            // Get pagination parameters from the request
            $page = $request->input('page');
            $perPage = $request->input('perPage');

            // Assuming User is your Eloquent model for vendors
            $vendorQuery = User::role('vendor')->with('Delivery', 'UserSetting'); // Eager load delivery and user setting relationships

            if ($request->has('search') && $request->search !== null) {
                $searchTerm = $request->search;
                $vendorQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            }

            $vendorCount = $vendorQuery->count();

            if ($vendorCount > 10) {
                $vendor_data = $vendorQuery->paginate($perPage, ['*'], 'page', $page);
            } else {
                $vendor_data = $vendorQuery->get();
            }

            // Success message
            $successMessage = 'Data retrieved successfully.';

            // Return JSON response with success message and paginated data
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'data' => $vendor_data
            ]);
        } catch (Exception $e) {
            // Handle any exceptions
            // You can log the error or return a custom error message
            $errorMessage = $e->getMessage();
            return response()->json([
                'success' => false,
                'error' => $errorMessage
            ], 500);
        }
    }
}

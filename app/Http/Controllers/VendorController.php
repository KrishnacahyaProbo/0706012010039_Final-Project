<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        return view('pages.customer.vendor');
    }

    public function data(Request $request)
    {
        try {
            // Get pagination parameters from the request
            $page = $request->input('page');
            $perPage = $request->input('perPage');

            // Eager load delivery and user setting relationships
            $vendorQuery = User::role('vendor')->with('Delivery', 'UserSetting');

            if ($request->has('search') && $request->search !== null) {
                $searchTerm = $request->search;
                $vendorQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            }

            // Get the total count of vendors
            $vendorCount = $vendorQuery->count();

            if ($vendorCount > 10) {
                $vendor_data = $vendorQuery->paginate($perPage, ['*'], 'page', $page);
            } else {
                $vendor_data = $vendorQuery->get();
            }

            // Calculate vendor rating
            foreach ($vendor_data as $vendor) {
                $rating = Testimony::where('vendor_id', $vendor->id)->avg('rating');

                if ($rating === null) {
                    $vendor->rating = 0;
                } else {
                    $vendor->rating = number_format($rating, 1, ',', '.');
                }
            }

            $successMessage = 'Data retrieved successfully.';

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'data' => $vendor_data
            ]);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json([
                'success' => false,
                'error' => $errorMessage
            ], 500);
        }
    }

    public function menu($nama_vendor)
    {
        try {
            $vendor = User::with('Delivery', 'menu', 'menu.menu_schedule', 'UserSetting')->where('name', $nama_vendor)->first();

            // Calculate vendor rating
            $rating = Testimony::where('vendor_id', $vendor->id)->avg('rating');
            $vendor->rating = $rating;

            if ($rating === null) {
                $vendor->rating = 0;
            } else {
                $vendor->rating = number_format($rating, 1, ',', '.');
            }

            return view('pages.customer.vendor.detailVendor', compact('vendor'));
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error occured while fetching detail menu data',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

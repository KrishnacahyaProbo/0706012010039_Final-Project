<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Testimony;
use App\Models\UserSetting;
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
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // Eager load delivery and user setting relationships
            $vendorQuery = User::role('vendor')->with('Delivery', 'UserSetting');

            $allVendor = User::role('vendor')->get();
            // Nearby vendor (keseluruhan vendor yang menjangkau jarak pengiriman terhadap customer)
            $listVendor = [];

            foreach ($allVendor as $vendor) {
                $user_setting = UserSetting::where('vendor_id', $vendor->id)->first();
                $delivery = Delivery::where('vendor_id', $vendor->id)->first();
                $vendorLat = $user_setting ? $user_setting->latitude : $vendor->latitude;
                $vendorLng = $user_setting ? $user_setting->longitude : $vendor->longitude;

                // Perhitungan jarak antara customer dan vendor
                $jarak = $this->calculateDistance($latitude, $longitude, $vendorLat, $vendorLng);
                if ($jarak <= ($delivery ? $delivery->distance_between : 10)) {
                    array_push($listVendor, $vendor->id);
                }
            }

            // Search vendors by name
            if ($request->has('search') && $request->search !== null) {
                $searchTerm = $request->search;
                $vendorQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter vendors by list of vendor IDs
            $vendorQuery = $vendorQuery->whereIn('id', $listVendor);

            // Get the total count of vendors
            $vendorCount = $vendorQuery->count();

            // Paginate the vendors
            if ($vendorCount > 10) {
                $vendor_data = $vendorQuery->paginate($perPage, ['*'], 'page', $page);
            } else {
                $vendor_data = $vendorQuery->get();
            }

            // Calculate vendor rating
            foreach ($vendor_data as $vendor) {
                $rating = Testimony::where('vendor_id', $vendor->id)->avg('rating');

                // If vendor has no rating, set it to 0
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

    public function detailVendor($nama_vendor)
    {
        try {
            $vendor = User::with('Delivery', 'menu', 'menu.menu_schedule', 'UserSetting')->where('name', $nama_vendor)->first();

            // Calculate vendor rating
            $rating = Testimony::where('vendor_id', $vendor->id)->avg('rating');
            $vendor->rating = $rating;

            // If vendor has no rating, set it to 0
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

    public function calculateDistance($userLat, $userLng, $vendorLat, $vendorLng)
    {
        try {
            $earthRadiusKm = 6371; // Earth radius in kilometers
            $userLatRadians = $this->toRadians($userLat);
            $vendorLatRadians = $this->toRadians($vendorLat);
            $latDiff = $this->toRadians($vendorLat - $userLat);
            $lngDiff = $this->toRadians($vendorLng - $userLng);

            $a = sin($latDiff / 2) * sin($latDiff / 2) +
                cos($userLatRadians) * cos($vendorLatRadians) *
                sin($lngDiff / 2) * sin($lngDiff / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $earthRadiusKm * $c; // Distance in kilometers
            return $distance = number_format($distance, 2);
        } catch (\Throwable $th) {
            dd($th, $userLat, $userLng, $vendorLat, $vendorLng);
        }
    }

    public function toRadians($degrees)
    {
        // Convert degrees to radians
        return $degrees * (pi() / 180);
    }
}

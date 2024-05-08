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
                $vendorLatitude = $user_setting ? $user_setting->latitude : $vendor->latitude;
                $vendorLongitude = $user_setting ? $user_setting->longitude : $vendor->longitude;

                // Perhitungan jarak antara customer dan vendor
                $jarak = $this->calculateDistance($latitude, $longitude, $vendorLatitude, $vendorLongitude);
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
                $user_setting = UserSetting::where('vendor_id', $vendor->id)->first();
                $vendor->latitude = $user_setting ? $user_setting->latitude : $vendor->latitude;
                $vendor->longitude = $user_setting ? $user_setting->longitude : $vendor->longitude;
                $vendor->vendorAddress = $user_setting ? $user_setting->address : $vendor->address;
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
            $user_setting = UserSetting::where('vendor_id', $vendor->id)->first();
            $vendor->address = $user_setting ? $user_setting->address : $vendor->address;
            $vendor->latitude = $user_setting ? $user_setting->latitude : $vendor->latitude;
            $vendor->longitude = $user_setting ? $user_setting->longitude : $vendor->longitude;

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

    public function calculateDistance($vendorLatitude, $vendorLongitude, $customerLatitude, $customerLongitude)
    {
        try {
            // Jari-jari (radius) bumi dalam kilometer
            $earthRadiusKilometer = 6371;
            // Perhitungan trigonometri dalam PHP menggunakan radian (bukan derajat) sehingga diperlukan konversi pada latitude
            // Konversi latitude pada vendor dari derajat ke radian
            $vendorLatitudeRadians = $this->toRadians($vendorLatitude);
            // Konversi latitude pada customer dari derajat ke radian
            $customerLatitudeRadians = $this->toRadians($customerLatitude);
            // Selisih jarak antara latitude vendor terhadap customer (dalam radian)
            $latitudeDifference = $this->toRadians($vendorLatitude - $customerLatitude);
            // Selisih jarak antara longitude vendor terhadap customer (dalam radian)
            $longitudeDifference = $this->toRadians($vendorLongitude - $customerLongitude);

            // Perhitungan jarak sudut antara titik lokasi vendor terhadap customer pada permukaan bola (seperti Bumi)
            $angularDistance = sin($latitudeDifference / 2) * sin($latitudeDifference / 2) +
                cos($vendorLatitudeRadians) * cos($customerLatitudeRadians) *
                sin($longitudeDifference / 2) * sin($longitudeDifference / 2);
            // Perhitungan sudut pusat (sudut antara dua titik pada permukaan bola yang diukur dari pusat bola) di mana atan2 mengembalikan nilai dalam radian dari dua variabel
            $centralAngle = 2 * atan2(sqrt($angularDistance), sqrt(1 - $angularDistance));

            // Jarak antara dua titik pada permukaan bola diukur dari jari-jari bumi dikali dengan sudut pusat
            $distance = $earthRadiusKilometer * $centralAngle;
            // Hasil jarak antar dua titik lokasi dalam kilometer dengan nilai hingga 2 desimal di belakang koma
            return $distance = number_format($distance, 2);
        } catch (\Throwable $th) {
            dd($th, $customerLatitude, $customerLongitude, $vendorLatitude, $vendorLongitude);
        }
    }

    public function toRadians($degrees)
    {
        // Konversi derajat ke radian
        return $degrees * (pi() / 180);
    }
}

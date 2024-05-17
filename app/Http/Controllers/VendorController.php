<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Testimony;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        // Mendapatkan UserSetting dari vendor
        $param['user_setting'] = UserSetting::where('vendor_id', Auth::user()->id)->first();
        return view('pages.customer.vendor')->with($param);
    }

    public function data(Request $request)
    {
        try {
            // Mengambil parameter pagination berdasarkan request:
            // Halaman ke-x (sekian)
            $page = $request->input('page');
            // Jumlah data per halaman
            $perPage = $request->input('perPage');
            // Latitude dan longitude dari customer
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // Mengambil data vendor
            $vendorQuery = User::role('vendor')->with('Delivery', 'UserSetting')->orderBy('created_at', 'desc');
            $allVendor = User::role('vendor')->get();
            // Menampung nearby vendor (keseluruhan vendor yang menjangkau jarak pengiriman terhadap customer)
            $listVendor = [];

            // Looping untuk mengecek vendor yang berada dalam jarak pengiriman terhadap customer
            foreach ($allVendor as $vendor) {
                // Mengambil data setting dan pengiriman vendor
                $user_setting = UserSetting::where('vendor_id', $vendor->id)->first();
                $delivery = Delivery::where('vendor_id', $vendor->id)->first();
                // Mengambil latitude dan longitude vendor
                $vendorLatitude = $user_setting ? $user_setting->latitude : $vendor->latitude;
                $vendorLongitude = $user_setting ? $user_setting->longitude : $vendor->longitude;

                // Perhitungan jarak antara customer dan vendor
                $jarak = $this->calculateDistance($latitude, $longitude, $vendorLatitude, $vendorLongitude);
                // Jika jarak antara customer dan vendor kurang dari atau sama dengan jarak pengiriman
                if ($jarak <= ($delivery ? $delivery->distance_between : PHP_INT_MAX)) {
                    // Menambahkan vendor ke dalam list vendor
                    array_push($listVendor, $vendor->id);
                }
            }

            if ($request->has('search') && $request->search !== null) {
                // Kata kunci pencarian vendor
                $searchTerm = $request->search;
                // Filter vendor berdasarkan nama
                $vendorQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter vendor lists berdasarkan id dan dapatkan total vendor
            $vendorQuery = $vendorQuery->whereIn('id', $listVendor);
            $vendorCount = $vendorQuery->count();

            // Pagination vendor
            if ($vendorCount > 10) {
                $vendor_data = $vendorQuery->paginate($perPage, ['*'], 'page', $page);
            } else {
                $vendor_data = $vendorQuery->get();
            }

            foreach ($vendor_data as $vendor) {
                $rating = Testimony::where('vendor_id', $vendor->id)->avg('rating');
                $user_setting = UserSetting::where('vendor_id', $vendor->id)->first();
                $vendor->latitude = $user_setting ? $user_setting->latitude : $vendor->latitude;
                $vendor->longitude = $user_setting ? $user_setting->longitude : $vendor->longitude;
                $vendor->vendorAddress = $user_setting ? $user_setting->address : $vendor->address;

                if ($rating === null) {
                    // Jika vendor belum memiliki rating dari testimoni, atur rating ke 0
                    $vendor->rating = 0;
                } else {
                    // Jika vendor memiliki rating dari testimoni, atur rating sesuai dengan rating tersebut
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
            // Mendapatkan data vendor berdasarkan nama vendor dengan relasi Delivery, Menu, MenuSchedule, dan UserSetting
            $vendor = User::with('Delivery', 'menu', 'menu.menu_schedule', 'UserSetting')->where('name', $nama_vendor)->first();

            // Kalkulasi rata-rata rating vendor dengan total testimoni
            $rating = Testimony::where('vendor_id', $vendor->id)->avg('rating');
            $vendor->rating = $rating;

            $user_setting = UserSetting::where('vendor_id', $vendor->id)->first();
            $vendor->address = $user_setting ? $user_setting->address : $vendor->address;
            $vendor->latitude = $user_setting ? $user_setting->latitude : $vendor->latitude;
            $vendor->longitude = $user_setting ? $user_setting->longitude : $vendor->longitude;

            if ($rating === null) {
                // Jika vendor belum memiliki rating dari testimoni, atur rating ke 0
                $vendor->rating = 0;
            } else {
                // Jika vendor memiliki rating dari testimoni, atur rating sesuai dengan rating tersebut
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
            // Perhitungan trigonometri dalam PHP menggunakan radian (bukan derajat) sehingga diperlukan konversi pada latitude:
            // Konversi latitude pada vendor dari derajat ke radian
            $vendorLatitudeRadians = $this->toRadians($vendorLatitude);
            // Konversi latitude pada customer dari derajat ke radian
            $customerLatitudeRadians = $this->toRadians($customerLatitude);
            // Selisih jarak antara latitude vendor terhadap customer (dalam radian)
            $latitudeDifference = $this->toRadians($vendorLatitude - $customerLatitude);
            // Selisih jarak antara longitude vendor terhadap customer (dalam radian)
            $longitudeDifference = $this->toRadians($vendorLongitude - $customerLongitude);

            // Perhitungan jarak sudut antara titik lokasi vendor terhadap customer pada permukaan bola (seperti Bumi)
            $a = sin($latitudeDifference / 2) * sin($latitudeDifference / 2) +
                cos($vendorLatitudeRadians) * cos($customerLatitudeRadians) *
                sin($longitudeDifference / 2) * sin($longitudeDifference / 2);
            // Perhitungan sudut pusat (sudut antara dua titik pada permukaan bola yang diukur dari pusat bola) di mana atan2 mengembalikan nilai dalam radian dari dua variabel
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            // Jarak antara dua titik pada permukaan bola diukur dari jari-jari bumi dikali dengan sudut pusat
            $distance = $earthRadiusKilometer * $c;
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

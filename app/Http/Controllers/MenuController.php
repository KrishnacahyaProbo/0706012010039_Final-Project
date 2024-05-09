<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Schedule;
use App\Models\MenuDetail;
use App\Models\MenuSchedule;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        $vendor_name = User::where('id', Auth::user()->id)->first()->name;
        return view('pages.menu.index', compact('vendor_name'));
    }

    public function show(Request $request)
    {
        try {
            // Retrieve detail menu dari database
            $dataDetail = Menu::with('menuDetail', 'menu_schedule')->where('id', $request->id)->first();

            // Jika data detail menu ditemukan, maka tampilkan dengan JSON response
            if ($dataDetail) {
                return response()->json([
                    'message' => 'Detail Menu data found',
                    'status' => true,
                    'data' => $dataDetail,
                ]);
            } else {
                // Jika data detail menu tidak ditemukan, maka tampilkan error response
                return response()->json([
                    'message' => 'Detail Menu data not found',
                    'success' => false,
                ], 404); // HTTP status code 404 "Not Found"
            }
        } catch (\Exception $e) {
            // Jika terjadi exception saat operasi database, maka tampilkan error response
            return response()->json([
                'message' => 'Error occured while fetching detail menu data',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500); // HTTP status code 500 "Internal Server Error"
        }
    }

    public function schedule(Request $request)
    {
        try {
            $dataMenu = Schedule::with(['menus' => function ($query) use ($request) {
                // Menambahkan kondisi whereHas untuk memfilter berdasarkan relasi menus
                $query->where('vendor_id', $request->id);
            }, 'menus.menuDetail'])
                ->where('schedule', '=', $request->date)
                ->first();

            if ($dataMenu) {
                $vendorRule = UserSetting::where('vendor_id', $request->id)->first();
                $date = strtotime(date("Y-m-d", strtotime("-" . $vendorRule->confirmation_days - 1 . "day", strtotime($dataMenu->schedule))));

                // Jika pada hari-H telah melewati batas waktu (confirmation_days) berdasarkan aturan vendor, maka tidak bisa melakukan pemesanan
                if (strtotime(now()) <= $date) {
                    $dataMenu->rule = 1;
                } else {
                    $dataMenu->rule = 0;
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Schedule data found',
                    'data_menu' => $dataMenu
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No schedule data found',
                    'data_menu' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching schedule data: ' . $e->getMessage(),
                'data_menu' => null
            ], 500);
        }
    }

    public function dataMenuVendor(Request $request)
    {
        try {
            $vendorId = $request->input('vendor_id');

            // Define an empty array to store menu items
            $menu = [];

            // Fetching the menu for the specified vendor in chunks
            Menu::where('vendor_id', $vendorId)->chunk(100, function ($chunk) use (&$menu) {
                // Process each chunk of menu items
                foreach ($chunk as $menuItem) {
                    // Add the menu item to the menu array
                    $menu[] = $menuItem;
                }
            });
            return response()->json(['menu' => $menu], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function data()
    {
        try {
            // Fetch menu items dari database berdasarkan vendor_id
            // Jika user yang sedang login adalah vendor, maka ambil menu items berdasarkan vendor_id
            $vendorId = auth()->user()->id;
            $menuItems = Menu::with('menuDetail')->where('vendor_id', $vendorId)->get();

            return DataTables::of($menuItems)
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500); // HTTP status code 500 "Internal Server Error"
        }
    }

    public function store(Request $request)
    {
        try {
            // Operasi database yang dijalankan sebagai satu kesatuan logis, baik yang semuanya berhasil dilakukan (committed) atau semuanya dibatalkan (rolled back) jika terdapat kesalahan
            DB::beginTransaction();

            if ($request->id != null) {
                // Jika menu_id tersedia, maka ubah data menu yang sudah ada
                $menu = Menu::findOrFail($request->input('id'));
            } else {
                // Jika menu_id tidak tersedia, maka buat data menu baru
                $menu = new Menu();
            }

            // Isi atribut menu dengan request data
            $menu->vendor_id = Auth::user()->id;
            $menu->menu_name = $request->input('menu_name');
            $menu->description = $request->input('description');

            // Cek tipe pedas atau tidak pedas
            if ($request->input('spicy') == 'spicy') {
                $menu->type = 'spicy';
            } else {
                $menu->type = 'no_spicy';
            }

            // Cek jika request memiliki file gambar
            if ($request->hasFile('image')) {
                // Simpan file gambar ke storage
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('menu'), $imageName);
                $menu->image = $imageName;
            }

            // Simpan data menu ke database
            $menu->save();

            // Simpan detail menu ke database
            MenuDetail::where('menu_id', $menu->id)->delete();

            $menuDetails = [];
            foreach ($request->size as $index => $s) {
                if ($s != null && $request->price[$index] !== null) {
                    // Pisahkan nominal menggunakan delimiter
                    $priceParts = explode('.', $request->price[$index]);

                    // Hapus semua titik dari array yang dihasilkan
                    $priceWithoutDots = implode('', $priceParts);

                    $menuDetails[] = [
                        'menu_id' => $menu->id,
                        'size' => $s,
                        'price' => $priceWithoutDots,
                    ];
                }
            }

            // Jika detail menu tidak kosong, maka simpan ke database
            if (!empty($menuDetails)) {
                MenuDetail::insert($menuDetails);
            }

            // Commit operasi database
            DB::commit();

            return response()->json([
                'message' => 'Menu added successfully',
                'success' => true,
            ], 200); // HTTP status code 200 "OK"
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Rollback operasi database jika terjadi exception
            DB::rollBack();

            return response()->json([
                'message' => 'Error occured while adding menu',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500); // HTTP status code 500 "Internal Server Error"
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            // Ambil menu berdasarkan menu_id
            $menu = Menu::findOrFail($request->id);

            // Hapus menu dan detail menu yang berkaitan dengan menu
            $menu->menuDetail()->delete();

            // Hapus menu dari database
            $menu->delete();

            // Commit operasi database
            DB::commit();

            return response()->json([
                'message' => 'Menu and its details deleted successfully',
                'success' => true,
            ], 200); // HTTP status code 200 "OK"
        } catch (\Exception $e) {
            // Rollback operasi database jika terjadi exception
            DB::rollBack();

            return response()->json([
                'message' => 'Error occured while deleting menu',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500); // HTTP status code 500 "Internal Server Error"
        }
    }

    public function addSchedule(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->scheduleDates as $date) {
                // Check if schedule exists for the given date
                $scheduleData = Schedule::where('schedule', date('Y-m-d', strtotime($date)))->first();
                $schedule_id = null;

                if ($scheduleData == null) {
                    // Insert new schedule if it doesn't exist
                    $newSchedule = new Schedule();
                    $newSchedule->schedule = date('Y-m-d', strtotime($date));
                    $newSchedule->save();

                    // Retrieve the newly inserted schedule ID
                    $schedule_id = $newSchedule->id;
                } else {
                    $schedule_id = $scheduleData->id;
                }

                // Check if a MenuSchedule already exists for the given menu_id and schedule_id
                $existingMenuSchedule = MenuSchedule::where('menu_id', $request->menuId)
                    ->where('schedule_id', $schedule_id)
                    ->first();

                if ($existingMenuSchedule) {
                    return response()->json(['error' => 'Menu schedule already exists.'], 400);
                }

                // Create a new MenuSchedule record
                $menuSchedule = new MenuSchedule();
                $menuSchedule->schedule_id = $schedule_id;
                $menuSchedule->menu_id = $request->menuId;
                $menuSchedule->save();
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Schedules added successfully'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();

            // Handle any exceptions that occur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

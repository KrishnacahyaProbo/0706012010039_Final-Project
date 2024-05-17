<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Schedule;
use App\Models\MenuDetail;
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

    public function data()
    {
        try {
            // Mendapatkan Menu berdasarkan vendor_id dengan relasi MenuDetail
            $vendorId = auth()->user()->id;
            $menuItems = Menu::with('menuDetail')->where('vendor_id', $vendorId)->get();

            return DataTables::of($menuItems)->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            // Mendapatkan Menu dengan relasi MenuDetail dan MenuSchedule
            $dataDetail = Menu::with('menuDetail', 'menu_schedule')->where('id', $request->id)->first();

            if ($dataDetail) {
                return response()->json([
                    'message' => 'Detail Menu data found',
                    'status' => true,
                    'data' => $dataDetail,
                ]);
            } else {
                return response()->json([
                    'message' => 'Detail Menu data not found',
                    'success' => false,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occured while fetching detail menu data',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function schedule(Request $request)
    {
        try {
            $dataMenu = Schedule::with(['menus' => function ($query) use ($request) {
                // Menambahkan kondisi whereHas untuk memfilter berdasarkan relasi Menu
                $query->where('vendor_id', $request->id);
            }, 'menus.menuDetail'])
                ->where('schedule', '=', $request->date)
                ->first();

            if ($dataMenu) {
                // Cek aturan vendor berdasarkan confirmation_days
                $vendorRule = UserSetting::where('vendor_id', $request->id)->first();
                $date = strtotime(date("Y-m-d", strtotime("-" . $vendorRule->confirmation_days - 1 . "day", strtotime($dataMenu->schedule))));

                // Jika pada hari-H telah melewati batas waktu berdasarkan aturan vendor, maka tidak bisa melakukan pemesanan
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

    public function store(Request $request)
    {
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            if ($request->id != null) {
                // Jika menu_id tersedia, maka ubah entri yang sudah ada
                $menu = Menu::findOrFail($request->input('id'));
            } else {
                // Jika menu_id tidak tersedia, maka buat entri baru
                $menu = new Menu();
            }

            // Memperbarui entity
            $menu->vendor_id = Auth::user()->id;
            $menu->menu_name = $request->input('menu_name');
            $menu->description = $request->input('description');

            // Cek tipe pedas atau tidak pedas
            if ($request->input('spicy') == 'spicy') {
                $menu->type = 'spicy';
            } else {
                $menu->type = 'no_spicy';
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('menu'), $imageName);
                $menu->image = $imageName;
            }

            // Menyimpan entri
            $menu->save();

            // Hapus detail menu yang sudah ada
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
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Rollback operasi database jika terjadi exception
            DB::rollBack();

            return response()->json([
                'message' => 'Error occured while adding menu',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Mendapatkan Menu berdasarkan menu_id
            $menu = Menu::findOrFail($request->id);

            // Hapus menu dan detail menu terkait
            $menu->menuDetail()->delete();

            // Menghapus entri
            $menu->delete();

            // Commit operasi database
            DB::commit();

            return response()->json([
                'message' => 'Menu and its details deleted successfully',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            // Rollback operasi database jika terjadi exception
            DB::rollBack();

            return response()->json([
                'message' => 'Error occured while deleting menu',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

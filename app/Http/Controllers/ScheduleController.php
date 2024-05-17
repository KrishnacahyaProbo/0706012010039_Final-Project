<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Schedule;
use App\Models\MenuSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function show($name)
    {
        // Menampilkan halaman jadwal dengan relasi Menu dan MenuSchedule
        $data = User::with('menu', 'menu.menu_schedule')->where('name', $name)->first();

        return view('pages.schedules.index', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            foreach ($request->scheduleDates as $date) {
                // Cek ketersediaan Schedule
                $scheduleData = Schedule::where('schedule', date('Y-m-d', strtotime($date)))->first();
                $schedule_id = null;

                if ($scheduleData == null) {
                    // Membuat entri baru pada Schedule
                    $newSchedule = new Schedule();
                    $newSchedule->schedule = date('Y-m-d', strtotime($date));
                    $newSchedule->save();

                    $schedule_id = $newSchedule->id;
                } else {
                    // Jika jadwal sudah ada, maka gunakan id yang sudah ada
                    $schedule_id = $scheduleData->id;
                }

                // Cek ketersediaan MenuSchedule
                $existingMenuSchedule = MenuSchedule::where('menu_id', $request->menuId)->where('schedule_id', $schedule_id)->first();

                if ($existingMenuSchedule) {
                    return response()->json(['error' => 'Menu schedule already exists.'], 400);
                }

                // Membuat entri baru pada MenuSchedule
                $menuSchedule = new MenuSchedule();
                $menuSchedule->schedule_id = $schedule_id;
                $menuSchedule->menu_id = $request->menuId;
                $menuSchedule->save();
            }

            // Commit operasi database
            DB::commit();

            return response()->json(['message' => 'Schedules added successfully'], 200);
        } catch (\Exception $e) {
            // Rollback operasi database jika terjadi exception
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Mengubah tanggal berdasarkan request
            $requestData = $request->only(['id', 'new_start']);
            $formattedDate = Carbon::parse($requestData['new_start'])->format('Y-m-d');

            // Cek ketersediaan Schedule
            $existingSchedule = Schedule::where('schedule', $formattedDate)->first();

            // Cek ketersediaan MenuSchedule
            $menuSchedule = MenuSchedule::where('id', $request->id)->first();
            $menu = Menu::where('id', $menuSchedule->menu_id)->first();

            if ($existingSchedule) {
                // Jika telah terdapat schedule, maka gunakan id yang sudah ada
                if ($menuSchedule) {
                    $menuSchedule->schedule_id = $existingSchedule->id;
                    $menuSchedule->save();
                } else {
                    // Jika belum terdapat jadwal, maka buat jadwal baru
                    $menu->menu_schedule()->attach($existingSchedule->id);
                }
            } else {
                // Membuat entri baru pada Schedule dan hubungkan ke MenuSchedule
                $newSchedule = Schedule::create(['schedule' => $formattedDate]);
                $menuSchedule->schedule_id = $newSchedule->id;
                $menuSchedule->save();
            }

            // Commit operasi database
            DB::commit();

            return response()->json(
                [
                    'message' => 'Schedule updated successfully',
                    'success' => true,
                ],
                200
            );
        } catch (\Exception $e) {
            // Rollback operasi database jika terjadi exception
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(Request $request)
    {
        try {
            // Menghapus MenuSchedule berdasarkan id
            $id = $request->input('id');
            MenuSchedule::where('id', $id)->delete();

            return response()->json([
                'message' => 'Menu schedule deleted successfully',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the menu schedule'], 500);
        }
    }
}

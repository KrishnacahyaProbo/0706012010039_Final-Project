<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\MenuSchedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    //
    public function show($name){
        $data = User::with('menu', 'menu.menu_schedule')
            ->where('name', $name)
            ->first();
        return view('pages.schedules.vendor', compact('data'));
    }
    public function store(Request $request)
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
                $menuSchedule->schedule_id = $schedule_id; // Assign the schedule ID
                $menuSchedule->menu_id = $request->menuId; // Assign the menu ID
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

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestData = $request->only(['id', 'new_start']);
            $formattedDate = Carbon::parse($requestData['new_start'])->format('Y-m-d');

            // Check if schedule exists
            $existingSchedule = Schedule::where('schedule', $formattedDate)->first();

            // Find the MenuSchedule
            $menuSchedule = MenuSchedule::where('id', $request->id)->first();
            $menu = Menu::where('id', $menuSchedule->menu_id)->first();

            // Sync the schedule with the menu
            if ($existingSchedule) {
                // Update the existing MenuSchedule record
                if ($menuSchedule) {
                    $menuSchedule->schedule_id = $existingSchedule->id;
                    $menuSchedule->save();
                } else {
                    // Create a new MenuSchedule record
                    $menu->menu_schedule()->attach($existingSchedule->id);
                }
            } else {
                // Create a new schedule
                $newSchedule = Schedule::create(['schedule' => $formattedDate]);
                // Sync the new schedule with the menu
                $menuSchedule->schedule_id = $newSchedule->id;
                $menuSchedule->save();
            }

            DB::commit();

            return response()->json(
                [
                    'message' => 'Schedule updated successfully',
                    'success' => true,
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(Request $request)
    {
        try {
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

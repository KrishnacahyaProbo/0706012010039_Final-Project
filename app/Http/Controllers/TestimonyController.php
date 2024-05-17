<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestimonyController extends Controller
{
    public function show(string $vendor_id)
    {
        // Mendapatkan Testimony pada vendor yang dipilih
        $testimony = Testimony::where('vendor_id', $vendor_id)->orderBy('created_at', 'desc')->paginate(10);

        // Mengumpulkan data ke array asosiatif
        $data = [
            'testimony' => $testimony,
        ];

        return view('pages.testimony.index', $data);
    }

    public function store(Request $request)
    {
        try {
            // Membuat array data
            $testimony = [
                'transactions_detail_id' => $request->addTestimonyId,
                'vendor_id' => $request->vendorId,
                'customer_id' => Auth::user()->id,
                'rating' => $request->rating,
                'description' => $request->description,
            ];

            if ($request->hasFile('testimony_photo')) {
                $image = $request->file('testimony_photo');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                Storage::disk('public_uploads_testimony_photo')->put($imageName, file_get_contents($image));
                $testimony['testimony_photo'] = $imageName;
            }

            // Membuat entri baru pada Testimony
            Testimony::create($testimony);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }
}

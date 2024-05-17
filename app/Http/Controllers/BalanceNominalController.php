<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BalanceHistory;
use App\Models\BalanceNominal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BalanceNominalController extends Controller
{
    public function index()
    {
        // Mendapatkan UserSetting, BalanceNominal, dan BalanceHistory dari user yang sedang Log In
        $user_setting = UserSetting::where('vendor_id', Auth::user()->id)->first();
        $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();
        $balance_history = BalanceHistory::where('user_id', Auth::user()->id)->get();

        // Mengumpulkan data ke array asosiatif
        $data = [
            'user_setting' => $user_setting,
            'balance' => $balance,
            'balance_history' => $balance_history,
        ];

        return view('pages.credit.index', $data);
    }

    public function topUp(Request $request)
    {
        try {
            // Mendapatkan entri BalanceNominal atau membuat entri baru jika belum ada
            $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

            if ($balance->credit) {
                // Jika sudah pernah Top up, maka akumulasi dengan nominal baru
                $topUp = $request->credit + $balance->credit;
            } else {
                // Jika belum pernah Top up, maka masukkan nominal pertama kali
                $topUp = $request->credit;
            }

            $imageName = "";

            if ($request->hasFile('transaction_proof')) {
                $image = $request->file('transaction_proof');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                Storage::disk('public_uploads_transaction_proof')->put($imageName, file_get_contents($image));
            }

            // Memperbarui entri BalanceNominal
            $payload = ['credit' => $topUp, 'transaction_proof' => $imageName];
            BalanceNominal::where('user_id', Auth::user()->id)->update($payload);

            // Menyimpan riwayat Top up
            $isiUlang = ['user_id' => Auth::user()->id, 'credit' => $request->credit, 'transaction_proof' => $imageName, 'category' => 'customer_income'];
            BalanceHistory::create($isiUlang);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function cashOut(Request $request)
    {
        try {
            // Mendapatkan entri BalanceNominal atau membuat entri baru jika belum ada
            $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();

            if ($balance->credit) {
                // Jika sudah pernah Cash out, maka kurangi dengan nominal baru
                $cashOut = $balance->credit - $request->credit;
            } else {
                // Jika belum pernah Cash out, maka masukkan nominal pertama kali
                $cashOut = $request->credit;
            }

            // Memperbarui entri BalanceNominal
            BalanceNominal::where('user_id', Auth::user()->id)->update(['credit' => $cashOut]);

            // Menyimpan riwayat Cash out
            $payload = ['user_id' => Auth::user()->id, 'credit' => $request->credit, 'category' => 'vendor_outcome'];
            BalanceHistory::create($payload);

            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }
    }

    public function balanceCategory(string $category)
    {
        // Mendapatkan data BalanceHistory berdasarkan kategori yang dipilih
        $auth = Auth::user()->id;

        if ($category === 'all_category') {
            return BalanceHistory::where('user_id', $auth)->orderBy('created_at', 'desc')->get();
        } else {
            return BalanceHistory::where(['category' =>  $category, 'user_id' => $auth])->orderBy('created_at', 'desc')->get();
        }
    }
}

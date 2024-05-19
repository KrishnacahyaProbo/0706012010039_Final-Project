<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\BalanceNominal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['pages.home', 'pages.menu.*', 'pages.customer.*'], function ($view) {
            $authDelivery = null;
            $balance = null;
            $confirmationDays = null;

            if (Auth::check()) {
                $auth = User::where('id', Auth::user()->id)->with('Delivery')->first();
                $confirmationDays = UserSetting::where('vendor_id', Auth::user()->id)->first();
                $balance = BalanceNominal::where('user_id', Auth::user()->id)->first();
                $authDelivery = $auth->Delivery;
            }

            $view->with([
                'authDelivery' => $authDelivery,
                'balance' => $balance,
                'confirmationDays' => $confirmationDays
            ]);
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            if (Schema::hasTable('setting_webs')) {
                $config = DB::table('setting_webs')->where('id', 1)->first();
                View::share('config', $config);
            }
        } catch (\Exception $e) {
            // Database not available; skip sharing configuration
        }
    }
}

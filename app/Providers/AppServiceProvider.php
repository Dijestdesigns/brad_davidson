<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Constant;

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
        Schema::defaultStringLength(191);

        date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

        // Constants.
        $constants = Constant::all();

        if (!empty($constants) && !$constants->isEmpty()) {
            foreach ($constants as $constant) {
                if (empty($constant->key) || empty($constant->value)) {
                    continue;
                }

                define(strtoupper($constant->key), $constant->value);
                define(strtoupper($constant->key) . "_KEY", $constant->key);
                define(strtoupper($constant->key) . "_ID", $constant->id);
            }
        }
    }
}

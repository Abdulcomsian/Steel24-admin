<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;


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
        Blade::directive('customDateFormat', function ($expression) {
            // return "<?php echo \Carbon\Carbon::parse($expression)->format('jS M, Y H:i'); ";
            return "<?php echo Carbon\Carbon::parse($expression)->format('d-m-Y H:i'); ?>";
        });
    }
}


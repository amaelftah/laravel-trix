<?php

namespace Te7aHoudini\LaravelTrix;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelTrixServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-trix.php' => config_path('laravel-trix.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_trix_rich_texts_table.php.stub' => $this->app->databasePath().'/migrations/'.date('Y_m_d_His').'_create_trix_rich_texts_table.php',
            ], 'migrations');
        }

        Route::group([
            'prefix' => 'laravel-trix',
        ], function () {
            Route::post('attachment', config('laravel-trix.store_attachment_action'))->name('laravel-trix.store');
            Route::delete('attachment/{attachment}', config('laravel-trix.destroy_attachment_action'))->name('laravel-trix.destroy');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-trix');

        Blade::directive('trixassets', function () {
            return "<?php echo view('laravel-trix::trixassets')->render(); ?>";
        });

        Blade::directive('trix', function ($expression) {
            return "{!! app('laravel-trix')->make($expression) !!}";
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-trix.php', 'laravel-trix');

        $this->app->bind('laravel-trix', function ($app) {
            return $app->make(LaravelTrix::class);
        });
    }
}

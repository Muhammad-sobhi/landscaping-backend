<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Fix for database migrations string length
        Schema::defaultStringLength(191);

        // 2. Configure Rate Limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 3. Load custom project name from settings table
        try {
            if (Schema::hasTable('settings')) {
                $appName = Setting::where('key', 'project_name')->value('value');
                
                if ($appName) {
                    Config::set('app.name', $appName);
                }
            }
        } catch (\Exception $e) {
            // Log the error or fail silently so 'php artisan migrate' can still run
            report($e);
        }
    }
}
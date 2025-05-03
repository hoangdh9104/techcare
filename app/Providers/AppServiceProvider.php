<?php

namespace App\Providers;


use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use App\Models\PusherSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;

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

        Paginator::useBootstrap();

        /* set time zone */
        $generalSetting = GeneralSetting::first();
        $logoSetting = LogoSetting::first();
        $pusherSetting = PusherSetting::first();


        // Cấu hình broadcasting 

        Config::set('broadcasting.connections.pusher.key', $pusherSetting->pusher_key);
        Config::set('broadcasting.connections.pusher.secret', $pusherSetting->pusher_secret);
        Config::set('broadcasting.connections.pusher.app_id', $pusherSetting->pusher_app_id);
        Config::set('broadcasting.connections.pusher.options.host', "api-{$pusherSetting->pusher_cluster}.pusher.com");

        // dd(Config::get('broadcasting'));

        if ($generalSetting) {
            Config::set('app.timezone', $generalSetting->time_zone);

            /** Share variable at all view */
            View::composer('*', function ($view) use ($generalSetting, $logoSetting, $pusherSetting) {
                $view->with(['settings' => $generalSetting, 'logoSetting' => $logoSetting, 'pusherSetting' => $pusherSetting]);
            });
        } else {
            // Đặt timezone mặc định nếu không có bản ghi trong bảng general_settings
            Config::set('app.timezone', config('app.timezone'));
        }
    }
}

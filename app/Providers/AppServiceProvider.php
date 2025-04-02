<?php

namespace App\Providers;


use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrap();

        /* set time zone */
        $generalSetting = GeneralSetting::first();
        $logoSetting = LogoSetting::first();


        if ($generalSetting) {
            Config::set('app.timezone', $generalSetting->time_zone);

            /** Share variable at all view */
            View::composer('*', function ($view) use ($generalSetting, $logoSetting) {
                $view->with(['settings' => $generalSetting, 'logoSetting' => $logoSetting ]);
            });
        } else {
            // Đặt timezone mặc định nếu không có bản ghi trong bảng general_settings
            Config::set('app.timezone', config('app.timezone'));
        }


        // $generalSetting = GeneralSetting::first(); // Giả sử bạn dùng model GeneralSetting
        // if ($generalSetting) {
        //     Config::set('app.timezone', $generalSetting->time_zone);
        // } else {
        //     // Xử lý khi không tìm thấy dữ liệu, ví dụ: đặt timezone mặc định
        //     Config::set('app.timezone', 'UTC');
        // }
    }
}

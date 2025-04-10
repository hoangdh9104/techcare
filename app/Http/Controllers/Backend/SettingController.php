<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ImageUploadTrait;
   public function index(){
    $generalSettings = GeneralSetting::first();
    $logoSetting = LogoSetting::first();
    return view('admin.setting.index', compact('generalSettings','logoSetting'));
   }
   public function generalSettingUpdate(Request $request){
    $request->validate([
        'site_name'=>['required','max:200'],
        'layout'=>['required','max:200'],
        'contact_email'=>['required','max:200'],
        'currency_name'=>['required','max:200'],
        'currency_icon'=>['required','max:200'],
        'time_zone'=>['required','max:200'],
    ]);
    GeneralSetting::updateOrCreate(
        ['id'=> 1],
    [
        'site_name'=>$request->site_name,
        'layout'=>$request->layout,
        'contact_email'=>$request->contact_email,
        'currency_name'=>$request->currency_name,
        'currency_icon'=>$request->currency_icon,
        'time_zone'=>$request->time_zone,
    ]);
        toastr('Update successfully!','success','Success');
        return redirect()->back();
   }
   public function logoSettingUpdate(Request $request)
   {
        $request->validate([

            'logo' => ['image','max:3000'],
            'favicon' => ['image','max:3000'],
        ]);

        $logoPath = $this->updateImage($request,'logo','uploads',$request->old_logo);
        $favicon = $this->updateImage($request,'favicon','uploads',$request->old_favicon);

        LogoSetting::updateOrCreate(
            ['id' => 1],
            [
                'logo' => !empty($logoPath) ? $logoPath : $request->old_logo,
                'favicon' => !empty($favicon) ? $favicon : $request->old_favicon,

            ]

            );
            toastr('Update successfully!', 'success', 'success');

            return redirect()->back();

   }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorShopProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email','vendor@gmail.com')->first();
        $vendor = new Vendor();
        $vendor->banner ='upload/1343.jpg';
        $vendor->shop_name ='Expample : Vendor Shop';
        $vendor->phone ='Example :0866910764';
        $vendor->email="Example :vendor@gmail.com";
        $vendor->address="Example :VietNam";
        $vendor->description ='Example :FPT POLYTECHNIC';
        $vendor->user_id =$user->id;
        $vendor->status =1;
        $vendor->save();
        $vendor->status =1;
    }
}

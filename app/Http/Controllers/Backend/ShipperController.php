<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShipperController extends Controller
{
    public function dashboard() {
        
        return view(' shipper.dashboard');
    }
}

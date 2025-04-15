<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShipperOrderController extends Controller
{
    public function index()
    {
        return view('shipper.orders.index');
    }
}

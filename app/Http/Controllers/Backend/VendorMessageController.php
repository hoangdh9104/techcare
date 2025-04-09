<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorMessageController extends Controller
{
    function index() : View {
        return view('vendor.messenger.index');
    }
}

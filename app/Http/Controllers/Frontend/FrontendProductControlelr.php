<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendProductControlelr extends Controller
{
    // show product detail page
    public function showProduct(string $slug)
    {
        return view('frontend.pages.product-detail');
    }
}

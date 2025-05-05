<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ShippedOrderDataTable;
use App\Http\Controllers\Controller;
use App\Traits\TrelloTrait;
use Illuminate\Http\Request;

class ShipperOrderController extends Controller
{
    use TrelloTrait;
    public function index(ShippedOrderDataTable $dataTable)
    {
        return $dataTable->render('shipper.orders.index'); 
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Services\ShipmentService;

class ReturnPricesController extends Controller
{
    public function returnPrice(Request $request)
    {
        // this inject funtion form services 
        return   (new ShipmentService())->getPrice($request);
    
    }
    
    public function returnCarrierPrice(Request $request)
    {

        // this inject funtion form services 
        return   (new ShipmentService())->getCarrierPrice($request);
    
    }
}

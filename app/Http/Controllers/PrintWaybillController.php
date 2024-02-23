<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Shipment;
use App\Models\Vehicle;
class PrintWaybillController extends Controller
{
    //

    public function generateInvoice(){
        $shipment = Shipment::findOrFail(7);
        $vehicle = Vehicle::findOrFail($shipment->shipmentDeliveryDetail->vehicle_id);
       
        $data = [
            'shipment' => $shipment,
            'vehicle' => $vehicle,
           
        ];
        return view('weybill.layouts', $data);
        $html = \View::make('weybill.layouts', $data)->render();
        
        $pdf = SnappyPdf::loadHTML($html)->setOption('enable-local-file-access', true);

      
        
        // You can save the PDF to a file or return it as a response
        // Save to file
        // $pdf->save('pdf/test.pdf');
        
        // Return as response
        // return $pdf->download('test.pdf');
        return $pdf->stream();

        // return response()->file($pdfFilePath);
        

    
    }
}

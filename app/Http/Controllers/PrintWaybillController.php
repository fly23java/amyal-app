<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Shipment;
use App\Models\Vehicle;
class PrintWaybillController extends Controller
{
    //

    public function generateInvoice($id){
        $shipment = Shipment::findOrFail($id);
        $vehicle = Vehicle::findOrFail($shipment->shipmentDeliveryDetail->vehicle_id);
        $top = mt_rand(60, 70);
        $right = mt_rand(30, 60);
        $data = [
            'shipment' => $shipment,
            'vehicle' => $vehicle,
            'top' => $top,
            'right' => $right,
           
        ];
        // return view('weybill.layouts', $data);
        $html = \View::make('weybill.layouts', $data)->render();
        
        $pdf = SnappyPdf::loadHTML($html)->setOption('enable-local-file-access', true);

      
        
        // You can save the PDF to a file or return it as a response
        // Save to file
        // $pdf->save('pdf/test.pdf');
        
        // Return as response
        return $pdf->download($shipment->serial_number.'.pdf');
        // return $pdf->stream();

        // return response()->file($pdfFilePath);
        

    
    }
}

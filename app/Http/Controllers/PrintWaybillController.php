<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Shipment;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\File;

use  App\Mail\SendWeyBill;
use Illuminate\Support\Facades\Mail;

class PrintWaybillController extends Controller
{
    //

    /**
     * Generate and download a PDF invoice for a shipment.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice($id)
    {
        $shipment = Shipment::findOrFail($id);
        $pdfFilePath = 'pdf/' . $shipment->serial_number . '.pdf';

        // Check if the PDF file already exists
        if (File::exists($pdfFilePath)) {
            // If the file exists, return it as a download response
            return response()->download($pdfFilePath);
        }

        $vehicle = Vehicle::findOrFail($shipment->shipmentDeliveryDetail->vehicle_id);

      
       
        $top = mt_rand(60, 80);
        $right = mt_rand(30, 60);

        $data = [
            'shipment' => $shipment,
            'vehicle' => $vehicle,
            'top' => $top,
            'right' => $right,
        ];
        $html = \View::make('weybill.show', $data)->render();

        // $pdf = SnappyPdf::loadHTML($html)->setOption('enable-local-file-access', true);



        // Save the PDF to a file
        $pdf->save($pdfFilePath);

        if ($shipment->id) {
            $basicAdminAccount = Account::where('type', 'admin')->first();

            $activeAdminUsers = User::where('type', 'admin')
                                    ->where('status', 'active')
                                    ->get();

            // $shipmentData = $shipmentService->prepareShipmentData($shipment->id);
            $pdfFileFullPath = public_path('pdf/' . $pdfFilePath);
            Mail::to('ibrahim.m@amyal.sa')
                ->cc($activeAdminUsers->pluck('email')->toArray())
                ->queue(new SendWeyBill($shipment,$pdfFileFullPath,$vehicle));
        }


        // Return the generated PDF as a download response
        return $pdf->download($shipment->serial_number . '.pdf');
    }
}

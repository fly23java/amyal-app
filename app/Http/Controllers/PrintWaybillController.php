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
        $vehicle = Vehicle::findOrFail($shipment->shipmentDeliveryDetail->vehicle_id);
        // Define the absolute path to the directory where you want to save the PDF file
        $pdfDirectory = public_path('pdf');
        
        // Create the directory if it doesn't exist
        if (!File::exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0755, true);
        }

        // Generate a unique filename for the PDF file (e.g., using the shipment serial number)
        $pdfFileName = $shipment->serial_number . '.pdf';

        // Specify the absolute path to the PDF file
        $pdfFilePath = $pdfDirectory . '/' . $pdfFileName;

        // Check if the PDF file already exists
        if (!File::exists($pdfFilePath)) {
            // Load HTML content into SnappyPdf and generate PDF
           
            $top = mt_rand(60, 80);
            $right = mt_rand(30, 60);
            $data = [
                'shipment' => $shipment,
                'vehicle' => $vehicle,
                'top' => $top,
                'right' => $right,
            ];
            $html = view('weybill.show', $data)->render();
            $pdf = SnappyPdf::loadHTML($html)->setOption('enable-local-file-access', true);

            // Save the PDF to the specified file path
            $pdf->save($pdfFilePath);
        }

        // Check if the PDF file was saved successfully
        if (!File::exists($pdfFilePath)) {
            // Handle error: PDF file was not saved
            return response()->json(['error' => 'Failed to save the PDF file.'], 500);
        }

        // Send the email with the PDF invoice attached
        $basicAdminAccount = Account::where('type', 'admin')->first();
        $activeAdminUsers = User::where('type', 'admin')->where('status', 'active')->get();
        $pdfFileFullPath = $pdfFilePath;

        Mail::to('ibrahim.m@amyal.sa')
            ->cc($activeAdminUsers->pluck('email')->toArray())
            ->queue(new SendWeyBill($shipment, $pdfFileFullPath, $vehicle));

        // Return the generated PDF as a download response
        return response()->download($pdfFilePath);
    }
}

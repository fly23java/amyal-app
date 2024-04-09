<?php

namespace App\Http\Controllers;


use App\Models\Shipment;

use App\Models\Status;
use App\Models\StatusChange;

use Illuminate\Http\Request;

class UpdateSelectedFieldsController extends Controller
{
    public function updateSelectedFields(Request $request)
    {
        // dd($request->all());
        // // Validate the form data
        // $request->validate([
        //     'status' => 'required', // Add any other validation rules as needed
        //     'selectedRows' => 'required', // Ensure selectedRows field is present
        // ]);
        // dd($request->all());
        // // Extract selected rows' IDs from the request
        // $selectedRows = explode(',', $request->selectedRows);

        // // Update the status for each selected row
        // foreach ($selectedRows as $rowId) {
        //     $shipment = Shipment::find($rowId);
        //     if ($shipment) {
        //         // Update the status
        //         $shipment->status = $request->status;
        //         $shipment->save();
        //     }
        // }

        // // Optionally, you can redirect or return a response here
        // return redirect()->back()->with('success', 'Shipment status updated successfully.');
    }
}

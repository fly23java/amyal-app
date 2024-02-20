<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentDeliveryDetailsFormRequest;
use App\Models\Shipment;
use App\Models\ShipmentDeliveryDetail;
use App\Models\Vehicle;
use Exception;

class ShipmentDeliveryDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the shipment delivery details.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $shipmentDeliveryDetails = ShipmentDeliveryDetail::with('shipment','vehicle')->paginate(25);

        return view('shipment_delivery_details.index', compact('shipmentDeliveryDetails'));
    }

    /**
     * Show the form for creating a new shipment delivery detail.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Shipments = Shipment::pluck('serial_number','id')->all();
$Vehicles = Vehicle::pluck('owner_name','id')->all();
        
        return view('shipment_delivery_details.create', compact('Shipments','Vehicles'));
    }

    /**
     * Store a new shipment delivery detail in the storage.
     *
     * @param App\Http\Requests\ShipmentDeliveryDetailsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(ShipmentDeliveryDetailsFormRequest $request)
    {
        
        $data = $request->getData();
        
        ShipmentDeliveryDetail::create($data);

        return redirect()->route('shipment_delivery_details.shipment_delivery_detail.index')
            ->with('success_message', trans('shipment_delivery_details.model_was_added'));
    }

    /**
     * Display the specified shipment delivery detail.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $shipmentDeliveryDetail = ShipmentDeliveryDetail::with('shipment','vehicle')->findOrFail($id);

        return view('shipment_delivery_details.show', compact('shipmentDeliveryDetail'));
    }

    /**
     * Show the form for editing the specified shipment delivery detail.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $shipmentDeliveryDetail = ShipmentDeliveryDetail::findOrFail($id);
        $Shipments = Shipment::pluck('serial_number','id')->all();
        $Vehicles = Vehicle::pluck('owner_name','id')->all();

        return view('shipment_delivery_details.edit', compact('shipmentDeliveryDetail','Shipments','Vehicles'));
    }

    /**
     * Update the specified shipment delivery detail in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\ShipmentDeliveryDetailsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, ShipmentDeliveryDetailsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $shipmentDeliveryDetail = ShipmentDeliveryDetail::findOrFail($id);
        $shipmentDeliveryDetail->update($data);

        return redirect()->route('shipment_delivery_details.shipment_delivery_detail.index')
            ->with('success_message', trans('shipment_delivery_details.model_was_updated'));  
    }

    /**
     * Remove the specified shipment delivery detail from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $shipmentDeliveryDetail = ShipmentDeliveryDetail::findOrFail($id);
            $shipmentDeliveryDetail->delete();

            return redirect()->route('shipment_delivery_details.shipment_delivery_detail.index')
                ->with('success_message', trans('shipment_delivery_details.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('shipment_delivery_details.unexpected_error')]);
        }
    }



}

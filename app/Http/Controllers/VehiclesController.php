<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehiclesFormRequest;
use App\Models\Account;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\VehicleType;
use Exception;

use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the vehicles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $vehicles = Vehicle::with('type', 'account', 'driver')->orderBy('id', 'DESC')->get();


        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
       
        $Accounts = Account::where(function ($query) {
            $query->where('type', 'business_carrier');
                  
        })
        ->pluck('name_arabic', 'id');
        
        return view('vehicles.create', compact('VehicleTypes','Accounts'));
    }

    /**
     * Store a new vehicle in the storage.
     *
     * @param App\Http\Requests\VehiclesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(VehiclesFormRequest $request)
    {
        
          // Start a database transaction
            DB::beginTransaction();

            try {
                // Insert vehicle data
                $vehicleData = $request->only([
                    'owner_name',
                    'sequence_number',
                    'plate',
                    'right_letter',
                    'middle_letter',
                    'left_letter',
                    'plate_type',
                    'vehicle_type_id',
                   
                    'account_id',
                   
                ]);

                $vehicle = Vehicle::create($vehicleData);

                // Insert driver data
                $driverData = $request->only([
                    'phone',
                    'identity_number',
                    'account_id',
                    
                ]);

                $driverData['name_arabic'] = $request->input('driver_name_arabic');

                $driverData['vehicle_id'] = $vehicle->id; // Attach vehicle ID to driver data

                $driver = Driver::create($driverData);

                // Commit the transaction if everything is successful
                DB::commit();

                return redirect()->route('vehicles.vehicle.index')
                    ->with('success_message', trans('vehicles.model_was_added'));
            } catch (\Exception $e) {
                // If an error occurs, rollback the transaction
                DB::rollback();

                // Handle the error as needed
                return redirect()->back()->with('error_message', $e->getMessage());
            }
    }

    /**
     * Display the specified vehicle.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $vehicle = Vehicle::with('type','account')->findOrFail($id);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
        $Accounts = Account::where(function ($query) {
            $query->where('type', 'business_carrier');
                  
        })
        ->pluck('name_arabic', 'id');

        return view('vehicles.edit', compact('vehicle','VehicleTypes','Accounts'));
    }

    /**
     * Update the specified vehicle in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\VehiclesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, VehiclesFormRequest $request)
    {
        
        
     // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the existing vehicle data
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->update($request->only([
                'owner_name',
                'sequence_number',
                'plate',
                'right_letter',
                'middle_letter',
                'left_letter',
                'plate_type',
                'vehicle_type_id',
                'account_id',
            ]));

            // Update or create driver data
            $driverData = $request->only([
                'phone',
                'identity_number',
                'account_id',
            ]);

            // Attach the vehicle ID and derivert name arabic to driver data
            $driverData['vehicle_id'] = $vehicle->id;
            $driverData['name_arabic'] = $request->input('driver_name_arabic');
            $driver = Driver::updateOrCreate(['vehicle_id' => $vehicle->id], $driverData);

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('vehicles.vehicle.index')
                ->with('success_message', trans('vehicles.model_was_updated'));
        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollback();

            // Handle the error as needed
            return redirect()->back()->with('error_message', $e->getMessage());
        }


    }
    
 
    /**
     * Remove the specified vehicle from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            // Find the vehicle by ID
            $vehicle = Vehicle::findOrFail($id);
    
            // Check if the driver exists and delete it
            if ($vehicle->driver) {
                if (optional($vehicle->driver)->delete()) {
                    return redirect()->route('vehicles.vehicle.index')
                        ->with('success_message', trans('vehicles.model_and_driver_deleted'));
                } else {
                    return redirect()->route('vehicles.vehicle.index')
                        ->with('error_message', trans('vehicles.driver_not_deleted'));
                }
            }
    
            // If there is no driver or the driver deletion was successful, delete the vehicle
            if ($vehicle->delete()) {
                return redirect()->route('vehicles.vehicle.index')
                    ->with('success_message', trans('vehicles.model_was_deleted'));
            } else {
                return redirect()->route('vehicles.vehicle.index')
                    ->with('error_message', trans('vehicles.model_not_deleted'));
            }
        } catch (Exception $exception) {
            // Handle any unexpected error
            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('vehicles.unexpected_error')]);
        }
    }
    



}

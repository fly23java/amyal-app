<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriversFormRequest;
use App\Models\Account;
use App\Models\Driver;
use App\Models\Vehicle;
use Exception;

class DriversController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the drivers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $drivers = Driver::with('account','vehicle')->orderBy('id', 'DESC')->get();

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new driver.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Accounts = Account::pluck('name_arabic','id')->all();
$Vehicles = Vehicle::pluck('owner_name','id')->all();
        
        return view('drivers.create', compact('Accounts','Vehicles'));
    }

    /**
     * Store a new driver in the storage.
     *
     * @param App\Http\Requests\DriversFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(DriversFormRequest $request)
    {
        
        $data = $request->getData();
        
        // Driver::create($data);

        Driver::create([
            'name_arabic'=> $data['name_arabic'],
            'name_english' => $data['name_english'],
            'email'  => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'identity_number' => $data['name_arabic'],
            'date_of_birth_gregorian' => date("Y-m-d H:i:s", strtotime($data['date_of_birth_gregorian'])),

            'account_id' => $data['account_id'],
            'vehicle_id'=> $data['vehicle_id']
           
        ]);

        return redirect()->route('drivers.driver.index')
            ->with('success_message', trans('drivers.model_was_added'));
    }

    /**
     * Display the specified driver.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $driver = Driver::with('account','vehicle')->findOrFail($id);

        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        $Accounts = Account::pluck('name_arabic','id')->all();
$Vehicles = Vehicle::pluck('owner_name','id')->all();

        return view('drivers.edit', compact('driver','Accounts','Vehicles'));
    }

    /**
     * Update the specified driver in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\DriversFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, DriversFormRequest $request)
    {
        
        $data = $request->getData();
        
        $driver = Driver::findOrFail($id);
        $driver->update($data);

        return redirect()->route('drivers.driver.index')
            ->with('success_message', trans('drivers.model_was_updated'));  
    }

    /**
     * Remove the specified driver from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $driver = Driver::findOrFail($id);
            $driver->delete();

            return redirect()->route('drivers.driver.index')
                ->with('success_message', trans('drivers.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('drivers.unexpected_error')]);
        }
    }



}

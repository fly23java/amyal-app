<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\GoodsType;
use Illuminate\Http\Request;
use Exception;

class VehicleTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the vehicle types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $vehicleTypes = VehicleType::orderBy('id', 'DESC')->get();

        return view('vehicle_types.index', compact('vehicleTypes'));
    }

    /**
     * Show the form for creating a new vehicle type.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        $vehicleTypes = VehicleType::all();
        $goodsTypes = GoodsType::all();

        return view('vehicle_types.create', compact('vehicleTypes', 'goodsTypes'));
    }

    /**
     * Store a new vehicle type in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $data = $this->getData($request);
        $goodsTypes = $request->input('goods_types');
        // dd($goodsTypes);
        $vehicleType = VehicleType::create($data);
        $vehicleType->goodsTypes()->sync($goodsTypes);
        return redirect()->route('vehicle_types.vehicle_type.index')
            ->with('success_message', trans('vehicle_types.model_was_added'));
    }

    /**
     * Display the specified vehicle type.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        return view('vehicle_types.show', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified vehicle type.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $goodsTypes = GoodsType::all();

        return view('vehicle_types.edit', compact('vehicleType' , 'goodsTypes'));
    }

    /**
     * Update the specified vehicle type in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $data = $this->getData($request);
        
        $vehicleType = VehicleType::findOrFail($id);
        $goodsTypes = $request->input('goods_types');

        
        $vehicleType->update($data);
        $vehicleType->goodsTypes()->sync($goodsTypes);
        return redirect()->route('vehicle_types.vehicle_type.index')
            ->with('success_message', trans('vehicle_types.model_was_updated'));  
    }

    /**
     * Remove the specified vehicle type from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $vehicleType = VehicleType::findOrFail($id);
            $vehicleType->delete();

            return redirect()->route('vehicle_types.vehicle_type.index')
                ->with('success_message', trans('vehicle_types.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('vehicle_types.unexpected_error')]);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'name_arabic' => 'required|string|min:1|nullable',
            'name_english' => 'required|string|min:1|nullable', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}

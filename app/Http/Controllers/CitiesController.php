<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitiesFormRequest;
use App\Models\City;
use App\Models\Region;
use Exception;

class CitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the cities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cities = City::with('region')->orderBy('id', 'DESC')->get();

        return view('cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new city.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Regions = Region::pluck('name_arabic','id')->all();
        
        return view('cities.create', compact('Regions'));
    }

    /**
     * Store a new city in the storage.
     *
     * @param App\Http\Requests\CitiesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(CitiesFormRequest $request)
    {
        
        $data = $request->getData();
        
        City::create($data);

        return redirect()->route('cities.city.index')
            ->with('success_message', trans('cities.model_was_added'));
    }

    /**
     * Display the specified city.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $city = City::with('region')->findOrFail($id);

        return view('cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified city.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $city = City::findOrFail($id);
        $Regions = Region::pluck('name_arabic','id')->all();

        return view('cities.edit', compact('city','Regions'));
    }

    /**
     * Update the specified city in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\CitiesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, CitiesFormRequest $request)
    {
        
        $data = $request->getData();
        
        $city = City::findOrFail($id);
        $city->update($data);

        return redirect()->route('cities.city.index')
            ->with('success_message', trans('cities.model_was_updated'));  
    }

    /**
     * Remove the specified city from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $city = City::findOrFail($id);
            $city->delete();

            return redirect()->route('cities.city.index')
                ->with('success_message', trans('cities.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('cities.unexpected_error')]);
        }
    }



}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountriesFormRequest;
use App\Models\Country;
use Exception;

class CountriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the countries.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $countries = Country::orderBy('id', 'DESC')->get();

        return view('countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new country.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('countries.create');
    }

    /**
     * Store a new country in the storage.
     *
     * @param App\Http\Requests\CountriesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(CountriesFormRequest $request)
    {

      
        
        $data = $request->getData();
        
        Country::create($data);

        return redirect()->route('countries.country.index')
            ->with('success_message', trans('countries.model_was_added'));
    }

    /**
     * Display the specified country.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $country = Country::findOrFail($id);

        return view('countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified country.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $country = Country::findOrFail($id);
        

        return view('countries.edit', compact('country'));
    }

    /**
     * Update the specified country in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\CountriesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, CountriesFormRequest $request)
    {
        
        $data = $request->getData();
        
        $country = Country::findOrFail($id);
        $country->update($data);

        return redirect()->route('countries.country.index')
            ->with('success_message', trans('countries.model_was_updated'));  
    }

    /**
     * Remove the specified country from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {

        // dd($id);
        try {
            $country = Country::findOrFail($id);
            $country->delete();

            return redirect()->route('countries.country.index')
                ->with('success_message', trans('countries.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('countries.unexpected_error')]);
        }
    }



}

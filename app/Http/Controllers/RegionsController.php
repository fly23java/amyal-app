<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegionsFormRequest;
use App\Models\Country;
use App\Models\Region;
use Exception;

class RegionsController extends Controller
{

    /**
     * Display a listing of the regions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::with('country')->orderBy('id', 'DESC')->get();

        return view('regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new region.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Countries = Country::pluck('name_arabic','id')->all();
        
        return view('regions.create', compact('Countries'));
    }

    /**
     * Store a new region in the storage.
     *
     * @param App\Http\Requests\RegionsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(RegionsFormRequest $request)
    {
        
        $data = $request->getData();
        
        Region::create($data);

        return redirect()->route('regions.region.create')
            ->with('success_message', trans('regions.model_was_added'));
    }

    /**
     * Display the specified region.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $region = Region::with('country')->findOrFail($id);

        return view('regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified region.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        $Countries = Country::pluck('name_arabic','id')->all();

        return view('regions.edit', compact('region','Countries'));
    }

    /**
     * Update the specified region in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\RegionsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, RegionsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $region = Region::findOrFail($id);
        $region->update($data);

        return redirect()->route('regions.region.index')
            ->with('success_message', trans('regions.model_was_updated'));  
    }

    /**
     * Remove the specified region from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $region = Region::findOrFail($id);
            $region->delete();

            return redirect()->route('regions.region.index')
                ->with('success_message', trans('regions.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('regions.unexpected_error')]);
        }
    }



}

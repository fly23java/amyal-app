<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitsFormRequest;
use App\Models\Unit;
use Exception;

class UnitsController extends Controller
{

    /**
     * Display a listing of the units.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $units = Unit::orderBy('id', 'DESC')->get();

        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('units.create');
    }

    /**
     * Store a new unit in the storage.
     *
     * @param App\Http\Requests\UnitsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(UnitsFormRequest $request)
    {
        
        $data = $request->getData();
        
        Unit::create($data);

        return redirect()->route('units.unit.index')
            ->with('success_message', trans('units.model_was_added'));
    }

    /**
     * Display the specified unit.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $unit = Unit::findOrFail($id);

        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        

        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified unit in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\UnitsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, UnitsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $unit = Unit::findOrFail($id);
        $unit->update($data);

        return redirect()->route('units.unit.index')
            ->with('success_message', trans('units.model_was_updated'));  
    }

    /**
     * Remove the specified unit from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();

            return redirect()->route('units.unit.index')
                ->with('success_message', trans('units.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('units.unexpected_error')]);
        }
    }



}

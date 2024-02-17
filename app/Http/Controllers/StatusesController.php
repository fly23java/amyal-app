<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusesFormRequest;
use App\Models\Status;
use Exception;

class StatusesController extends Controller
{

    /**
     * Display a listing of the statuses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $statuses = Status::orderBy('id', 'DESC')->get();

        return view('statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new status.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('statuses.create');
    }

    /**
     * Store a new status in the storage.
     *
     * @param App\Http\Requests\StatusesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(StatusesFormRequest $request)
    {
        
        $data = $request->getData();
        
        Status::create($data);

        return redirect()->route('statuses.status.index')
            ->with('success_message', trans('statuses.model_was_added'));
    }

    /**
     * Display the specified status.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $status = Status::findOrFail($id);

        return view('statuses.show', compact('status'));
    }

    /**
     * Show the form for editing the specified status.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $status = Status::findOrFail($id);
        

        return view('statuses.edit', compact('status'));
    }

    /**
     * Update the specified status in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\StatusesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, StatusesFormRequest $request)
    {
        
        $data = $request->getData();
        
        $status = Status::findOrFail($id);
        $status->update($data);

        return redirect()->route('statuses.status.index')
            ->with('success_message', trans('statuses.model_was_updated'));  
    }

    /**
     * Remove the specified status from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $status = Status::findOrFail($id);
            $status->delete();

            return redirect()->route('statuses.status.index')
                ->with('success_message', trans('statuses.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('statuses.unexpected_error')]);
        }
    }



}

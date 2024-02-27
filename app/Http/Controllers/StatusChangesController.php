<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusChangesFormRequest;
use App\Models\Shipment;
use App\Models\Status;
use App\Models\StatusChange;
use App\Models\User;
use Exception;

class StatusChangesController extends Controller
{

    /**
     * Display a listing of the status changes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $statusChanges = StatusChange::with('shipment','status','user')->paginate(25);

        return view('status_changes.index', compact('statusChanges'));
    }

    /**
     * Show the form for creating a new status change.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Shipments = Shipment::pluck('serial_number','id')->all();
$Statuses = Status::pluck('name_arabic','id')->all();
$Users = User::pluck('name','id')->all();
        
        return view('status_changes.create', compact('Shipments','Statuses','Users'));
    }

    /**
     * Store a new status change in the storage.
     *
     * @param App\Http\Requests\StatusChangesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(StatusChangesFormRequest $request)
    {
        
        $data = $request->getData();
        
        StatusChange::create($data);

        return redirect()->route('status_changes.status_change.index')
            ->with('success_message', trans('status_changes.model_was_added'));
    }

    /**
     * Display the specified status change.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $statusChange = StatusChange::with('shipment','status','user')->findOrFail($id);

        return view('status_changes.show', compact('statusChange'));
    }

    /**
     * Show the form for editing the specified status change.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $statusChange = StatusChange::findOrFail($id);
        $Shipments = Shipment::pluck('serial_number','id')->all();
$Statuses = Status::pluck('name_arabic','id')->all();
$Users = User::pluck('name','id')->all();

        return view('status_changes.edit', compact('statusChange','Shipments','Statuses','Users'));
    }

    /**
     * Update the specified status change in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\StatusChangesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, StatusChangesFormRequest $request)
    {
        
        $data = $request->getData();
        
        $statusChange = StatusChange::findOrFail($id);
        $statusChange->update($data);

        return redirect()->route('status_changes.status_change.index')
            ->with('success_message', trans('status_changes.model_was_updated'));  
    }

    /**
     * Remove the specified status change from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $statusChange = StatusChange::findOrFail($id);
            $statusChange->delete();

            return redirect()->route('status_changes.status_change.index')
                ->with('success_message', trans('status_changes.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('status_changes.unexpected_error')]);
        }
    }



}

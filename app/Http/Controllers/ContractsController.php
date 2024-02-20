<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractsFormRequest;
use App\Models\Contract;
use App\Models\User;
use Exception;

class ContractsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the contracts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contracts = Contract::with('user','user')->orderBy('id', 'DESC')->get();

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new contract.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Users = User::pluck('name','id')->all();
        
        return view('contracts.create', compact('Users','Users'));
    }

    /**
     * Store a new contract in the storage.
     *
     * @param App\Http\Requests\ContractsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(ContractsFormRequest $request)
    {
        
        $data = $request->getData();
        
        Contract::create($data);

        return redirect()->route('contracts.contract.index')
            ->with('success_message', trans('contracts.model_was_added'));
    }

    /**
     * Display the specified contract.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $contract = Contract::with('user','user')->findOrFail($id);

        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified contract.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $Users = User::pluck('name','id')->all();

        return view('contracts.edit', compact('contract','Users','Users'));
    }

    /**
     * Update the specified contract in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\ContractsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, ContractsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $contract = Contract::findOrFail($id);
        $contract->update($data);

        return redirect()->route('contracts.contract.index')
            ->with('success_message', trans('contracts.model_was_updated'));  
    }

    /**
     * Remove the specified contract from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $contract->delete();

            return redirect()->route('contracts.contract.index')
                ->with('success_message', trans('contracts.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('contracts.unexpected_error')]);
        }
    }



}

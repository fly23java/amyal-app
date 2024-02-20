<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractDetailsFormRequest;
use App\Models\City;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\Goods;
use App\Models\VehicleType;
use Exception;

class ContractDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the contract details.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contractDetails = ContractDetail::with('contract','vehicletype','goods','city','city')->orderBy('id', 'DESC')->get();

        return view('contract_details.index', compact('contractDetails'));
    }

    /**
     * Show the form for creating a new contract detail.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Contracts = Contract::pluck('description','id')->all();
$VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
$Goods = Goods::pluck('name_arabic','id')->all();
$Cities = City::pluck('name_arabic','id')->all();
        
        return view('contract_details.create', compact('Contracts','VehicleTypes','Goods','Cities','Cities'));
    }

    /**
     * Store a new contract detail in the storage.
     *
     * @param App\Http\Requests\ContractDetailsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(ContractDetailsFormRequest $request)
    {
        
        $data = $request->getData();
        
        ContractDetail::create($data);

        return redirect()->route('contract_details.contract_detail.index')
            ->with('success_message', trans('contract_details.model_was_added'));
    }

    /**
     * Display the specified contract detail.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $contractDetail = ContractDetail::with('contract','vehicletype','goods','city','city')->findOrFail($id);

        return view('contract_details.show', compact('contractDetail'));
    }

    /**
     * Show the form for editing the specified contract detail.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $contractDetail = ContractDetail::findOrFail($id);
        $Contracts = Contract::pluck('description','id')->all();
$VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
$Goods = Goods::pluck('name_arabic','id')->all();
$Cities = City::pluck('name_arabic','id')->all();

        return view('contract_details.edit', compact('contractDetail','Contracts','VehicleTypes','Goods','Cities','Cities'));
    }

    /**
     * Update the specified contract detail in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\ContractDetailsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, ContractDetailsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $contractDetail = ContractDetail::findOrFail($id);
        $contractDetail->update($data);

        return redirect()->route('contract_details.contract_detail.index')
            ->with('success_message', trans('contract_details.model_was_updated'));  
    }

    /**
     * Remove the specified contract detail from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $contractDetail = ContractDetail::findOrFail($id);
            $contractDetail->delete();

            return redirect()->route('contract_details.contract_detail.index')
                ->with('success_message', trans('contract_details.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('contract_details.unexpected_error')]);
        }
    }



}

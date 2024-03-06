<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractsFormRequest;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\VehicleType;
use App\Models\Goods;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
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
        $vehicleTypes = VehicleType::all();
        $goods = Goods::all();
        $cities = City::all();
      

        return view('contracts.create', compact('vehicleTypes', 'goods', 'cities', 'Users'));
        
        // return view('contracts.create', compact('Users','Users'));
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
         
        try {
            // Validate the request
            $validatedData = $request->validated();
    
            // Create a new contract
            $contract = Contract::create($validatedData);
    
            // Get the contract details from the request
            $contractDetails = $request->input('contract_details', []);
    
            // Validate and save each contract detail
            foreach ($contractDetails as $detail) {
                $this->validateContractDetail($detail);
    
                // Create a new contract detail associated with the contract
                $contract->contractDetails()->create($detail);
            }
    
            return redirect()->route('contracts.contract.index')
                ->with('success_message', trans('contracts.model_was_added'));
        } catch (\Exception $e) {
            // Handle the validation error here
            return redirect()->back()
                ->withErrors($request->validate())
                ->withInput();
        }
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
       
        $vehicleTypes = VehicleType::all();
        $goods = Goods::all();

        return view('contracts.edit', compact('contract', 'vehicleTypes', 'goods','Users'));
        
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

    public function vehicleTypes()
    {
        $vehicleTypes = VehicleType::all();
       

        return response()->json([
            'vehicleTypes' => $vehicleTypes,
            
        ]);
    }
    public function fetchGoods(Request $request)
    {
       
       
        $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);
      
        $goodsTypeIds = $vehicleType->goodsTypes->pluck('id');

        $goods = Goods::whereIn('goods_type_id', $goodsTypeIds)->get();



        // dd($goods);

        return response()->json([
            'goods' => $goods,
            
        ]);
    }
    public function fetchCity(Request $request)
    {
       
       
        $city = City::all();
      
      return response()->json([
            'city' => $city,
            
        ]);
    }


            private function validateContractDetail(array $detail)
        {
            
            // Example: 'vehicle_type_id' should be required and exist in the 'vehicle_types' table
            return validator($detail, [
                'vehicle_type_id' => 'required|exists:vehicle_types,id',
                'goods_id' => 'required|exists:goods,id',
                'loading_city_id' => 'required|exists:cities,id',
                'dispersal_city_id' => 'required|exists:cities,id',
                'price' => 'required|numeric',
                // Add other validation rules as needed...
            ])->validate();
        }



        public function getContractDetails($id)
        {

            
            $contractDetails = ContractDetail::where('contract_id', $id)
                ->orderBy('created_at', 'asc') // اعتمادًا على الحقل الذي يحدد الترتيب
                ->get();

            return response()->json(['contractDetails' => $contractDetails]);
        }
}

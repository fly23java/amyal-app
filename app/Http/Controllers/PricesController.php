<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PricesFormRequest;
use App\Models\Account;
use App\Models\Price;
use App\Models\VehicleType;
use App\Models\Goods;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
class PricesController extends Controller
{

    /**
     * Display a listing of the prices.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $prices = Price::with('account')->paginate(25);

        return view('prices.index', compact('prices'));
    }

    /**
     * Show the form for creating a new price.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Accounts = Account::pluck('name_arabic','id')->all();

        $vehicleTypes = VehicleType::all();
        $goods = Goods::all();
        $cities = City::all();
        
        return view('prices.create', compact('Accounts' ,'vehicleTypes', 'goods', 'cities'));
    }

    /**
     * Store a new price in the storage.
     *
     * @param App\Http\Requests\PricesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(PricesFormRequest $request)
    {
        
        $data = $request->getData();
        
        Price::create($data);

        return redirect()->route('prices.price.index')
            ->with('success_message', trans('prices.model_was_added'));
    }

    /**
     * Display the specified price.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $price = Price::with('account')->findOrFail($id);

        return view('prices.show', compact('price'));
    }

    /**
     * Show the form for editing the specified price.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $price = Price::findOrFail($id);
        $Accounts = Account::pluck('name_arabic','id')->all();

        return view('prices.edit', compact('price','Accounts'));
    }

    /**
     * Update the specified price in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\PricesFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, PricesFormRequest $request)
    {
        
        $data = $request->getData();
        
        $price = Price::findOrFail($id);
        $price->update($data);

        return redirect()->route('prices.price.index')
            ->with('success_message', trans('prices.model_was_updated'));  
    }

    /**
     * Remove the specified price from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $price = Price::findOrFail($id);
            $price->delete();

            return redirect()->route('prices.price.index')
                ->with('success_message', trans('prices.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('prices.unexpected_error')]);
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

        $goods = Goods::whereIn('goods_type_id', $goodsTypeIds)
        ->where('account_id' , $request->receiver_id)
        ->get();



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


            private function validatePriceDetail(array $detail)
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



        public function getPriceDetails($id)
        {

            
            $PriceDetails = PriceDetail::where('price_id', $id)
                ->orderBy('created_at', 'asc') // اعتمادًا على الحقل الذي يحدد الترتيب
                ->get();

            return response()->json(['PriceDetails' => $PriceDetails]);
        }



}

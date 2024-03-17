<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PriceDetailsFormRequest;
use App\Models\City;
use App\Models\Good;
use App\Models\Price;
use App\Models\PriceDetail;
use App\Models\User;
use App\Models\VehicleType;
use Exception;

class PriceDetailsController extends Controller
{

    /**
     * Display a listing of the price details.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $priceDetails = PriceDetail::with('price','vehicletype','good','city','city','user')->paginate(25);

        return view('price_details.index', compact('priceDetails'));
    }

    /**
     * Show the form for creating a new price detail.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Prices = Price::pluck('price_title','id')->all();
$VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
$Goods = Good::pluck('name_arabic','id')->all();
$Cities = City::pluck('name_arabic','id')->all();
$Users = User::pluck('name','id')->all();
        
        return view('price_details.create', compact('Prices','VehicleTypes','Goods','Cities','Cities','Users'));
    }

    /**
     * Store a new price detail in the storage.
     *
     * @param App\Http\Requests\PriceDetailsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(PriceDetailsFormRequest $request)
    {
        
        $data = $request->getData();
        
        PriceDetail::create($data);

        return redirect()->route('price_details.price_detail.index')
            ->with('success_message', trans('price_details.model_was_added'));
    }

    /**
     * Display the specified price detail.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $priceDetail = PriceDetail::with('price','vehicletype','good','city','city','user')->findOrFail($id);

        return view('price_details.show', compact('priceDetail'));
    }

    /**
     * Show the form for editing the specified price detail.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $priceDetail = PriceDetail::findOrFail($id);
        $Prices = Price::pluck('price_title','id')->all();
$VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
$Goods = Good::pluck('name_arabic','id')->all();
$Cities = City::pluck('name_arabic','id')->all();
$Users = User::pluck('name','id')->all();

        return view('price_details.edit', compact('priceDetail','Prices','VehicleTypes','Goods','Cities','Cities','Users'));
    }

    /**
     * Update the specified price detail in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\PriceDetailsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, PriceDetailsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $priceDetail = PriceDetail::findOrFail($id);
        $priceDetail->update($data);

        return redirect()->route('price_details.price_detail.index')
            ->with('success_message', trans('price_details.model_was_updated'));  
    }

    /**
     * Remove the specified price detail from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $priceDetail = PriceDetail::findOrFail($id);
            $priceDetail->delete();

            return redirect()->route('price_details.price_detail.index')
                ->with('success_message', trans('price_details.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('price_details.unexpected_error')]);
        }
    }



}

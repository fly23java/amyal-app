<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ShipmentsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => 'required',
            'shipment_date' => 'required',
            'loading_city_id' => 'required',
            'unloading_city_id' => 'required',
            'vehicle_type_id' => 'required',
            'goods_id' => 'required',
            'price' => 'required|numeric|min:-999999.99|max:999999.99',
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData()
    {
        $data = $this->only(['user_id', 'loading_city_id', 'unloading_city_id', 'vehicle_type_id', 'goods_id', 'price']);



        return $data;
    }

}
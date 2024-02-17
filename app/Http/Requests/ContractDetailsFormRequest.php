<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ContractDetailsFormRequest extends FormRequest
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
            'contract_id' => 'required',
            'vehicle_type_id' => 'nullable',
            'goods_id' => 'nullable',
            'loading_city_id' => 'required',
            'dispersal_city_id' => 'required',
            'price' => 'required|numeric|min:-9999999.99|max:9999999.99',
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
        $data = $this->only(['contract_id', 'vehicle_type_id', 'goods_id', 'loading_city_id', 'dispersal_city_id', 'price']);



        return $data;
    }

}
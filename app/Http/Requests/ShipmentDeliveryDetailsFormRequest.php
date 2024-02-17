<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ShipmentDeliveryDetailsFormRequest extends FormRequest
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
            'shipment_id' => 'required',
            'vehicle_id' => 'required',
            'loading_time' => 'nullable',
            'unloading_time' => 'nullable',
            'arrival_time' => 'nullable',
            'departure_time' => 'nullable',
            'delivery_status' => 'nullable',
            'delivery_document' => 'nullable',
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
        $data = $this->only(['shipment_id', 'vehicle_id', 'loading_time', 'unloading_time', 'arrival_time', 'departure_time', 'delivery_status', 'delivery_document']);



        return $data;
    }

}
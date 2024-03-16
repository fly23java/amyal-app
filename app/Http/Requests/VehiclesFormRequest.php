<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class VehiclesFormRequest extends FormRequest
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
            'owner_name' => 'required|string|min:1|max:255',
            'sequence_number' => 'required|numeric',
            'plate' => 'required|string|min:1|max:255',
            'right_letter' => 'required|string|min:1|max:1',
            'middle_letter' => 'required|string|min:1|max:1',
            'left_letter' => 'required|string|min:1|max:1',
            'plate_type' => 'required|numeric|min:-2147483648|max:2147483647',
            'vehicle_type_id' => 'required',
            'account_id' => 'required|numeric|min:0',
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
        $data = $this->only(['owner_name', 'sequence_number', 'plate', 'right_letter', 'middle_letter', 'left_letter', 'plate_type', 'vehicle_type_id', 'account_id']);



        return $data;
    }

}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StatusChangesFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'status_id' => 'required',
            'user_id' => 'required',
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
        $data = $this->only(['shipment_id', 'status_id', 'user_id']);



        return $data;
    }

}
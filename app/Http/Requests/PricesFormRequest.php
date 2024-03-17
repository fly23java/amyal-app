<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class PricesFormRequest extends FormRequest
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
            'receiver_id' => 'required',
            'price_title' => 'required',
            'description' => 'required',
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
        $data = $this->only(['receiver_id', 'price_title', 'description']);



        return $data;
    }

}
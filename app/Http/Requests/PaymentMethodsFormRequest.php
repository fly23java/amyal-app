<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class PaymentMethodsFormRequest extends FormRequest
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
            'name_arabic' => 'required|string|min:1|max:255',
            'name_english' => 'required|string|min:1|max:255',
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
        $data = $this->only(['name_arabic', 'name_english']);



        return $data;
    }

}
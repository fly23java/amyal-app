<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class AccountsFormRequest extends FormRequest
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
            'name_english' => 'nullable|string|min:0|max:255',
            'cr_number' => 'nullable|numeric|string|min:0|max:255',
            'bank' => 'nullable|string|min:0|max:255',
            'iban' => 'nullable|string|min:0|max:255',
            'account_number' => 'nullable|numeric|string|min:0|max:255',
            'tax_number' => 'nullable|numeric|string|min:0|max:255',
            'tax_value' => 'nullable|string|min:0|max:255',
            'type' => 'required',
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
        $data = $this->only(['name_arabic', 'name_english', 'cr_number', 'bank', 'iban', 'account_number', 'tax_number', 'tax_value', 'type']);



        return $data;
    }

}
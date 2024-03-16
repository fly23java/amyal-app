<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class DriversFormRequest extends FormRequest
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
            'name_arabic' => 'required|string|min:0|max:255',
            'name_english' => 'nullable|string|min:0|max:255',
            'email' => 'nullable|string|min:0|max:255',
            'password' => 'nullable|string|min:0|max:255',
            'phone' => 'required|string|min:0|max:255',
            'identity_number' => 'required|string|min:0|max:255',
            'date_of_birth_hijri' => 'nullable|date_format:Y-m-d',
            'date_of_birth_gregorian' => 'nullable|date_format:Y-m-d',
            'account_id' => 'required|numeric|min:0',
            'vehicle_id' => 'required',
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
        $data = $this->only(['name_arabic', 'name_english', 'email', 'password', 'phone', 'identity_number', 'date_of_birth_hijri', 'date_of_birth_gregorian', 'account_id', 'vehicle_id']);



        return $data;
    }

}
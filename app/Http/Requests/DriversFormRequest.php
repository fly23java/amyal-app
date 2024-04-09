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
    

    public function messages()
    {
        return [
            'name_arabic.required' => 'The Arabic name field is required.',
            'name_arabic.string' => 'The Arabic name must be a string.',
            'name_arabic.min' => 'The Arabic name must be at least :min characters.',
            'name_arabic.max' => 'The Arabic name may not be greater than :max characters.',
            
            'name_english.string' => 'The English name must be a string.',
            'name_english.min' => 'The English name must be at least :min characters.',
            'name_english.max' => 'The English name may not be greater than :max characters.',
            
            'email.string' => 'The email must be a string.',
            'email.min' => 'The email must be at least :min characters.',
            'email.max' => 'The email may not be greater than :max characters.',
            
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least :min characters.',
            'password.max' => 'The password may not be greater than :max characters.',
            
            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.min' => 'The phone must be at least :min characters.',
            'phone.max' => 'The phone may not be greater than :max characters.',
            
            'identity_number.required' => 'The identity number field is required.',
            'identity_number.string' => 'The identity number must be a string.',
            'identity_number.min' => 'The identity number must be at least :min characters.',
            'identity_number.max' => 'The identity number may not be greater than :max characters.',
            
            'date_of_birth_hijri.date_format' => 'The Hijri date of birth does not match the format Y-m-d.',
            'date_of_birth_gregorian.date_format' => 'The Gregorian date of birth does not match the format Y-m-d.',
            
            'account_id.required' => 'The account ID field is required.',
            'account_id.numeric' => 'The account ID must be a number.',
            'account_id.min' => 'The account ID must be at least :min.',
            
            'vehicle_id.required' => 'The vehicle ID field is required.',
        ];
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
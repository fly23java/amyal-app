<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CountriesFormRequest extends FormRequest
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
            'name_arabic' => 'required|string|min:1|max:255',
            'name_english' => 'required|string|min:1|max:255',
            'alpha2_code' => 'required|string|min:1|max:255',
            'alpha3_code' => 'required|string|min:1|max:255',
            'phone_code' => 'required|numeric',
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
        $data = $this->only(['name_arabic', 'name_english', 'alpha2_code', 'alpha3_code', 'phone_code']);



        return $data;
    }

}
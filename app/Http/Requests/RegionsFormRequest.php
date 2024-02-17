<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class RegionsFormRequest extends FormRequest
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
            'country_id' => 'required|numeric|min:0',
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
        $data = $this->only(['country_id', 'name_arabic', 'name_english']);



        return $data;
    }

}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class GoodsTypesFormRequest extends FormRequest
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
            'name_arabic' => 'nullable|string|min:0|max:255',
            'name_english' => 'nullable|string|min:0|max:255',
            'parent_id' => 'nullable',
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
        $data = $this->only(['name_arabic', 'name_english', 'parent_id']);



        return $data;
    }

}
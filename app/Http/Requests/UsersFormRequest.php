<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UsersFormRequest extends FormRequest
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
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|string|min:1|max:255|unique:users',
            'password' => 'required|string|min:1|max:255',
            'birth_date' => 'nullable|string|min:0',
            'account_id' => 'required|numeric|min:0',
            'type' => 'required',
            'status' => 'required',
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
        $data = $this->only(['name', 'email', 'password', 'birth_date', 'account_id', 'type', 'status']);



        return $data;
    }

}
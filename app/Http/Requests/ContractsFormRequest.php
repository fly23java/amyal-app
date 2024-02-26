<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ContractsFormRequest extends FormRequest
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
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'contract_title' => 'required',
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
        $data = $this->only(['sender_id', 'receiver_id', 'description']);



        return $data;
    }

}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StatusesFormRequest extends FormRequest
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
            'message_text_in_arabic' => 'required|string|min:1|max:255',
            'message_text_in_english' => 'required|string|min:1|max:255',
            'confirm_sending_the_message' => 'boolean|numeric',
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
        $data = $this->only(['name_arabic', 'name_english', 'message_text_in_arabic', 'message_text_in_english', 'confirm_sending_the_message', 'parent_id']);

        $data['confirm_sending_the_message'] = $this->has('confirm_sending_the_message');


        return $data;
    }

}
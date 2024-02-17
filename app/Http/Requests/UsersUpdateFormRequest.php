<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UsersUpdateFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:1|max:255',
            'email' => ['required', 'string', 'max:500',
                 Rule::unique('users', 'email')
                ->ignore($this->user()->id)],
            'password' => 'required|string|min:1|max:255',
            'birth_date' => 'nullable|string|min:0',
            'account_id' => 'required|numeric|min:0',
            'type' => 'required',
            'status' => 'required',
        ];
    }
}

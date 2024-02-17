<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AccountUpdateRequest extends FormRequest
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
        
        $user_id = $this->session()->get('editableUser');
        // dd( $user_id );
        return [
            'name_arabic'=>'required|string',
            'account_type' =>'required|string',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user_id->id)],
            // 'oldpassword' => 'sometimes|nullable|string|min:8',
            'newpassword' => 'sometimes|nullable|string|min:8',
        ];
    }
}

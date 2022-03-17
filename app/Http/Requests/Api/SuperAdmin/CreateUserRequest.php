<?php

namespace App\Http\Requests\Api\SuperAdmin;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends ApiFormRequest
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
        return [
            'email' => ['required', 'string', 'unique:users,email'],
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'gender' => ['required'],
            'phone_number' => ['required', 'size:10'],
            'address' => ['required'],
            'role_id' => ['required'],
            'password' => ['required', 'string', 'min:5', 'max:30', 'confirmed'],
        ];
    }
}

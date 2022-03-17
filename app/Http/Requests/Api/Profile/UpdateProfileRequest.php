<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\ApiFormRequest;


class UpdateProfileRequest extends ApiFormRequest
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
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'gender' => ['required'],
            'phone_number' => ['required', 'size:10'],
            'address' => ['required'],
        ];
    }
}

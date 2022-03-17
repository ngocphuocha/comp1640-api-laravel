<?php

namespace App\Http\Requests\Api\QA_Manager;

use App\Http\Requests\Api\ApiFormRequest;

class StoreCategoryRequest extends ApiFormRequest
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
            'name' => ['required'],
            'description' => ['required']
        ];
    }
}

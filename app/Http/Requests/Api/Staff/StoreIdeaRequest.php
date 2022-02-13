<?php

namespace App\Http\Requests\Api\Staff;

use App\Http\Requests\Api\ApiFormRequest;

class StoreIdeaRequest extends ApiFormRequest
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
            'title' => ['required'],
            'content' => ['required'],
            'category_id' => ['required'],
            'is_hidden' => ['nullable'],
            'file' => ['nullable', 'mimes:pdf'],
        ];
    }
}

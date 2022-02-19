<?php

namespace App\Http\Requests\Api\Idea;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateIdeaRequest extends ApiFormRequest
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
            'title' => ['required', 'min: 5'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer']
        ];
    }
}

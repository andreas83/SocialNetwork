<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentStoreRequest extends FormRequest
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
        'html_content' => ['required'],
        'json_content' => ['required'],
        'has_comment' => ['required', 'boolean'],
        'is_comment' => ['required', 'boolean'],
        'anonymous' => ['required', 'boolean'],
        'visibility' => ['required', 'in:public,private,friends'],
        'parent_id' => ['required', 'integer'],
        ];
    }
}

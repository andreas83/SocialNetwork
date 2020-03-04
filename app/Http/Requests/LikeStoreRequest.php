<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikeStoreRequest extends FormRequest
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
        'key' => ['required', 'in:heart,happy,wink,like,devil,coffee,beer,sunglasses,displeased'],
        'content_id' => ['required', 'integer'],
        ];
    }
}

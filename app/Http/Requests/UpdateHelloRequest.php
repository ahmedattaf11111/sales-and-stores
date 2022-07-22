<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHelloRequest extends FormRequest
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
            "title_ar" => "required|unique:hellos,title_ar," . request()->id,
            "title_en" => "required|unique:hellos,title_en," . request()->id,
            "image" => "nullable|image",
            "list" => "required|array|min:1",
            "list.*.title_ar" => "required",
            "list.*.title_en" => "required"
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            "name" => "required",
            "email" => "required|unique:admins",
            "password" => "required",
            "treasuries_ids" => "array",
            "treasuries_ids.*" => "required|numeric",
            "permissions" => "array",
            "permissions.*" => "required",
        ];
    }
}

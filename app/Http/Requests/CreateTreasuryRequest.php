<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTreasuryRequest extends FormRequest
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
            "name" => "required|unique:treasuries",
            "last_collection_receipt" => "required|numeric|min:0",
            "last_exchange_receipt" => "required|numeric|min:0",
            "active" => "required|boolean",
            "is_master" => ["required","boolean", Rule::unique('treasuries')->where(function ($query) {
                return $query->where('is_master', 1);
           })],
            "treasuries_ids" => "array",
            "treasuries_ids.*" => "required|numeric",
        ];
    }
}

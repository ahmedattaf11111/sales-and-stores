<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
            "account_type_id" => "required",
            "is_master" => "required|boolean",
            "parent_id" => !$this->is_master?"required":"nullable",
            "start_balance" => "required|numeric",//If (0) balance (-) debtor (+) creditor
        ];
    }
}

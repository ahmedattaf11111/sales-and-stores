<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            "id" => "required",
            "name" => "required",
            "start_balance" => "required|numeric", //If (0) balance (-) debtor (+) creditor
            "address" => "required",
            "phone" => 'required|regex:/^01[0125][0-9]{8}$/',
            "supplier_category_id" => "required",
        ];
    }
}

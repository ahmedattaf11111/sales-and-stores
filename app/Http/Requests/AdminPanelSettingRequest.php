<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPanelSettingRequest extends FormRequest
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
            "system_name" => 'required',
            "phone" => 'required|regex:/^01[0125][0-9]{8}$/',
            "address" => 'required',
            "general_alert" => "nullable",
            "customer_parent_account_id"=>"required|numeric",
            "supplier_parent_account_id"=>"required|numeric",
            "delegate_parent_account_id"=>"required|numeric",
        ];
    }
}

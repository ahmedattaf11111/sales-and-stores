<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDelegateRequest extends FormRequest
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
            "start_balance" => "required|numeric", //If (0) balance (-) debtor (+) creditor
            "address"=>"required",
            "phone" => 'required|regex:/^01[0125][0-9]{8}$/',
            "note" => "nullable",
            "active" => "required|boolean",
            "percent_type"=>"required|boolean",
            "percent_collect_commission" => "required|numeric|min:0" . $this->percent_type ? "|max:100" : "",
            "percent_sales_retail_commission" => "required|numeric|min:0" . $this->percent_type ? "|max:100" : "",
            "percent_halfsale_commission" => "required|numeric|min:0" . $this->percent_type ? "|max:100" : "",
            "percent_wholesale_commission" => "required|numeric|min:0" . $this->percent_type ? "|max:100" : "",
        ];
    }
}

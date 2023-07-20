<?php

namespace App\Http\Requests\SaleReturnInvoice;

use Illuminate\Foundation\Http\FormRequest;

class SaleReturnInvoiceRequest extends FormRequest
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
            "date"=>"required|date",
            "customer_id"=>"required",
            "delegate_id"=>"required",
            "invoice_number" => "required|unique:sale_invoices",
            "invoice_category_id"=>"required",
            "sale_price_type"=>"required",
        ];
    }
}

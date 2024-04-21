<?php

namespace App\Http\Requests\SaleInvoice;

use Illuminate\Foundation\Http\FormRequest;

class EditSaleInvoiceRequest extends FormRequest
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
        $rules = [
            "id" => "required", //sale invoice id
            "date" => "required|date",
            "customer_id" => "required",
            "delegate_id" => "required",
            "invoice_number" => "required|unique:sale_invoices,invoice_number," . $this->id,
            "invoice_category_id" => "required",
            "tax" => "required|numeric",
            "is_tax_percent" => "required|boolean",
            "discount" => "required|numeric",
            "is_discount_percent" => "required|boolean",
            "is_deferred" => "required|boolean",
        ];
        if ($this->is_deferred) $rules["paid_amount"] = "required|min:0";
        return $rules;
    }
}

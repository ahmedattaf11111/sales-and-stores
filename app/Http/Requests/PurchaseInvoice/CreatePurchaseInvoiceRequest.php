<?php

namespace App\Http\Requests\PurchaseInvoice;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseInvoiceRequest extends FormRequest
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
            "date" => "required|date",
            "invoice_number" => "required|unique:purchase_invoices",
            "supplier_id" => "required|numeric",
            "store_id" => "required|numeric",
        ];
    }
}

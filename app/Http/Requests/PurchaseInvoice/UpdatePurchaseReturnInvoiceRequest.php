<?php

namespace App\Http\Requests\PurchaseInvoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseReturnInvoiceRequest extends FormRequest
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
            "id" => "required|numeric",
            "date" => "required|date",
            "supplier_id" => "required|numeric",
            "store_id" => "required|numeric",
            "invoice_number" => "required|unique:purchase_invoices,invoice_number," . $this->id,
        ];
    }
}

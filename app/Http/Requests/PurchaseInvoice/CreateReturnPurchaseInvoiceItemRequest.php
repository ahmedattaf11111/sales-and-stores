<?php

namespace App\Http\Requests\PurchaseInvoice;

use Illuminate\Foundation\Http\FormRequest;

class CreateReturnPurchaseInvoiceItemRequest extends FormRequest
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
            "purchase_invoice_id" => "required|numeric",
            "item_id" => "required|numeric",
            "unit_of_measure_id" => "required|numeric",
            "quantity" => "required|numeric",
            "purchase_price" => "required|numeric",
            "batch_id" => "required|numeric",
        ];
    }
}

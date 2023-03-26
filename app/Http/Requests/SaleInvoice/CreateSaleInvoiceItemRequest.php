<?php

namespace App\Http\Requests\SaleInvoice;

use Illuminate\Foundation\Http\FormRequest;

class CreateSaleInvoiceItemRequest extends FormRequest
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
            "sale_invoice_id" => "required|numeric",
            "store_id" => "required|numeric",
            "item_id" => "required|numeric",
            "unit_of_measure_id" => "required|numeric",
            "sale_price_type" => "required",
            "is_sale_price_normal" => "required",
            "quantity" => "required|numeric",
            "sale_price" => "required|numeric",
            "batch_id" => "required",
        ];
    }
}

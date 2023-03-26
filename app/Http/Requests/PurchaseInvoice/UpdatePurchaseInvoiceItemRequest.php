<?php

namespace App\Http\Requests\PurchaseInvoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseInvoiceItemRequest extends FormRequest
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
            "item_id" => "required|numeric",
            "unit_of_measure_id" => "required|numeric",
            "quantity" => "required|numeric",
            "purchase_price" => "required|numeric",
            "production_date" => "nullable|date",
            "expiration_date" => "nullable|date",
        ];
    }
}

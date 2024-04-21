<?php

namespace App\Http\Requests\PurchaseInvoice;

use Illuminate\Foundation\Http\FormRequest;

class ApprovePurchaseInvoiceRequest extends FormRequest
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
            "id" => "required|numeric",
            "is_discount_percent" => "required|boolean",
            "discount" => "required|numeric|min:0" . $this->is_discount_percent ? "|max:100" : "",
            "is_tax_percent" => "required|boolean",
            "tax" => "required|numeric|min:0" . $this->is_tax_percent ? "|max:100" : "",
            "is_deferred" => "required|boolean",
        ];
        if ($this->is_deferred) $rules["paid_amount"] = "required|min:0";
        return $rules;
    }
}

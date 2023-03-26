<?php

namespace App\Http\Requests;

use App\Commons\Consts\TreasuryTransactionType;
use Illuminate\Foundation\Http\FormRequest;

class TreasuryTransactionRequest extends FormRequest
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
            "type" => "required|in:" . TreasuryTransactionType::ALL,
            "account_id" => "required|numeric",
            "amount" => "required|numeric|min:0",
            "note" => "nullable"
        ];
    }
}

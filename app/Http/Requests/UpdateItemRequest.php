<?php

namespace App\Http\Requests;

use App\Commons\Consts\ItemType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            "id" => "required",
            "name" => "required|unique:items,name," . $this->id,
            "image" => "nullable|image",
            "item_category_id" => "required",
            "type" => "required|in:" . ItemType::ALL,
            "has_fixed_price" => "required|boolean",
            "main_unit_of_measure_id" => "required",
            "main_unit_half_wholesale_price" => "required|numeric|min:0",
            "main_unit_wholesale_price" => "required|numeric|min:0",
            "main_unit_retail_price" => "required|numeric|min:0",
            "has_sub_unit" => "required|boolean",
            "sub_to_main_quantity" => [$this->has_sub_unit ? "required" : "nullable", "numeric", "min:2"],
            "sub_unit_of_measure_id" => $this->has_sub_unit ? "required" : "nullable",
            "sub_unit_half_wholesale_price" => [$this->has_sub_unit ? "required" : "nullable", "numeric", "min:0"],
            "sub_unit_wholesale_price" => [$this->has_sub_unit ? "required" : "nullable", "numeric", "min:0"],
            "sub_unit_retail_price" => [$this->has_sub_unit ? "required" : "nullable", "numeric", "min:0"]
        ];
    }
}

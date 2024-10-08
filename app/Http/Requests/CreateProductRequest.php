<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:products|max:155',
            'expire_date' => 'required|string',
            'category' => 'required',
            'phone_number' =>'required',
            'price' => 'required|min:0',
            'quantity' => 'required|min:1',
        ];
    }

    public function attributes()
    {
        return[
            'name' => __('product.name'),
            'expire_date' =>  __('product.expire_date'),
            'category' => __('product.category'),
            'phone_number' => __('product.phone_number'),
            'price' => __('product.price'),
            'quantity' => __('product.quantity')
        ];
    }
}

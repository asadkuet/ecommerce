<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\BaseFormRequest;

class ProductRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required']
        ];
    }
}

<?php

namespace App\Http\Requests\Addresses;

use App\Http\Requests\BaseFormRequest;

class CreateAddressRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city' => ['required'],
            'address' => ['required']
        ];
    }
}

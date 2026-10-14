<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'harga' => ['required', 'integer', 'min:1', 'max:10000000'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000'],
            'member' => ['sometimes', 'boolean'],
            'ekspres' => ['sometimes', 'boolean'],
        ];
    }
}

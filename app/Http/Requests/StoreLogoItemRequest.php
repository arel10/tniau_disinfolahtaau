<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogoItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'    => 'required|string|max:255',
            'link_url' => 'nullable|url|max:500',
            'logo'     => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ];
    }
}

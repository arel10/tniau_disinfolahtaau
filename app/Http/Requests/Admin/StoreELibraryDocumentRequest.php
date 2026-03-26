<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreELibraryDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pdfs.*' => 'required|file|mimes:pdf|max:25600', // 25MB
            'cover_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

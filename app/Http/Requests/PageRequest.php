<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],
            'body' => 'required|string',
            'is_published' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul halaman wajib diisi.',
            'body.required' => 'Konten halaman wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
        ];
    }
}

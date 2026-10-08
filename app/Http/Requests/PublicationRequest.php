<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pubId = $this->route('publication')?->id;

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('publications', 'slug')->ignore($pubId),
            ],
            'type' => 'required|in:berita,infografis',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul publikasi wajib diisi.',
            'type.required' => 'Tipe publikasi wajib dipilih.',
            'type.in' => 'Tipe harus berita atau infografis.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'image.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
        ];
    }
}

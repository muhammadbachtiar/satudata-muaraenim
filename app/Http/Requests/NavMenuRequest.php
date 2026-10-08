<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NavMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:link,page,route',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'parent_id' => 'nullable|exists:nav_menus,id',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul menu wajib diisi.',
            'type.required' => 'Tipe menu wajib dipilih.',
            'type.in' => 'Tipe menu harus link, halaman, atau route.',
        ];
    }
}

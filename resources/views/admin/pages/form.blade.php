@extends('admin.layouts.app')
@section('title', $page ? 'Edit Halaman' : 'Tambah Halaman')
@section('header', $page ? 'Edit Halaman' : 'Tambah Halaman')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ $page ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST">
            @csrf
            @if($page) @method('PUT') @endif

            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title ?? '') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span
                            class="text-gray-400 font-normal">(otomatis jika kosong)</span></label>
                    <div class="flex items-center gap-0">
                        <span
                            class="bg-gray-100 border border-r-0 border-gray-300 px-3 py-2.5 rounded-l-lg text-sm text-gray-500">/halaman/</span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug ?? '') }}"
                            class="flex-1 px-4 py-2.5 rounded-r-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            placeholder="nama-halaman">
                    </div>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1.5">Konten *</label>
                    <textarea id="body" name="body" rows="15" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono leading-relaxed"
                        placeholder="Tulis konten halaman... (mendukung HTML)">{{ old('body', $page->body ?? '') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Mendukung HTML. Gunakan tag &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;
                        untuk struktur konten.</p>
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1"
                            {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Publikasikan (tampilkan di website)
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-5 border-t border-gray-200">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-sm">
                    {{ $page ? 'Simpan Perubahan' : 'Buat Halaman' }}
                </button>
                <a href="{{ route('admin.pages.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
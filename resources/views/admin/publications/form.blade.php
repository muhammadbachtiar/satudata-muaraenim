@extends('admin.layouts.app')
@section('title', $publication ? 'Edit Publikasi' : 'Tambah Publikasi')
@section('header', $publication ? 'Edit Publikasi' : 'Tambah Publikasi')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form
            action="{{ $publication ? route('admin.publications.update', $publication) : route('admin.publications.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if($publication) @method('PUT') @endif

            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $publication->title ?? '') }}"
                        required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span
                                class="text-gray-400 font-normal">(otomatis)</span></label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $publication->slug ?? '') }}"
                            placeholder="otomatis-dari-judul"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe *</label>
                        <select id="type" name="type" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="berita"
                                {{ old('type', $publication->type ?? '') === 'berita' ? 'selected' : '' }}>📰 Berita
                            </option>
                            <option value="infografis"
                                {{ old('type', $publication->type ?? '') === 'infografis' ? 'selected' : '' }}>📊
                                Infografis</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">Gambar</label>
                    @if($publication?->image)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $publication->image_url }}" alt=""
                            class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                        <span class="text-xs text-gray-400">Gambar saat ini. Upload baru untuk mengganti.</span>
                    </div>
                    @endif
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau WebP. Maksimal 2MB.</p>
                    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1.5">Ringkasan</label>
                    <textarea id="excerpt" name="excerpt" rows="3"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        placeholder="Ringkasan singkat untuk preview...">{{ old('excerpt', $publication->excerpt ?? '') }}</textarea>
                    @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1.5">Konten</label>
                    <textarea id="body" name="body" rows="12"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono leading-relaxed"
                        placeholder="Tulis konten publikasi... (mendukung HTML)">{{ old('body', $publication->body ?? '') }}</textarea>
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal
                            Publish</label>
                        <input type="date" id="published_at" name="published_at"
                            value="{{ old('published_at', $publication?->published_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm text-gray-700 pb-2.5">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1"
                                {{ old('is_published', $publication->is_published ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Publikasikan
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-5 border-t border-gray-200">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-sm">
                    {{ $publication ? 'Simpan Perubahan' : 'Buat Publikasi' }}
                </button>
                <a href="{{ route('admin.publications.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
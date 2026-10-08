@extends('admin.layouts.app')
@section('title', $menu ? 'Edit Menu' : 'Tambah Menu')
@section('header', $menu ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ $menu ? route('admin.nav-menus.update', $menu) : route('admin.nav-menus.store') }}"
            method="POST" x-data="{ type: '{{ old('type', $menu->type ?? 'route') }}' }">
            @csrf
            @if($menu) @method('PUT') @endif

            <div class="space-y-5">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul Menu *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $menu->title ?? '') }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Menu *</label>
                    <div class="flex gap-3">
                        <label
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition text-sm"
                            :class="type === 'route' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 text-gray-600 hover:border-gray-400'">
                            <input type="radio" name="type" value="route" x-model="type" class="hidden"> 🔗 Route
                            Internal
                        </label>
                        <label
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition text-sm"
                            :class="type === 'link' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 text-gray-600 hover:border-gray-400'">
                            <input type="radio" name="type" value="link" x-model="type" class="hidden"> 🌐 Link Luar
                        </label>
                        <label
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition text-sm"
                            :class="type === 'page' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 text-gray-600 hover:border-gray-400'">
                            <input type="radio" name="type" value="page" x-model="type" class="hidden"> 📄 Halaman
                        </label>
                    </div>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Route Name (conditional) --}}
                <div x-show="type === 'route'" x-cloak>
                    <label for="route_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Route</label>
                    <select id="route_name" name="route_name"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">— Pilih route —</option>
                        <option value="home"
                            {{ old('route_name', $menu->route_name ?? '') === 'home' ? 'selected' : '' }}>Beranda (/)
                        </option>
                        <option value="datasets.search"
                            {{ old('route_name', $menu->route_name ?? '') === 'datasets.search' ? 'selected' : '' }}>
                            Dataset (/dataset)</option>
                        <option value="organizations"
                            {{ old('route_name', $menu->route_name ?? '') === 'organizations' ? 'selected' : '' }}>
                            Instansi (/instansi)</option>
                        <option value="publikasi"
                            {{ old('route_name', $menu->route_name ?? '') === 'publikasi' ? 'selected' : '' }}>Publikasi
                            (/publikasi)</option>
                        <option value="tentang"
                            {{ old('route_name', $menu->route_name ?? '') === 'tentang' ? 'selected' : '' }}>Tentang
                            (/tentang)</option>
                    </select>
                    @error('route_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- External URL (conditional) --}}
                <div x-show="type === 'link'" x-cloak>
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-1.5">URL Eksternal</label>
                    <input type="url" id="url" name="url" value="{{ old('url', $menu->url ?? '') }}"
                        placeholder="https://..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Page (conditional) --}}
                <div x-show="type === 'page'" x-cloak>
                    <label for="page_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Halaman</label>
                    <select id="page_id" name="page_id"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">— Pilih halaman —</option>
                        @foreach($pages as $page)
                        <option value="{{ $page->id }}"
                            {{ old('page_id', $menu->page_id ?? '') == $page->id ? 'selected' : '' }}>
                            {{ $page->title }}
                        </option>
                        @endforeach
                    </select>
                    @error('page_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Parent Menu --}}
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1.5">Induk Menu (Submenu
                        dari)</label>
                    <select id="parent_id" name="parent_id"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">— Menu Utama (tanpa induk) —</option>
                        @foreach($parents as $parent)
                        <option value="{{ $parent->id }}"
                            {{ old('parent_id', $menu->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->title }}
                        </option>
                        @endforeach
                    </select>
                    @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Order --}}
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-1.5">Urutan</label>
                        <input type="number" id="order" name="order" value="{{ old('order', $menu->order ?? 0) }}"
                            min="0"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    {{-- Checkboxes --}}
                    <div class="flex flex-col justify-end gap-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Aktif
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="open_in_new_tab" value="0">
                            <input type="checkbox" name="open_in_new_tab" value="1"
                                {{ old('open_in_new_tab', $menu->open_in_new_tab ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Buka di tab baru
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-5 border-t border-gray-200">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-sm">
                    {{ $menu ? 'Simpan Perubahan' : 'Tambah Menu' }}
                </button>
                <a href="{{ route('admin.nav-menus.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('admin.layouts.app')
@section('title', 'Navigasi Menu')
@section('header', 'Navigasi Menu')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">Kelola menu navigasi website. Bisa tambah submenu, link eksternal, atau halaman.
    </p>
    <a href="{{ route('admin.nav-menus.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition shadow-sm">
        + Tambah Menu
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    @if($menus->count())
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Urutan</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Judul</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Tipe</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Target</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($menus as $menu)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 text-gray-500">{{ $menu->order }}</td>
                <td class="px-5 py-3 font-medium text-gray-900">
                    {{ $menu->title }}
                </td>
                <td class="px-5 py-3">
                    <span
                        class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $menu->type === 'link' ? 'bg-amber-50 text-amber-600' : ($menu->type === 'page' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600') }}">
                        {{ $menu->type === 'link' ? 'Link Luar' : ($menu->type === 'page' ? 'Halaman' : 'Route') }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs max-w-[200px] truncate">
                    @if($menu->type === 'link') {{ $menu->url }}
                    @elseif($menu->type === 'page') {{ $menu->page?->title ?? '-' }}
                    @else {{ $menu->route_name }}
                    @endif
                </td>
                <td class="px-5 py-3">
                    @if($menu->is_active)
                    <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                    <span class="text-xs text-green-600 ml-1">Aktif</span>
                    @else
                    <span class="w-2 h-2 bg-gray-300 rounded-full inline-block"></span>
                    <span class="text-xs text-gray-400 ml-1">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.nav-menus.edit', $menu) }}"
                            class="text-blue-600 hover:text-blue-700 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.nav-menus.destroy', $menu) }}" method="POST"
                            onsubmit="return confirm('Hapus menu {{ $menu->title }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>

            {{-- Sub-menus --}}
            @foreach($menu->children as $child)
            <tr class="hover:bg-gray-50 transition bg-gray-50/50">
                <td class="px-5 py-3 text-gray-400 pl-10">↳ {{ $child->order }}</td>
                <td class="px-5 py-3 font-medium text-gray-700 pl-10">{{ $child->title }}</td>
                <td class="px-5 py-3">
                    <span
                        class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $child->type === 'link' ? 'bg-amber-50 text-amber-600' : ($child->type === 'page' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600') }}">
                        {{ $child->type === 'link' ? 'Link Luar' : ($child->type === 'page' ? 'Halaman' : 'Route') }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs max-w-[200px] truncate">
                    @if($child->type === 'link') {{ $child->url }}
                    @elseif($child->type === 'page') {{ $child->page?->title ?? '-' }}
                    @else {{ $child->route_name }}
                    @endif
                </td>
                <td class="px-5 py-3">
                    @if($child->is_active)
                    <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                    <span class="text-xs text-green-600 ml-1">Aktif</span>
                    @else
                    <span class="w-2 h-2 bg-gray-300 rounded-full inline-block"></span>
                    <span class="text-xs text-gray-400 ml-1">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.nav-menus.edit', $child) }}"
                            class="text-blue-600 hover:text-blue-700 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.nav-menus.destroy', $child) }}" method="POST"
                            onsubmit="return confirm('Hapus submenu {{ $child->title }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
    @else
    <div class="py-12 text-center">
        <div class="text-4xl mb-3">📋</div>
        <p class="text-gray-500 text-sm">Belum ada menu navigasi.</p>
        <a href="{{ route('admin.nav-menus.create') }}"
            class="text-blue-600 text-sm font-medium hover:underline mt-2 inline-block">Tambah menu pertama →</a>
    </div>
    @endif
</div>
@endsection
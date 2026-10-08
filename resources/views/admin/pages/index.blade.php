@extends('admin.layouts.app')
@section('title', 'Halaman')
@section('header', 'Halaman')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">Kelola halaman statis yang bisa ditautkan ke menu navigasi.</p>
    <a href="{{ route('admin.pages.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition shadow-sm">
        + Tambah Halaman
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    @if($pages->count())
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Judul</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Slug</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Diperbarui</th>
                <th class="text-right px-5 py-3 font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($pages as $page)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 font-medium text-gray-900">{{ $page->title }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs font-mono">/halaman/{{ $page->slug }}</td>
                <td class="px-5 py-3">
                    @if($page->is_published)
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">Publik</span>
                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Draft</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-400 text-xs">{{ $page->updated_at->format('d M Y H:i') }}</td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="/halaman/{{ $page->slug }}" target="_blank"
                            class="text-gray-400 hover:text-gray-600 text-xs font-medium">Lihat</a>
                        <a href="{{ route('admin.pages.edit', $page) }}"
                            class="text-blue-600 hover:text-blue-700 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                            onsubmit="return confirm('Hapus halaman {{ $page->title }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $pages->links() }}
    </div>
    @else
    <div class="py-12 text-center">
        <div class="text-4xl mb-3">📄</div>
        <p class="text-gray-500 text-sm">Belum ada halaman.</p>
        <a href="{{ route('admin.pages.create') }}"
            class="text-blue-600 text-sm font-medium hover:underline mt-2 inline-block">Buat halaman pertama →</a>
    </div>
    @endif
</div>
@endsection
@extends('admin.layouts.app')
@section('title', 'Publikasi')
@section('header', 'Publikasi')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">Kelola berita dan infografis yang tampil di website.</p>
    <a href="{{ route('admin.publications.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition shadow-sm">
        + Tambah Publikasi
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    @if($publications->count())
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Gambar</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Judul</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Tipe</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                <th class="text-left px-5 py-3 font-semibold text-gray-600">Tanggal</th>
                <th class="text-right px-5 py-3 font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($publications as $pub)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3">
                    @if($pub->image)
                    <img src="{{ $pub->image_url }}" alt=""
                        class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                    @else
                    <div
                        class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-lg">
                        {{ $pub->type === 'berita' ? '📰' : '📊' }}
                    </div>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900">{{ Str::limit($pub->title, 50) }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">/publikasi/{{ $pub->slug }}</div>
                </td>
                <td class="px-5 py-3">
                    <span
                        class="px-2 py-1 rounded-full text-xs font-medium {{ $pub->type === 'berita' ? 'bg-amber-50 text-amber-600' : 'bg-teal-50 text-teal-600' }}">
                        {{ $pub->type === 'berita' ? '📰 Berita' : '📊 Infografis' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    @if($pub->is_published)
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">Publik</span>
                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Draft</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-400 text-xs">{{ $pub->published_at?->format('d M Y') }}</td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.publications.edit', $pub) }}"
                            class="text-blue-600 hover:text-blue-700 text-xs font-medium">Edit</a>
                        <form action="{{ route('admin.publications.destroy', $pub) }}" method="POST"
                            onsubmit="return confirm('Hapus publikasi {{ $pub->title }}?')">
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
        {{ $publications->links() }}
    </div>
    @else
    <div class="py-12 text-center">
        <div class="text-4xl mb-3">📰</div>
        <p class="text-gray-500 text-sm">Belum ada publikasi.</p>
        <a href="{{ route('admin.publications.create') }}"
            class="text-blue-600 text-sm font-medium hover:underline mt-2 inline-block">Tambah publikasi pertama →</a>
    </div>
    @endif
</div>
@endsection
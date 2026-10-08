@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500 mb-1">Total Menu</div>
        <div class="text-2xl font-bold text-gray-900">{{ $menuCount }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500 mb-1">Halaman</div>
        <div class="text-2xl font-bold text-gray-900">{{ $pageCount }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500 mb-1">Berita</div>
        <div class="text-2xl font-bold text-gray-900">{{ $beritaCount }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-sm font-medium text-gray-500 mb-1">Infografis</div>
        <div class="text-2xl font-bold text-gray-900">{{ $infografisCount }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Publications --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Publikasi Terbaru</h2>
            <a href="{{ route('admin.publications.index') }}"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentPublications as $pub)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($pub->title, 40) }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ $pub->type === 'berita' ? '📰 Berita' : '📊 Infografis' }} ·
                        {{ $pub->published_at?->format('d M Y') }}</div>
                </div>
                <span
                    class="text-xs px-2 py-1 rounded-full {{ $pub->is_published ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                    {{ $pub->is_published ? 'Publik' : 'Draft' }}
                </span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada publikasi</div>
            @endforelse
        </div>
    </div>

    {{-- Nav Menu Overview --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Menu Navigasi</h2>
            <a href="{{ route('admin.nav-menus.index') }}"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium">Kelola</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($navMenus as $menu)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="text-sm font-medium text-gray-900">
                    {{ $menu->title }}
                    @if($menu->children->count())
                    <span class="text-xs text-gray-400 ml-1">({{ $menu->children->count() }} sub)</span>
                    @endif
                </div>
                <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600">{{ $menu->type }}</span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada menu</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
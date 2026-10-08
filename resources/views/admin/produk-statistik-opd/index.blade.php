@extends('admin.layouts.app')
@section('title', 'Produk Statistik OPD')
@section('header', 'Produk Statistik OPD')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-900 mb-1">Upload PDF</h2>
            <p class="text-sm text-gray-500 mb-5">Unggah file PDF Produk Statistik OPD. Maksimal 50 MB per file.</p>

            <form action="{{ route('admin.produk-statistik-opd.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div>
                    <label for="pdf" class="block text-sm font-medium text-gray-700 mb-1.5">File PDF *</label>
                    <input type="file" id="pdf" name="pdf" accept="application/pdf" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-sm">
                    Upload PDF
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900">Daftar File</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Tampil di halaman /publikasi/produk-statistik-opd</p>
                </div>
                <a href="{{ route('produk-statistik-opd') }}" target="_blank"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat halaman</a>
            </div>

            @if($files->count())
            <div class="divide-y divide-gray-100">
                @foreach($files as $file)
                <div class="px-5 py-4 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <div class="font-medium text-gray-900 truncate">{{ $file['title'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $file['name'] }} · {{ number_format($file['size'] / 1024 / 1024, 1) }} MB ·
                            {{ \Carbon\Carbon::createFromTimestamp($file['date'])->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ $file['url'] }}" target="_blank"
                            class="text-blue-600 hover:text-blue-700 text-xs font-medium">Download</a>
                        <form action="{{ route('admin.produk-statistik-opd.destroy', $file['name']) }}" method="POST"
                            onsubmit="return confirm('Hapus file {{ $file['name'] }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-12 text-center">
                <div class="text-4xl mb-3">📄</div>
                <p class="text-gray-500 text-sm">Belum ada file PDF Produk Statistik OPD.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
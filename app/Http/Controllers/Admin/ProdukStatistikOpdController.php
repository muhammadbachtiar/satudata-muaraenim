<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukStatistikOpdController extends Controller
{
    private string $directory = 'produk-statistik-opd';

    public function index()
    {
        $files = collect(Storage::disk('public')->files($this->directory))
            ->filter(fn(string $path) => Str::lower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf')
            ->map(fn(string $path) => [
                'name' => basename($path),
                'title' => Str::of(pathinfo($path, PATHINFO_FILENAME))->replace(['-', '_'], ' ')->title()->toString(),
                'date' => Storage::disk('public')->lastModified($path),
                'size' => Storage::disk('public')->size($path),
                'url' => Storage::url($path),
            ])
            ->sortByDesc('date')
            ->values();

        return view('admin.produk-statistik-opd.index', compact('files'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:500000'],
        ]);

        $file = $validated['pdf'];
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = Str::slug($name) . '-' . now()->format('YmdHis') . '.pdf';

        $file->storeAs($this->directory, $filename, 'public');

        return redirect()
            ->route('admin.produk-statistik-opd.index')
            ->with('success', 'File PDF Produk Statistik OPD berhasil diunggah.');
    }

    public function destroy(string $filename)
    {
        $path = $this->directory . '/' . basename($filename);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('admin.produk-statistik-opd.index')
            ->with('success', 'File PDF Produk Statistik OPD berhasil dihapus.');
    }
}

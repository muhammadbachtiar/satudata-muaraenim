<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicationRequest;
use App\Models\Publication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::orderBy('published_at', 'desc')->simplePaginate(15);

        return view('admin.publications.index', compact('publications'));
    }

    public function create()
    {
        return view('admin.publications.form', ['publication' => null]);
    }

    public function store(PublicationRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('publications', 'public');
        }

        Publication::create($data);

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publikasi berhasil dibuat.');
    }

    public function edit(Publication $publication)
    {
        return view('admin.publications.form', compact('publication'));
    }

    public function update(PublicationRequest $request, Publication $publication)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($publication->image) {
                Storage::disk('public')->delete($publication->image);
            }
            $data['image'] = $request->file('image')->store('publications', 'public');
        }

        $publication->update($data);

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function destroy(Publication $publication)
    {
        if ($publication->image) {
            Storage::disk('public')->delete($publication->image);
        }

        $publication->delete();

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }
}

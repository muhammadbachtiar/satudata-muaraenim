<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Publication;
use App\Services\BeritaService;
use App\Services\CkanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CkanController extends Controller
{
    protected CkanService $ckan;
    protected BeritaService $berita;

    public function __construct(CkanService $ckan, BeritaService $berita)
    {
        $this->ckan = $ckan;
        $this->berita = $berita;
    }

    /**
     * GET / — Home page
     */
    public function index()
    {
        $stats = $this->ckan->siteStats();
        $organizations = $this->ckan->organizationList();
        $recentDatasets = $this->ckan->getDatasets(6, 0);

        $beritaList = $this->berita->latest(3);
        $infografisList = Publication::published()->infografis()->orderBy('published_at', 'desc')->take(6)->get();

        return view('home', compact('stats', 'organizations', 'recentDatasets', 'beritaList', 'infografisList'));
    }

    /**
     * GET /instansi — Organizations list
     */
    public function organizations()
    {
        $organizations = $this->ckan->organizationList();

        return view('organizations', compact('organizations'));
    }

    /**
     * GET /dataset — Search/list datasets
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '*');
        $page = max(1, (int) $request->input('page', 1));
        $rows = 12;
        $start = ($page - 1) * $rows;

        $result = $this->ckan->searchDatasets($query, $rows, $start);

        $datasets = $result['results'] ?? [];
        $totalCount = $result['count'] ?? 0;
        $totalPages = (int) ceil($totalCount / $rows);

        return view('datasets.search', compact('datasets', 'query', 'page', 'totalPages', 'totalCount', 'rows'));
    }

    /**
     * GET /dataset/{id} — Dataset detail
     */
    public function datasetShow(string $id)
    {
        $dataset = $this->ckan->packageShow($id);

        if (!$dataset) {
            abort(404);
        }

        return view('datasets.show', compact('dataset'));
    }

    /**
     * GET /resource/{id} — Resource detail
     */
    public function resourceShow(string $id)
    {
        $resource = $this->ckan->resourceShow($id);

        if (!$resource) {
            abort(404);
        }

        $views = $this->ckan->resourceViewList($id);

        $dataset = null;
        if (!empty($resource['package_id'])) {
            $dataset = $this->ckan->packageShow($resource['package_id']);
        }

        $preview = $this->ckan->datastoreSearch($id, 50);

        return view('resources.show', compact('resource', 'views', 'dataset', 'preview'));
    }

    /**
     * GET /publikasi — Publikasi listing page
     */
    public function publikasi(Request $request)
    {
        $type = $request->input('type');

        if ($type === 'berita') {
            $publications = $this->berita->list(max(1, (int) $request->input('page', 1)), 12);

            return view('publikasi', compact('publications', 'type'));
        }

        $query = Publication::published()->orderBy('published_at', 'desc');

        if ($type === 'infografis') {
            $query->infografis();
            $publications = $query->simplePaginate(12);
        } else {
            $apiBerita = collect($this->berita->latest(6));
            $infografis = $query->infografis()->take(6)->get();

            $publications = $apiBerita
                ->concat($infografis)
                ->sortByDesc(fn($item) => data_get($item, 'published_at'))
                ->values();
        }

        return view('publikasi', compact('publications', 'type'));
    }

    /**
     * GET /publikasi/{slug} — Single publication
     */
    public function publikasiShow(string $slug)
    {
        $publication = $this->berita->detail($slug)
            ?? Publication::published()->where('slug', $slug)->firstOrFail();

        return view('publikasi-show', compact('publication'));
    }

    /**
     * GET /publikasi/produk-statistik-opd — PDF publication files.
     */
    public function produkStatistikOpd()
    {
        $files = collect(Storage::disk('public')->files('produk-statistik-opd'))
            ->filter(fn(string $path) => Str::lower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf')
            ->map(function (string $path) {
                $filename = pathinfo($path, PATHINFO_FILENAME);

                return [
                    'title' => Str::of($filename)->replace(['-', '_'], ' ')->title()->toString(),
                    'date' => Storage::disk('public')->lastModified($path),
                    'size' => Storage::disk('public')->size($path),
                    'url' => Storage::url($path),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('produk-statistik-opd', compact('files'));
    }

    /**
     * GET /tentang — About page
     */
    public function tentang()
    {
        return view('tentang');
    }

    /**
     * GET /halaman/{slug} — Custom page
     */
    public function pageShow(string $slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('page-show', compact('page'));
    }
}

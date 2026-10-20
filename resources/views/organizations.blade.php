@extends('layouts.app')

@section('title', 'Instansi Perangkat Daerah — Satu Data Muara Enim')

@push('styles')
<style>
    .org-section {
        padding: 1.5rem 0 5rem;
    }

    .search-form-hero {
        max-width: 600px;
        margin: 1.75rem auto 0;
        display: flex;
        gap: 0;
        background: #fff;
        border: 1.5px solid var(--border-warm);
        border-radius: var(--radius);
        padding: 0.3rem;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .search-form-hero:focus-within {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-glow);
    }

    .search-form-hero input {
        flex: 1;
        border: none;
        padding: 0.8rem 1.1rem;
        font-size: 0.9rem;
        background: transparent;
        color: var(--text-body);
        outline: none;
        font-family: inherit;
    }

    .search-form-hero input::placeholder {
        color: var(--text-muted);
    }

    .search-form-hero button {
        padding: 0.7rem 1.5rem;
        background: var(--navy);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.84rem;
        cursor: pointer;
        transition: background 0.25s ease;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }

    .search-form-hero button:hover {
        background: var(--navy-light);
    }

    .results-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .results-info strong {
        color: var(--text-heading);
        font-weight: 700;
    }

    .stats-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--gold-muted);
        color: var(--gold);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 1.1rem;
        border-radius: var(--radius-full);
        margin: 1.25rem auto 0;
        letter-spacing: 0.01em;
    }

    .page-hero .stats-pill {
        display: inline-flex;
        margin-top: 1rem;
    }

    .org-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .org-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.75rem 1.25rem;
        text-align: center;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .org-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .org-card .org-avatar {
        width: 58px;
        height: 58px;
        border-radius: var(--radius-sm);
        background: var(--navy);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--gold-light);
        margin-bottom: 1rem;
        flex-shrink: 0;
        overflow: hidden;
        border: 1px solid var(--border-light);
    }

    .org-card .org-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .org-card .org-avatar .org-avatar-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .org-card h3 {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
        line-height: 1.35;
        word-break: break-word;
    }

    .org-card .org-desc {
        font-size: 0.8rem;
        color: var(--text-secondary);
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.95rem;
    }

    .org-card .dataset-count {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        padding: 0.28rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.73rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
    }

    .org-card .org-link {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gold);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: auto;
        transition: gap 0.25s ease;
    }

    .org-card .org-link:hover {
        gap: 0.6rem;
    }

    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: var(--text-muted);
    }

    .empty-state .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
    }

    .empty-state p {
        font-size: 0.88rem;
        margin-bottom: 1.25rem;
    }

    .empty-state a {
        color: var(--gold);
        text-decoration: none;
        font-weight: 600;
    }

    .empty-state a:hover {
        text-decoration: underline;
    }

    @media (max-width: 960px) {
        .org-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .org-grid {
            grid-template-columns: 1fr;
        }

        .search-form-hero {
            flex-direction: column;
        }

        .results-info {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container">
        <h1>Instansi Perangkat Daerah</h1>
        @if(!empty($query))
        <p>Hasil pencarian untuk: <strong>"{{ $query }}"</strong></p>
        @else
        <p>Daftar instansi pemerintah yang mempublikasikan data di portal Satu Data Kabupaten Muara Enim</p>
        @endif

        <form action="/instansi" method="GET" class="search-form-hero">
            <input type="text" name="q" value="{{ $query }}" placeholder="Cari instansi atau perangkat daerah..." />
            <button type="submit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                Cari
            </button>
        </form>
    </div>
</section>

<section class="org-section">
    <div class="container">
        <div class="results-info">
            <div>
                Ditemukan <strong>{{ $totalCount }}</strong> instansi
                @if(!empty($query)) untuk "<strong>{{ $query }}</strong>"@endif
            </div>

            @if(!empty($query))
            <a href="/instansi" class="badge" style="background:var(--gold-muted); color:var(--gold); text-decoration:none; padding:0.35rem 0.75rem; font-size:0.75rem;">
                ✕ Tampilkan semua instansi
            </a>
            @endif
        </div>

        <div class="org-grid">
            @forelse($organizations as $org)
            @php
            $title = !empty(trim($org['title'] ?? ''))
                ? trim($org['title'])
                : (!empty(trim($org['display_name'] ?? ''))
                    ? trim($org['display_name'])
                    : (!empty(trim($org['name'] ?? ''))
                        ? ucwords(str_replace(['-', '_'], ' ', $org['name']))
                        : 'Instansi Pemerintah Kabupaten Muara Enim'));

            $words = array_values(array_filter(explode(' ', $title)));
            $initials = strtoupper(
                mb_substr($words[0] ?? 'O', 0, 1) .
                (isset($words[1]) ? mb_substr($words[1], 0, 1) : mb_substr($words[0] ?? 'O', 1, 1))
            );

            $rawImg = $org['image_url'] ?? null;
            $orgImg = null;
            if (!empty($rawImg)) {
                $orgImg = (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://'))
                    ? $rawImg
                    : 'https://opendata.muaraenimkab.go.id/uploads/group/' . $rawImg;
            }

            $desc = !empty(trim($org['description'] ?? ''))
                ? trim($org['description'])
                : 'Organisasi Perangkat Daerah yang mempublikasikan data terbuka resmi di portal Satu Data Kabupaten Muara Enim.';

            $pkgCount = (int) ($org['package_count'] ?? 0);
            $orgSlug = $org['name'] ?? '';
            @endphp
            <div class="org-card">
                <div class="org-avatar">
                    @if(!empty($orgImg))
                    <img src="{{ $orgImg }}" alt="{{ $title }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                    <span class="org-avatar-fallback" style="display:none;">{{ $initials }}</span>
                    @else
                    <span class="org-avatar-fallback">{{ $initials }}</span>
                    @endif
                </div>

                <h3>{{ $title }}</h3>
                <p class="org-desc">{{ $desc }}</p>
                <span class="dataset-count">📦 {{ $pkgCount }} dataset</span>

                @if(!empty($orgSlug))
                <a href="{{ route('datasets.search', ['organization' => $orgSlug]) }}" class="org-link">
                    Lihat Dataset <span>→</span>
                </a>
                @else
                <a href="{{ route('datasets.search', ['q' => $title]) }}" class="org-link">
                    Lihat Dataset <span>→</span>
                </a>
                @endif
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-icon">🏢</div>
                <h3>Tidak ada instansi ditemukan</h3>
                <p>Tidak ditemukan instansi dengan kata kunci <strong>"{{ $query }}"</strong></p>
                <a href="/instansi">← Kembali ke semua instansi</a>
            </div>
            @endforelse
        </div>

        @if($totalPages > 1)
        <div class="pagination">
            @if($page > 1)
            <a href="?{{ http_build_query(array_filter(['q' => $query, 'page' => $page - 1])) }}" class="page-btn">← Sebelumnya</a>
            @endif

            <span class="page-info">Halaman {{ $page }} dari {{ $totalPages }}</span>

            @if($page < $totalPages)
            <a href="?{{ http_build_query(array_filter(['q' => $query, 'page' => $page + 1])) }}" class="page-btn">Selanjutnya →</a>
            @endif
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.gsap) {
            return;
        }

        if (window.ScrollTrigger) {
            gsap.registerPlugin(ScrollTrigger);
        }

        gsap.from('.page-hero', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });

        gsap.from('.org-card', {
            opacity: 0,
            y: 30,
            duration: 0.45,
            stagger: 0.04,
            ease: 'power2.out',
            clearProps: 'opacity,transform'
        });
    });
</script>
@endpush
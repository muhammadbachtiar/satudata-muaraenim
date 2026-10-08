@extends('layouts.app')

@section('title', 'Dataset — Satu Data Muara Enim')

@push('styles')
<style>
    .search-section {
        padding: 2.5rem 0 4rem;
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

    .dataset-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .dataset-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        transition: var(--transition);
        position: relative;
        text-decoration: none;
        overflow: hidden;
    }

    .dataset-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--gold);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .dataset-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .dataset-card:hover::after {
        transform: scaleX(1);
    }

    .dataset-card .org-name {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--gold);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .dataset-card .formats {
        display: flex;
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .dataset-card h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.4;
    }

    .dataset-card h3 a {
        text-decoration: none;
        color: inherit;
        transition: var(--transition-fast);
    }

    .dataset-card h3 a:hover {
        color: var(--gold);
    }

    .dataset-card .notes {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .dataset-card .meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: auto;
        padding-top: 0.65rem;
        border-top: 1px solid var(--border-light);
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .dataset-card .meta span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: var(--text-muted);
    }

    .empty-state .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.4;
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

    @media (max-width: 768px) {
        .dataset-grid {
            grid-template-columns: 1fr;
        }

        .results-info {
            flex-direction: column;
            gap: 0.4rem;
            align-items: flex-start;
        }

        .search-form-hero {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container">
        <h1>Dataset</h1>
        @if($query !== '*')
        <p>Hasil pencarian untuk: <strong>"{{ $query }}"</strong></p>
        @else
        <p>Jelajahi seluruh dataset yang tersedia di portal Satu Data Kabupaten Muara Enim</p>
        @endif

        <form action="/dataset" method="GET" class="search-form-hero">
            <input type="text" name="q" value="{{ $query === '*' ? '' : $query }}" placeholder="Cari dataset..." />
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

<section class="search-section">
    <div class="container">
        <div class="results-info">
            Ditemukan <strong>{{ $totalCount }}</strong> dataset
            @if($query !== '*') untuk "<strong>{{ $query }}</strong>"@endif
        </div>

        <div class="dataset-grid">
            @forelse($datasets as $ds)
            <a href="{{ route('datasets.show', $ds['name']) }}" class="dataset-card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem;">
                    <span class="org-name">{{ $ds['organization']['title'] ?? '' }}</span>
                    <div class="formats">
                        @foreach(array_unique(array_column($ds['resources'] ?? [], 'format')) as $fmt)
                        @php
                        $fmtLower = strtolower($fmt ?: 'n/a');
                        $fmtClass = in_array($fmtLower, ['csv','pdf','xls','xlsx','json','xml']) ? $fmtLower :
                        'default';
                        @endphp
                        <span class="format-badge format-{{ $fmtClass }}">{{ $fmt ?: 'N/A' }}</span>
                        @endforeach
                    </div>
                </div>
                <h3>{{ $ds['title'] }}</h3>
                <p class="notes">{{ Str::limit($ds['notes'] ?? 'Tidak ada deskripsi', 150) }}</p>
                <div class="meta">
                    <span>📎 {{ $ds['num_resources'] ?? 0 }} resource</span>
                    <span>🕒 {{ \Carbon\Carbon::parse($ds['metadata_modified'])->diffForHumans() }}</span>
                </div>
            </a>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-icon">📂</div>
                <h3>Tidak ada dataset ditemukan</h3>
                <p>Coba gunakan kata kunci yang berbeda</p>
                <a href="/dataset">← Kembali ke semua dataset</a>
            </div>
            @endforelse
        </div>

        @if($totalPages > 1)
        <div class="pagination">
            @if($page > 1)
            <a href="?q={{ urlencode($query) }}&page={{ $page - 1 }}" class="page-btn">← Sebelumnya</a>
            @endif
            <span class="page-info">Halaman {{ $page }} dari {{ $totalPages }}</span>
            @if($page < $totalPages) <a href="?q={{ urlencode($query) }}&page={{ $page + 1 }}" class="page-btn">
                Selanjutnya →</a>
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

        gsap.from('.page-hero', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });
        gsap.from('.dataset-card', {
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
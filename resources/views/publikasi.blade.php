@extends('layouts.app')

@section('title', 'Publikasi — Satu Data Muara Enim')

@push('styles')
<style>
    .publikasi-content {
        padding: 2rem 0 5rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.45rem 1.1rem;
        font-size: 0.82rem;
        font-weight: 600;
        border-radius: var(--radius-full);
        border: 1.5px solid var(--border-light);
        background: var(--bg-card);
        color: var(--text-secondary);
        text-decoration: none;
        transition: var(--transition);
    }

    .filter-tab:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-glow);
    }

    .filter-tab.active {
        background: var(--navy);
        color: #fff;
        border-color: var(--navy);
    }

    .pub-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .pub-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        overflow: hidden;
        transition: var(--transition);
        text-decoration: none;
        display: flex;
        flex-direction: column;
    }

    .pub-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .pub-card-img-wrap {
        position: relative;
        overflow: hidden;
    }

    .pub-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .pub-card:hover .pub-card-img {
        transform: scale(1.04);
    }

    .pub-card-type {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 0.2rem 0.65rem;
        font-size: 0.65rem;
        font-weight: 700;
        border-radius: var(--radius-full);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .pub-card-type.berita {
        background: rgba(184, 151, 58, 0.9);
        color: #fff;
    }

    .pub-card-type.infografis {
        background: rgba(26, 122, 109, 0.9);
        color: #fff;
    }

    .pub-card-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .pub-card-date {
        font-size: 0.68rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .pub-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.4;
        margin-bottom: 0.4rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pub-card-excerpt {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .pub-card-readmore {
        margin-top: auto;
        padding-top: 0.65rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--gold);
        display: flex;
        align-items: center;
        gap: 0.35rem;
        transition: gap 0.25s;
    }

    .pub-card:hover .pub-card-readmore {
        gap: 0.6rem;
    }

    .pub-card-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--bg-warm), var(--bg-cream));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        opacity: 0.4;
    }

    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        grid-column: 1 / -1;
    }

    .empty-state .icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.35;
    }

    .empty-state h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
    }

    .empty-state p {
        font-size: 0.88rem;
        color: var(--text-muted);
    }

    @media (max-width: 960px) {
        .pub-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .pub-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container">
        <h1>Publikasi</h1>
        <p>Infografis dan berita seputar data dan pembangunan Kabupaten Muara Enim</p>
    </div>
</section>

<section class="publikasi-content">
    <div class="container">
        <div class="filter-tabs">
            <a href="/publikasi" class="filter-tab {{ !$type ? 'active' : '' }}">Semua</a>
            <a href="/publikasi?type=berita" class="filter-tab {{ $type === 'berita' ? 'active' : '' }}">📰 Berita</a>
            <a href="/publikasi?type=infografis" class="filter-tab {{ $type === 'infografis' ? 'active' : '' }}">📊
                Infografis</a>
        </div>

        <div class="pub-grid">
            @forelse($publications as $pub)
            @php
            $pubTitle = data_get($pub, 'title');
            $pubSlug = data_get($pub, 'slug');
            $pubType = data_get($pub, 'type', 'berita');
            $pubImage = data_get($pub, 'image_url');
            $pubDate = data_get($pub, 'published_at');
            $pubBody = data_get($pub, 'body', '');
            $pubExcerpt = data_get($pub, 'excerpt') ?: Str::limit(strip_tags($pubBody), 120);
            @endphp
            <a href="{{ route('publikasi.show', $pubSlug) }}" class="pub-card">
                <div class="pub-card-img-wrap">
                    @if($pubImage)
                    <img src="{{ $pubImage }}" alt="{{ $pubTitle }}" class="pub-card-img">
                    @else
                    <div class="pub-card-placeholder">{{ $pubType === 'berita' ? '📰' : '📊' }}</div>
                    @endif
                    <span class="pub-card-type {{ $pubType }}">{{ $pubType }}</span>
                </div>
                <div class="pub-card-body">
                    <div class="pub-card-date">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <path d="M16 2v4" />
                            <path d="M8 2v4" />
                            <path d="M3 10h18" />
                        </svg>
                        {{ $pubDate?->format('d M Y') }}
                    </div>
                    <h3 class="pub-card-title">{{ $pubTitle }}</h3>
                    <p class="pub-card-excerpt">{{ $pubExcerpt }}</p>
                    <div class="pub-card-readmore">
                        Selengkapnya
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
            @empty
            <div class="empty-state">
                <div class="icon">📰</div>
                <h3>Belum Ada Publikasi</h3>
                <p>Publikasi akan ditampilkan di sini setelah ditambahkan melalui admin panel.</p>
            </div>
            @endforelse
        </div>

        @if(method_exists($publications, 'hasPages') && $publications->hasPages())
        <div class="pagination">
            @if($publications->previousPageUrl())
            <a href="{{ $publications->previousPageUrl() }}" class="page-btn">← Sebelumnya</a>
            @endif
            @if($publications->nextPageUrl())
            <a href="{{ $publications->nextPageUrl() }}" class="page-btn">Selanjutnya →</a>
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
        gsap.from('.pub-card', {
            opacity: 0,
            y: 24,
            duration: 0.45,
            stagger: 0.04,
            ease: 'power2.out',
            delay: 0.15,
            clearProps: 'opacity,transform'
        });
    });
</script>
@endpush
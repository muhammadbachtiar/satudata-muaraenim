@extends('layouts.app')

@php
$pubTitle = data_get($publication, 'title');
$pubType = data_get($publication, 'type', 'berita');
$pubBody = data_get($publication, 'body', '');
$pubImage = data_get($publication, 'image_url');
$pubDate = data_get($publication, 'published_at');
@endphp

@section('title', $pubTitle . ' — Satu Data Muara Enim')

@push('styles')
<style>
    .pub-detail {
        padding: 2rem 0 5rem;
    }

    .pub-detail-inner {
        max-width: 760px;
        margin: 0 auto;
    }

    .pub-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-decoration: none;
        margin-bottom: 1.75rem;
        transition: var(--transition);
    }

    .pub-back:hover {
        color: var(--gold);
        gap: 0.7rem;
    }

    .pub-detail-type {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.7rem;
        font-size: 0.68rem;
        font-weight: 700;
        border-radius: var(--radius-full);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.85rem;
    }

    .pub-detail-type.berita {
        background: var(--gold-muted);
        color: var(--gold);
    }

    .pub-detail-type.infografis {
        background: var(--teal-light);
        color: var(--teal);
    }

    .pub-detail-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.4rem, 3.5vw, 2.15rem);
        font-weight: 700;
        color: var(--text-heading);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-bottom: 0.85rem;
    }

    .pub-detail-date {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .pub-detail-img {
        width: 100%;
        border-radius: var(--radius);
        margin-bottom: 1.75rem;
        border: 1px solid var(--border-light);
    }

    .pub-detail-body {
        font-size: 0.95rem;
        line-height: 1.85;
        color: var(--text-body);
    }

    .pub-detail-body h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 2rem 0 0.85rem;
    }

    .pub-detail-body h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 1.5rem 0 0.65rem;
    }

    .pub-detail-body p {
        margin-bottom: 1.15rem;
    }

    .pub-detail-body ul,
    .pub-detail-body ol {
        margin-bottom: 1.15rem;
        padding-left: 1.5rem;
    }

    .pub-detail-body li {
        margin-bottom: 0.4rem;
    }

    .pub-detail-body img {
        border-radius: var(--radius-sm);
        margin: 1rem 0;
    }

    .pub-detail-body blockquote {
        border-left: 3px solid var(--gold);
        padding: 0.85rem 1.35rem;
        background: var(--gold-glow);
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        margin: 1.5rem 0;
        color: var(--text-secondary);
        font-style: italic;
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container">
        <div class="pub-detail-inner" style="text-align:left;">
            <a href="/publikasi" class="pub-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Kembali ke Publikasi
            </a>
        </div>
    </div>
</section>

<section class="pub-detail">
    <div class="container">
        <div class="pub-detail-inner">
            <span class="pub-detail-type {{ $pubType }}">
                {{ $pubType === 'berita' ? '📰 Berita' : '📊 Infografis' }}
            </span>

            <h1 class="pub-detail-title">{{ $pubTitle }}</h1>

            <div class="pub-detail-date">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                    <path d="M16 2v4" />
                    <path d="M8 2v4" />
                    <path d="M3 10h18" />
                </svg>
                {{ $pubDate?->translatedFormat('d F Y') }}
            </div>

            @if($pubImage)
            <img src="{{ $pubImage }}" alt="{{ $pubTitle }}" class="pub-detail-img">
            @endif

            <div class="pub-detail-body">
                {!! $pubBody !!}
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    gsap.from('.pub-detail-inner', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });
</script>
@endpush
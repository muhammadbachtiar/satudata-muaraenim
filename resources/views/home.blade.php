@extends('layouts.app')

@section('title', 'Satu Data Kabupaten Muara Enim — Portal Data Terbuka')

@push('styles')
<style>
    /* ════════════════════════════════════════
       HOME — Premium Government Portal
       ════════════════════════════════════════ */

    /* ── Hero ── */
    .hero {
        position: relative;
        min-height: calc(100vh - 68px);
        display: block;
        overflow: hidden;
        background: #f5f7f9;
    }

    .hero-bg {
        position: relative;
        z-index: 1;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: #d9eef4;
    }

    .hero-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
        opacity: 1;
    }

    .hero-bg::after {
        content: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: none;
        margin: 0 auto;
        padding: 0;
        display: block;
    }

    .hero-text {
        display: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--gold-light);
        background: rgba(184, 151, 58, 0.12);
        border: 1px solid rgba(184, 151, 58, 0.2);
        border-radius: var(--radius-full);
        margin-bottom: 1.75rem;
        opacity: 0;
        transform: translateY(16px);
    }

    .hero-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--gold);
        animation: pulseDot 2s ease-in-out infinite;
    }

    @keyframes pulseDot {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.4;
            transform: scale(0.7);
        }
    }

    .hero h1 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2.25rem, 5vw, 3.5rem);
        font-weight: 700;
        line-height: 1.12;
        letter-spacing: -0.02em;
        color: #fff;
        margin-bottom: 1.25rem;
        opacity: 0;
        transform: translateY(24px);
    }

    .hero h1 .hero-highlight {
        color: var(--gold-light);
    }

    .hero-desc {
        font-size: 1rem;
        color: rgba(232, 228, 221, 0.7);
        line-height: 1.8;
        max-width: 460px;
        margin-bottom: 2rem;
        opacity: 0;
        transform: translateY(16px);
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        opacity: 0;
        transform: translateY(16px);
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 28px;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: all 0.25s ease;
        line-height: 1;
        letter-spacing: 0.01em;
    }

    .hero-btn--primary {
        background: var(--gold);
        color: var(--navy-deep);
    }

    .hero-btn--primary:hover {
        background: var(--gold-light);
        box-shadow: 0 4px 16px rgba(184, 151, 58, 0.3);
        transform: translateY(-1px);
    }

    .hero-btn--ghost {
        color: rgba(232, 228, 221, 0.8);
        border: 1.5px solid rgba(232, 228, 221, 0.2);
    }

    .hero-btn--ghost:hover {
        color: #fff;
        border-color: rgba(232, 228, 221, 0.4);
        background: rgba(255, 255, 255, 0.05);
    }

    .hero-btn .arrow {
        transition: transform 0.25s ease;
    }

    .hero-btn:hover .arrow {
        transform: translateX(2px);
    }

    /* Hero stats panel */
    .hero-stats-panel {
        display: none;
    }

    .hero-stat-card {
        background: rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius);
        padding: 1.5rem;
        text-align: center;
    }

    .hero-stat-card__num {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .hero-stat-card__label {
        font-size: 0.7rem;
        font-weight: 500;
        color: rgba(232, 228, 221, 0.5);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .hero-stat-card--accent {
        background: rgba(184, 151, 58, 0.12);
        border-color: rgba(184, 151, 58, 0.2);
    }

    .hero-stat-card--accent .hero-stat-card__num {
        color: var(--gold-light);
    }

    /* ── Section Commons ── */
    .home-section {
        padding: 5rem 0;
        position: relative;
    }

    .home-section--alt {
        background: var(--bg-cream);
    }

    .section-header {
        margin-bottom: 3rem;
    }

    .section-header--center {
        text-align: center;
    }

    .section-header--center .section-desc {
        margin-left: auto;
        margin-right: auto;
    }

    .section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.65rem;
    }

    .section-eyebrow .line {
        display: inline-block;
        width: 28px;
        height: 1.5px;
        background: var(--gold);
        border-radius: 1px;
    }

    .section-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--gold);
        text-decoration: none;
        transition: gap 0.25s ease;
        margin-top: 0.75rem;
    }

    .section-link:hover {
        gap: 10px;
    }

    .section-link svg {
        width: 15px;
        height: 15px;
        transition: transform 0.25s ease;
    }

    .section-link:hover svg {
        transform: translateX(2px);
    }

    .section-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    .section-header-row .section-header {
        margin-bottom: 0;
    }

    /* ── Search ── */
    .search-section {
        background: var(--bg-cream);
        border-bottom: 1px solid var(--border-light);
    }

    .search-section .container {
        padding-top: 3.5rem;
        padding-bottom: 3.5rem;
    }

    .search-wrap {
        max-width: 640px;
        margin: 0 auto;
        text-align: center;
    }

    .search-wrap h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.15rem, 2.5vw, 1.5rem);
        font-weight: 700;
        color: var(--text-heading);
        letter-spacing: -0.01em;
        margin-bottom: 0.4rem;
    }

    .search-wrap p {
        font-size: 0.88rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .search-form {
        display: flex;
        gap: 0;
        max-width: 540px;
        margin: 0 auto;
        background: #fff;
        border: 1.5px solid var(--border-warm);
        border-radius: var(--radius);
        padding: 0.3rem;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .search-form:focus-within {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-glow);
    }

    .search-input {
        flex: 1;
        padding: 12px 16px;
        font-size: 0.88rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-body);
        background: transparent;
        border: none;
        outline: none;
    }

    .search-input::placeholder {
        color: var(--text-muted);
    }

    .search-btn {
        padding: 10px 22px;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #fff;
        background: var(--navy);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: background 0.25s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .search-btn:hover {
        background: var(--navy-light);
    }

    /* ── Dataset Cards ── */
    .dataset-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .dataset-card {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.5rem;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
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

    .dataset-card__org {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.5rem;
    }

    .dataset-card__title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .dataset-card__excerpt {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .dataset-card__meta {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-light);
    }

    .dataset-card__format {
        display: inline-flex;
        align-items: center;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 3px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .dataset-card__format--csv {
        background: #dcfce7;
        color: #15803d;
    }

    .dataset-card__format--pdf {
        background: #fee2e2;
        color: #b91c1c;
    }

    .dataset-card__format--xls,
    .dataset-card__format--xlsx {
        background: #ccfbf1;
        color: #0f766e;
    }

    .dataset-card__format--json {
        background: #fef3c7;
        color: #b45309;
    }

    .dataset-card__format--xml {
        background: #e0e7ff;
        color: #4338ca;
    }

    .dataset-card__format--default {
        background: var(--bg-warm);
        color: var(--text-secondary);
    }

    .dataset-card__resources {
        margin-left: auto;
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ── Berita Cards ── */
    .berita-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .berita-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        overflow: hidden;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .berita-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .berita-card__img-wrap {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/10;
        background: var(--bg-warm);
    }

    .berita-card__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .berita-card:hover .berita-card__img {
        transform: scale(1.04);
    }

    .berita-card__img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--bg-warm), var(--bg-cream));
    }

    .berita-card__img-placeholder svg {
        width: 36px;
        height: 36px;
        color: var(--text-muted);
        opacity: 0.3;
    }

    .berita-card__type-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: var(--radius-xs);
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(6px);
        color: var(--gold);
    }

    .berita-card__body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .berita-card__date {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.4rem;
    }

    .berita-card__title {
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

    .berita-card__excerpt {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .berita-card__read {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--gold);
        margin-top: 0.75rem;
        text-decoration: none;
        transition: gap 0.25s ease;
    }

    .berita-card:hover .berita-card__read {
        gap: 8px;
    }

    /* ── Infografis ── */
    .infografis-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .infografis-card {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        overflow: hidden;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .infografis-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .infografis-card__img-wrap {
        position: relative;
        overflow: hidden;
        aspect-ratio: 3/4;
        background: var(--bg-warm);
    }

    .infografis-card__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .infografis-card:hover .infografis-card__img {
        transform: scale(1.03);
    }

    .infografis-card__img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--bg-cream), var(--bg-warm));
    }

    .infografis-card__img-placeholder svg {
        width: 36px;
        height: 36px;
        color: var(--gold);
        opacity: 0.3;
    }

    .infografis-card__img-placeholder span {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .infografis-card__overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1.25rem;
        background: linear-gradient(to top, rgba(10, 22, 40, 0.85), transparent);
    }

    .infografis-card__title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #f1f5f9;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .infografis-card__date {
        font-size: 0.65rem;
        color: rgba(203, 213, 225, 0.6);
        margin-top: 4px;
        font-weight: 500;
    }

    /* ── Org Cards ── */
    .org-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .org-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.5rem 1.25rem;
        text-align: center;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.3s ease;
    }

    .org-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .org-card__avatar {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        background: var(--navy);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-light);
        font-weight: 800;
        font-size: 0.85rem;
        margin-bottom: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .org-card__name {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-heading);
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.35rem;
    }

    .org-card__count {
        font-size: 0.68rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ── CTA ── */
    .cta-section {
        text-align: center;
        padding: 5rem 2rem;
        background: var(--navy-deep);
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
    }

    .cta-section h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
    }

    .cta-section p {
        font-size: 0.95rem;
        color: rgba(232, 228, 221, 0.6);
        max-width: 440px;
        margin: 0 auto 2rem;
        line-height: 1.7;
    }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
        color: var(--text-muted);
    }

    .empty-state svg {
        width: 40px;
        height: 40px;
        margin-bottom: 0.75rem;
        opacity: 0.25;
    }

    .empty-state p {
        font-size: 0.88rem;
        font-weight: 500;
    }

    /* ── GSAP ── */
    .gs-fade {
        opacity: 0;
        transform: translateY(24px);
    }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .hero-content {
            padding: 0;
        }

        .dataset-grid,
        .infografis-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hero {
            min-height: auto;
        }

        .hero-content {
            padding: 0;
        }

        .home-section {
            padding: 3.5rem 0;
        }

        .dataset-grid,
        .berita-grid {
            grid-template-columns: 1fr;
        }

        .infografis-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .org-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .section-header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .search-form {
            flex-direction: column;
        }

        .search-btn {
            justify-content: center;
        }

        .hero-bg {
            aspect-ratio: 16 / 9;
        }
    }

    @media (max-width: 480px) {
        .org-grid {
            grid-template-columns: 1fr;
        }

        .infografis-grid {
            grid-template-columns: 1fr;
        }

        .hero-bg {
            aspect-ratio: 16 / 10;
            min-height: 320px;
        }

        .hero-bg img {
            object-position: center top;
        }
    }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════
     HERO
     ════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-bg">
        <img src="/header.png" alt="Muara Enim">
    </div>

    <div class="hero-content">
        <div class="hero-text">
            <div class="hero-badge">
                <span class="dot"></span>
                Portal Data Terbuka Kabupaten Muara Enim
            </div>

            <h1>
                Satu Data untuk<br>
                <span class="hero-highlight">Muara Enim Maju</span>
            </h1>

            <p class="hero-desc">
                Akses data terbuka untuk mendukung transparansi, perencanaan pembangunan,
                dan inovasi berbasis data di Kabupaten Muara Enim.
            </p>

            <div class="hero-actions">
                <a href="{{ route('datasets.search') }}" class="hero-btn hero-btn--primary">
                    Jelajahi Dataset
                    <svg class="arrow" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
                <a href="{{ route('tentang') }}" class="hero-btn hero-btn--ghost">
                    Tentang Portal
                </a>
            </div>
        </div>

        <div class="hero-stats-panel">
            <div class="hero-stat-card hero-stat-card--accent">
                <div class="hero-stat-card__num">{{ number_format($stats['dataset_count'] ?? 0) }}</div>
                <div class="hero-stat-card__label">Dataset</div>
            </div>
            <div class="hero-stat-card">
                <div class="hero-stat-card__num">{{ number_format($stats['organization_count'] ?? 0) }}</div>
                <div class="hero-stat-card__label">Organisasi</div>
            </div>
            <div class="hero-stat-card">
                <div class="hero-stat-card__num">{{ number_format($recentDatasets['count'] ?? 0) }}+</div>
                <div class="hero-stat-card__label">Berkas Data</div>
            </div>
            <div class="hero-stat-card">
                @php
                $resourceCount = 0;
                foreach (($recentDatasets['results'] ?? []) as $d) {
                $resourceCount += count($d['resources'] ?? []);
                }
                @endphp
                <div class="hero-stat-card__num">{{ $resourceCount }}</div>
                <div class="hero-stat-card__label">Resource Terbaru</div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     SEARCH
     ════════════════════════════════════ --}}
<section class="search-section">
    <div class="container">
        <div class="search-wrap gs-fade">
            <h2>Cari Data yang Anda Butuhkan</h2>
            <p>Telusuri data dari berbagai instansi pemerintah Kabupaten Muara Enim</p>

            <form action="{{ route('datasets.search') }}" method="GET" class="search-form">
                <input type="text" name="q" class="search-input" placeholder="Contoh: APBD, kependudukan, pendidikan..."
                    autocomplete="off">
                <button type="submit" class="search-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    Cari
                </button>
            </form>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     DATASET TERBARU
     ════════════════════════════════════ --}}
<section class="home-section">
    <div class="container">
        <div class="section-header-row gs-fade">
            <div class="section-header">
                <div class="section-eyebrow">
                    <span class="line"></span>
                    Dataset Terbaru
                </div>
                <h2 class="section-title">Data Terbuka untuk Semua</h2>
                <p class="section-desc">
                    Akses dataset terbaru dari berbagai instansi pemerintah Kabupaten Muara Enim.
                </p>
            </div>
            <a href="{{ route('datasets.search') }}" class="section-link">
                Lihat Semua Dataset
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>

        <div class="dataset-grid">
            @forelse(($recentDatasets['results'] ?? []) as $ds)
            <a href="{{ route('datasets.show', $ds['id'] ?? $ds['name'] ?? '#') }}" class="dataset-card gs-fade">
                <div class="dataset-card__org">
                    {{ $ds['organization']['title'] ?? 'Pemerintah Kab. Muara Enim' }}
                </div>
                <div class="dataset-card__title">
                    {{ $ds['title'] ?? $ds['name'] ?? 'Untitled' }}
                </div>
                <div class="dataset-card__excerpt">
                    {{ \Illuminate\Support\Str::limit(strip_tags($ds['notes'] ?? ''), 100) ?: 'Deskripsi belum tersedia.' }}
                </div>
                <div class="dataset-card__meta">
                    @foreach(array_slice($ds['resources'] ?? [], 0, 3) as $res)
                    @php
                    $fmt = strtolower($res['format'] ?? 'data');
                    $fmtClass = match(true) {
                    in_array($fmt, ['csv']) => 'csv',
                    in_array($fmt, ['pdf']) => 'pdf',
                    in_array($fmt, ['xls', 'xlsx']) => 'xls',
                    in_array($fmt, ['json']) => 'json',
                    in_array($fmt, ['xml']) => 'xml',
                    default => 'default',
                    };
                    @endphp
                    <span class="dataset-card__format dataset-card__format--{{ $fmtClass }}">{{ $fmt }}</span>
                    @endforeach
                    <span class="dataset-card__resources">
                        {{ count($ds['resources'] ?? []) }} berkas
                    </span>
                </div>
            </a>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" />
                    <path d="M14 2v6h6" />
                    <path d="M12 18v-6" />
                    <path d="m9 15 3-3 3 3" />
                </svg>
                <p>Belum ada dataset tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     BERITA & KEGIATAN
     ════════════════════════════════════ --}}
<section class="home-section home-section--alt">
    <div class="container">
        <div class="section-header-row gs-fade">
            <div class="section-header">
                <div class="section-eyebrow">
                    <span class="line"></span>
                    Berita & Kegiatan
                </div>
                <h2 class="section-title">Kabar Terbaru</h2>
                <p class="section-desc">
                    Informasi terkini mengenai kegiatan dan perkembangan data terbuka di Kabupaten Muara Enim.
                </p>
            </div>
            <a href="{{ route('publikasi', ['type' => 'berita']) }}" class="section-link">
                Lihat Semua Berita
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>

        <div class="berita-grid">
            @forelse($beritaList ?? [] as $berita)
            @php
            $beritaTitle = data_get($berita, 'title');
            $beritaSlug = data_get($berita, 'slug');
            $beritaImage = data_get($berita, 'image_url');
            $beritaDate = data_get($berita, 'published_at');
            $beritaExcerpt = data_get($berita, 'excerpt') ?: \Illuminate\Support\Str::limit(strip_tags(data_get($berita,
            'body', '')), 120);
            @endphp
            <a href="{{ route('publikasi.show', $beritaSlug) }}" class="berita-card gs-fade">
                <div class="berita-card__img-wrap">
                    @if($beritaImage)
                    <img src="{{ $beritaImage }}" alt="{{ $beritaTitle }}" class="berita-card__img" loading="lazy">
                    @else
                    <div class="berita-card__img-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                            <circle cx="9" cy="9" r="2" />
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                        </svg>
                    </div>
                    @endif
                    <span class="berita-card__type-badge">Berita</span>
                </div>
                <div class="berita-card__body">
                    <div class="berita-card__date">
                        {{ $beritaDate?->translatedFormat('d M Y') ?? '-' }}
                    </div>
                    <div class="berita-card__title">
                        {{ $beritaTitle }}
                    </div>
                    <div class="berita-card__excerpt">
                        {{ $beritaExcerpt }}
                    </div>
                    <span class="berita-card__read">
                        Baca selengkapnya
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </span>
                </div>
            </a>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2" />
                    <path d="M18 14h-8" />
                    <path d="M15 18h-5" />
                    <path d="M10 6h8v4h-8V6Z" />
                </svg>
                <p>Belum ada berita tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     INFOGRAFIS
     ════════════════════════════════════ --}}
<section class="home-section">
    <div class="container">
        <div class="section-header-row gs-fade">
            <div class="section-header">
                <div class="section-eyebrow">
                    <span class="line"></span>
                    Infografis
                </div>
                <h2 class="section-title">Visualisasi Data</h2>
                <p class="section-desc">
                    Data yang divisualisasikan dalam bentuk infografis yang mudah dipahami oleh semua kalangan.
                </p>
            </div>
            <a href="{{ route('publikasi', ['type' => 'infografis']) }}" class="section-link">
                Lihat Semua Infografis
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>

        <div class="infografis-grid">
            @forelse($infografisList ?? [] as $info)
            <a href="{{ route('publikasi.show', $info->slug) }}" class="infografis-card gs-fade">
                <div class="infografis-card__img-wrap">
                    @if($info->image_url)
                    <img src="{{ $info->image_url }}" alt="{{ $info->title }}" class="infografis-card__img"
                        loading="lazy">
                    @else
                    <div class="infografis-card__img-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="M18 17V9" />
                            <path d="M13 17V5" />
                            <path d="M8 17v-3" />
                        </svg>
                        <span>Infografis</span>
                    </div>
                    @endif
                    <div class="infografis-card__overlay">
                        <div class="infografis-card__title">{{ $info->title }}</div>
                        <div class="infografis-card__date">{{ $info->published_at?->translatedFormat('d M Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 3v18h18" />
                    <path d="M18 17V9" />
                    <path d="M13 17V5" />
                    <path d="M8 17v-3" />
                </svg>
                <p>Belum ada infografis tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     KONTRIBUTOR / ORGANISASI
     ════════════════════════════════════ --}}
<section class="home-section home-section--alt">
    <div class="container">
        <div class="section-header-row gs-fade">
            <div class="section-header">
                <div class="section-eyebrow">
                    <span class="line"></span>
                    Kontributor Data
                </div>
                <h2 class="section-title">Instansi Penyedia Data</h2>
                <p class="section-desc">
                    Organisasi perangkat daerah yang berkontribusi menyediakan data terbuka untuk masyarakat.
                </p>
            </div>
            <a href="{{ route('organizations') }}" class="section-link">
                Lihat Semua Instansi
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>

        <div class="org-grid">
            @forelse(array_slice($organizations ?? [], 0, 8) as $org)
            <a href="{{ route('datasets.search', ['q' => $org['title'] ?? $org['display_name'] ?? '']) }}"
                class="org-card gs-fade">
                <div class="org-card__avatar">
                    @php
                    $orgName = $org['title'] ?? $org['display_name'] ?? '?';
                    $words = explode(' ', $orgName);
                    $initials = '';
                    foreach (array_slice($words, 0, 2) as $w) {
                    $initials .= mb_substr($w, 0, 1);
                    }
                    @endphp
                    {{ strtoupper($initials) }}
                </div>
                <div class="org-card__name">
                    {{ $orgName }}
                </div>
                <div class="org-card__count">
                    {{ $org['package_count'] ?? 0 }} dataset
                </div>
            </a>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <p>Belum ada data organisasi tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function() {
    gsap.registerPlugin(ScrollTrigger);

    // General sections
    document.querySelectorAll('.gs-fade').forEach(function(el) {
        gsap.to(el, {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 88%',
                once: true
            }
        });
    });

    // Batch stagger cards
    ['dataset-card', 'berita-card', 'infografis-card', 'org-card'].forEach(function(cls) {
        ScrollTrigger.batch('.' + cls + '.gs-fade', {
            onEnter: function(batch) {
                gsap.to(batch, {
                    opacity: 1,
                    y: 0,
                    duration: 0.6,
                    ease: 'power2.out',
                    stagger: 0.08
                });
            },
            start: 'top 88%',
            once: true
        });
    });
})();
</script>
@endpush
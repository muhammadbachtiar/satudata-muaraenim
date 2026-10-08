@extends('layouts.app')

@section('title', 'Instansi Perangkat Daerah — Satu Data Muara Enim')

@push('styles')
<style>
    .org-section {
        padding: 2rem 0 5rem;
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
    }

    .org-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .org-card .org-avatar {
        width: 54px;
        height: 54px;
        border-radius: var(--radius-sm);
        background: var(--navy);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        color: var(--gold-light);
        margin-bottom: 1rem;
        flex-shrink: 0;
    }

    .org-card h3 {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
        line-height: 1.3;
    }

    .org-card .org-desc {
        font-size: 0.78rem;
        color: var(--text-secondary);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.85rem;
    }

    .org-card .dataset-count {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-full);
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.65rem;
    }

    .org-card .org-link {
        font-size: 0.78rem;
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

    @media (max-width: 960px) {
        .org-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .org-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container">
        <h1>Instansi Perangkat Daerah</h1>
        <p>Daftar instansi pemerintah yang mempublikasikan data di portal Satu Data Kabupaten Muara Enim</p>
        <div class="stats-pill">
            <span style="font-weight: 800; font-size: 0.95rem;">{{ count($organizations) }}</span> organisasi terdaftar
        </div>
    </div>
</section>

<section class="org-section">
    <div class="container">
        <div class="org-grid">
            @foreach($organizations as $org)
            @php
            $name = $org['display_name'] ?? $org['title'] ?? $org['name'];
            $words = explode(' ', trim($name));
            $initials = strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) :
            mb_substr($words[0], 1, 1)));
            @endphp
            <div class="org-card">
                <div class="org-avatar">{{ $initials }}</div>
                <h3>{{ $name }}</h3>
                <p class="org-desc">{{ $org['description'] ?: 'Organisasi Perangkat Daerah Kabupaten Muara Enim' }}</p>
                <span class="dataset-count">📦 {{ $org['package_count'] ?? 0 }} dataset</span>
                <a href="/dataset?q=organization:{{ $org['name'] }}" class="org-link">Lihat Dataset <span>→</span></a>
            </div>
            @endforeach
        </div>
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
        gsap.from('.stats-pill', { opacity: 0, y: 16, duration: 0.5, delay: 0.2, ease: 'power2.out' });

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
@extends('layouts.app')

@section('title', 'Produk Statistik OPD — Satu Data Muara Enim')

@push('styles')
<style>
    .produk-section {
        padding: 2rem 0 5rem;
    }

    .produk-list {
        display: grid;
        gap: 1rem;
        max-width: 980px;
        margin: 0 auto;
    }

    .produk-card {
        display: grid;
        grid-template-columns: 96px 1fr auto;
        gap: 1.25rem;
        align-items: center;
        padding: 1.25rem;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        text-decoration: none;
        transition: var(--transition);
    }

    .produk-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .produk-icon {
        width: 96px;
        height: 112px;
        border-radius: var(--radius-sm);
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--navy);
    }

    .produk-icon svg {
        width: 54px;
        height: 54px;
    }

    .produk-meta {
        color: var(--text-secondary);
        font-size: 0.88rem;
        margin-bottom: 0.35rem;
    }

    .produk-title {
        color: var(--text-heading);
        font-size: 1.05rem;
        font-weight: 800;
        line-height: 1.4;
        margin-bottom: 0.45rem;
    }

    .produk-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.65;
    }

    .produk-download {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: var(--gold);
        font-weight: 700;
        white-space: nowrap;
    }

    .produk-empty {
        max-width: 720px;
        margin: 0 auto;
        padding: 3rem 2rem;
        text-align: center;
        background: var(--bg-card);
        border: 1px dashed var(--border-warm);
        border-radius: var(--radius);
        color: var(--text-secondary);
    }

    .produk-empty strong {
        display: block;
        color: var(--text-heading);
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .produk-card {
            grid-template-columns: 72px 1fr;
        }

        .produk-icon {
            width: 72px;
            height: 86px;
        }

        .produk-icon svg {
            width: 40px;
            height: 40px;
        }

        .produk-download {
            grid-column: 2;
        }
    }
</style>
@endpush

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>Produk Statistik OPD</h1>
        <p>Kumpulan publikasi PDF produk statistik dari perangkat daerah Kabupaten Muara Enim.</p>
    </div>
</section>

<section class="produk-section">
    <div class="container">
        @if($files->count())
        <div class="produk-list">
            @foreach($files as $file)
            <a href="{{ $file['url'] }}" target="_blank" rel="noopener" class="produk-card">
                <div class="produk-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <path d="M14 2v6h6" />
                        <path d="M9 15h6" />
                        <path d="M9 18h4" />
                    </svg>
                </div>
                <div>
                    <div class="produk-meta">
                        {{ \Carbon\Carbon::createFromTimestamp($file['date'])->translatedFormat('d F Y') }} ·
                        {{ number_format($file['size'] / 1024 / 1024, 1) }} MB
                    </div>
                    <h2 class="produk-title">{{ $file['title'] }}</h2>
                    <p class="produk-desc">Dokumen PDF Produk Statistik OPD Kabupaten Muara Enim.</p>
                </div>
                <span class="produk-download">Download PDF →</span>
            </a>
            @endforeach
        </div>
        @else
        <div class="produk-empty">
            <strong>Belum ada file PDF Produk Statistik OPD.</strong>
            Simpan file PDF ke folder <code>storage/app/public/produk-statistik-opd</code>, lalu file akan tampil
            otomatis
            di halaman ini.
        </div>
        @endif
    </div>
</section>
@endsection
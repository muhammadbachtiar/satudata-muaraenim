@extends('layouts.app')

@section('title', ($resource['name'] ?? 'Resource') . ' — Satu Data Muara Enim')

@push('styles')
<style>
    .resource-detail {
        padding: 0 0 4rem;
    }

    .format-pill-lg {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
        height: 38px;
        padding: 0 1.15rem;
        border-radius: var(--radius-xs);
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 0.85rem;
    }

    .format-pill-lg.csv {
        background: #dcfce7;
        color: #15803d;
    }

    .format-pill-lg.pdf {
        background: #fee2e2;
        color: #b91c1c;
    }

    .format-pill-lg.xls,
    .format-pill-lg.xlsx {
        background: #ccfbf1;
        color: #0f766e;
    }

    .format-pill-lg.json {
        background: #fef3c7;
        color: #b45309;
    }

    .format-pill-lg.xml {
        background: #e0e7ff;
        color: #4338ca;
    }

    .format-pill-lg.default {
        background: var(--bg-warm);
        color: var(--text-secondary);
    }

    .resource-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.4rem, 3.5vw, 1.9rem);
        font-weight: 700;
        color: var(--text-heading);
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin-bottom: 0.65rem;
    }

    .resource-desc {
        font-size: 0.92rem;
        color: var(--text-secondary);
        line-height: 1.75;
        margin-bottom: 2.25rem;
        max-width: 780px;
    }

    .info-section {
        padding: 2.5rem 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.85rem;
        margin-bottom: 2.5rem;
    }

    .info-item {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.1rem 1.25rem;
        transition: var(--transition);
    }

    .info-item:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-sm);
    }

    .info-item .info-label {
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
    }

    .info-item .info-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-heading);
        word-break: break-all;
    }

    .info-value.active-status {
        color: #16a34a;
    }

    .preview-section {
        margin-bottom: 2.5rem;
    }

    .preview-section .section-heading {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.85rem;
    }

    .preview-table-wrapper {
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        overflow-x: auto;
        background: var(--bg-card);
    }

    .preview-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }

    .preview-table thead {
        background: var(--bg-cream);
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .preview-table th {
        padding: 0.65rem 0.85rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-secondary);
        border-bottom: 2px solid var(--border-light);
        white-space: nowrap;
    }

    .preview-table td {
        padding: 0.55rem 0.85rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-body);
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .preview-table tbody tr:hover {
        background: var(--bg-cream);
    }

    .preview-table tbody tr:last-child td {
        border-bottom: none;
    }

    .preview-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0.85rem;
        font-size: 0.75rem;
        color: var(--text-muted);
        border-top: 1px solid var(--border-light);
        background: var(--bg-cream);
        border-radius: 0 0 var(--radius) var(--radius);
    }

    .preview-image {
        width: 100%;
        height: auto;
        display: block;
        border-radius: var(--radius);
        border: 1px solid var(--border-light);
    }

    .download-section {
        padding: 2rem 0;
        text-align: center;
    }

    .download-box {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.75rem;
        text-align: center;
        margin-bottom: 1.75rem;
    }

    .download-box .btn-lg {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 2.25rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
        background: var(--navy);
        border-radius: var(--radius-sm);
        border: none;
        text-decoration: none;
        transition: var(--transition);
    }

    .download-box .btn-lg:hover {
        background: var(--navy-light);
        box-shadow: 0 4px 16px rgba(15, 29, 53, 0.2);
        transform: translateY(-1px);
    }

    .download-box .download-url {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 1.25rem;
        word-break: break-all;
    }

    .parent-section {
        padding: 0 0 4rem;
    }

    .parent-label {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 0.65rem;
    }

    .parent-link-card {
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.35rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        transition: var(--transition);
        text-decoration: none;
        color: var(--gold);
        font-weight: 600;
    }

    .parent-link-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .download-box {
            padding: 1.25rem;
        }

        .parent-link-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')

@php
function formatBytesRes($bytes, $precision = 1) {
if ($bytes <= 0) return '-' ; $units=['B', 'KB' , 'MB' , 'GB' ]; $pow=floor(log($bytes) / log(1024)); return
    round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow]; } $fmt=strtolower($resource['format'] ?? '' );
    $fmtClass=in_array($fmt, ['csv','pdf','xls','xlsx','json','xml']) ? $fmt : 'default' ; @endphp <section
    class="page-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Beranda</a>
            <span>/</span>
            <a href="/dataset">Dataset</a>
            <span>/</span>
            @if($dataset)
            <a
                href="{{ route('datasets.show', $dataset['name'] ?? $dataset['id']) }}">{{ Str::limit($dataset['title'] ?? 'Dataset', 40) }}</a>
            <span>/</span>
            @endif
            <span>{{ Str::limit($resource['name'] ?? 'Resource', 40) }}</span>
        </div>

        <span class="format-pill-lg {{ $fmtClass }}">{{ $resource['format'] ?? 'FILE' }}</span>

        <h1 class="resource-title">{{ $resource['name'] ?? 'Resource' }}</h1>

        @if(!empty($resource['description']))
        <p class="resource-desc">{{ $resource['description'] }}</p>
        @endif
    </div>
    </section>

    <section class="info-section">
        <div class="container">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Format</div>
                    <div class="info-value">{{ $resource['format'] ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">MIME Type</div>
                    <div class="info-value">{{ $resource['mimetype'] ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Ukuran</div>
                    <div class="info-value">{{ formatBytesRes($resource['size'] ?? 0) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Dibuat</div>
                    <div class="info-value">
                        {{ !empty($resource['created']) ? \Carbon\Carbon::parse($resource['created'])->format('d M Y, H:i') : '-' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Terakhir Diubah</div>
                    <div class="info-value">
                        {{ !empty($resource['last_modified']) ? \Carbon\Carbon::parse($resource['last_modified'])->format('d M Y, H:i') : '-' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value {{ ($resource['state'] ?? '') === 'active' ? 'active-status' : '' }}">
                        {{ ($resource['state'] ?? '') === 'active' ? '● Active' : ucfirst($resource['state'] ?? '-') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($preview) && !empty($preview['records']))
    @php
    $fields = collect($preview['fields'])->filter(fn($f) => !str_starts_with($f['id'], '_'))->values();
    @endphp
    <section class="preview-section">
        <div class="container">
            <h2 class="section-heading">Preview</h2>
            <div class="preview-table-wrapper" style="max-height:480px; overflow-y:auto;">
                <table class="preview-table">
                    <thead>
                        <tr>
                            <th style="width:36px">#</th>
                            @foreach($fields as $field)
                            <th>{{ $field['id'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preview['records'] as $idx => $row)
                        <tr>
                            <td style="color:var(--text-muted);font-size:.72rem">{{ $idx + 1 }}</td>
                            @foreach($fields as $field)
                            <td title="{{ $row[$field['id']] ?? '' }}">{{ Str::limit($row[$field['id']] ?? '-', 80) }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="preview-meta">
                <span>Menampilkan {{ count($preview['records']) }} dari {{ number_format($preview['total']) }}
                    baris</span>
                <span>{{ strtoupper($resource['format'] ?? 'DATA') }} • DataStore API</span>
            </div>
        </div>
    </section>
    @elseif(in_array(strtolower($resource['mimetype'] ?? ''), ['image/png', 'image/jpeg', 'image/gif', 'image/webp']))
    <section class="preview-section">
        <div class="container">
            <h2 class="section-heading">Preview</h2>
            <img src="{{ $resource['url'] }}" alt="{{ $resource['name'] }}" class="preview-image" />
        </div>
    </section>
    @endif

    <section class="download-section">
        <div class="container">
            <div class="download-box">
                <a href="{{ $resource['url'] ?? '#' }}" target="_blank" class="btn-lg">⬇ Download Resource</a>
                <p class="download-url">{{ $resource['url'] ?? '' }}</p>
            </div>
        </div>
    </section>

    @if($dataset)
    <section class="parent-section">
        <div class="container">
            <p class="parent-label">Resource ini merupakan bagian dari dataset:</p>
            <a href="{{ route('datasets.show', $dataset['name'] ?? $dataset['id']) }}" class="parent-link-card">
                ← {{ $dataset['title'] ?? 'Lihat Dataset' }}
            </a>
        </div>
    </section>
    @endif

    @endsection

    @push('scripts')
    <script>
        gsap.registerPlugin(ScrollTrigger);
    gsap.from('.page-hero', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });
    gsap.from('.info-item', {
        scrollTrigger: { trigger: '.info-grid', start: 'top 88%' },
        opacity: 0, y: 24, duration: 0.4, stagger: 0.05, ease: 'power2.out'
    });
    gsap.from('.download-box', {
        scrollTrigger: { trigger: '.download-section', start: 'top 88%' },
        opacity: 0, y: 16, duration: 0.5, ease: 'power2.out'
    });
    </script>
    @endpush
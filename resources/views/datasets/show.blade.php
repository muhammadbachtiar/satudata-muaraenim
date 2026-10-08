@extends('layouts.app')

@section('title', ($dataset['title'] ?? 'Dataset') . ' — Satu Data Muara Enim')

@push('styles')
<style>
    .dataset-detail {
        padding: 0 0 4rem;
    }

    .org-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        background: var(--gold-muted);
        color: var(--gold);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.8rem;
        border-radius: var(--radius-full);
        margin-bottom: 1rem;
        letter-spacing: 0.01em;
    }

    .dataset-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.5rem, 3.5vw, 2.15rem);
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.25;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }

    .dataset-notes {
        font-size: 0.92rem;
        color: var(--text-secondary);
        line-height: 1.75;
        margin-bottom: 2rem;
        max-width: 780px;
    }

    .meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        padding: 1.15rem 0;
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 2.5rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .meta-item .meta-label {
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
    }

    .meta-item .meta-value {
        font-size: 0.85rem;
        color: var(--text-body);
        font-weight: 500;
    }

    .tags-section {
        margin-bottom: 2.5rem;
    }

    .tags-section h3 {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
        margin-bottom: 0.65rem;
    }

    .tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.8rem;
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
        transition: var(--transition-fast);
    }

    .tag:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-glow);
    }

    .resources-section h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 1.15rem;
    }

    .resource-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.35rem;
        display: flex;
        align-items: flex-start;
        gap: 1.15rem;
        transition: var(--transition);
        margin-bottom: 0.85rem;
    }

    .resource-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-md);
    }

    .resource-card .format-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .resource-card .resource-info {
        flex: 1;
        min-width: 0;
    }

    .resource-card .resource-info h4 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.3rem;
        line-height: 1.3;
    }

    .resource-card .resource-info p {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 0.4rem;
    }

    .resource-card .resource-meta {
        font-size: 0.72rem;
        color: var(--text-muted);
        display: flex;
        gap: 0.85rem;
    }

    .resource-card .resource-actions {
        display: flex;
        gap: 0.4rem;
        flex-shrink: 0;
        align-self: center;
    }

    .pill-csv {
        background: #dcfce7;
        color: #15803d;
    }

    .pill-pdf {
        background: #fee2e2;
        color: #b91c1c;
    }

    .pill-xls,
    .pill-xlsx {
        background: #ccfbf1;
        color: #0f766e;
    }

    .pill-json {
        background: #fef3c7;
        color: #b45309;
    }

    .pill-xml {
        background: #e0e7ff;
        color: #4338ca;
    }

    .pill-default {
        background: var(--bg-warm);
        color: var(--text-secondary);
    }

    .extras-section {
        margin-top: 2.5rem;
        padding-top: 1.75rem;
        border-top: 1px solid var(--border-light);
    }

    .extras-section h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.85rem;
    }

    .extras-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }

    .extras-table th {
        text-align: left;
        padding: 0.65rem 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        background: var(--bg-cream);
        border-bottom: 1px solid var(--border-light);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .extras-table td {
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-body);
    }

    .extras-table tr:last-child td {
        border-bottom: none;
    }

    .extras-table tr:hover td {
        background: var(--bg-cream);
    }

    @media (max-width: 768px) {
        .resource-card {
            flex-direction: column;
            align-items: stretch;
        }

        .resource-card .resource-actions {
            align-self: flex-start;
        }

        .meta-row {
            gap: 0.85rem;
        }
    }
</style>
@endpush

@section('content')

@php
function formatBytes($bytes, $precision = 1) {
if ($bytes <= 0) return '-' ; $units=['B', 'KB' , 'MB' , 'GB' ]; $pow=floor(log($bytes) / log(1024)); return
    round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow]; } @endphp <section class="page-hero"
    style="text-align: left;">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Beranda</a>
            <span class="separator">/</span>
            <a href="/dataset">Dataset</a>
            <span class="separator">/</span>
            <span class="current">{{ Str::limit($dataset['title'], 50) }}</span>
        </div>

        @if(!empty($dataset['organization']['title']))
        <span class="org-badge">{{ $dataset['organization']['title'] }}</span>
        @endif

        <h1 class="dataset-title">{{ $dataset['title'] }}</h1>

        @if(!empty($dataset['notes']))
        <p class="dataset-notes">{{ $dataset['notes'] }}</p>
        @endif

        <div class="meta-row">
            <div class="meta-item">
                <span class="meta-label">Dibuat</span>
                <span
                    class="meta-value">{{ \Carbon\Carbon::parse($dataset['metadata_created'])->format('d M Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Diperbarui</span>
                <span
                    class="meta-value">{{ \Carbon\Carbon::parse($dataset['metadata_modified'])->format('d M Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Lisensi</span>
                <span class="meta-value">{{ $dataset['license_title'] ?? '-' }}</span>
            </div>
        </div>
    </div>
    </section>

    <div class="dataset-detail">
        <div class="container">
            @if(!empty($dataset['tags']))
            <section class="tags-section">
                <h3>Tags</h3>
                <div class="tags-list">
                    @foreach($dataset['tags'] as $tag)
                    <span class="tag">{{ $tag['display_name'] }}</span>
                    @endforeach
                </div>
            </section>
            @endif

            <section class="resources-section">
                <h2>Resources ({{ $dataset['num_resources'] ?? 0 }})</h2>

                @foreach($dataset['resources'] ?? [] as $res)
                @php
                $fmt = strtolower($res['format'] ?? '');
                $pillClass = in_array($fmt, ['csv','pdf','xls','xlsx','json','xml']) ? $fmt : 'default';
                @endphp
                <div class="resource-card">
                    <span class="format-pill pill-{{ $pillClass }}">{{ $res['format'] ?? 'FILE' }}</span>
                    <div class="resource-info">
                        <h4>{{ $res['name'] ?? 'Resource' }}</h4>
                        @if(!empty($res['description']))
                        <p>{{ $res['description'] }}</p>
                        @endif
                        <div class="resource-meta">
                            <span>{{ formatBytes($res['size'] ?? 0) }}</span>
                            <span>{{ $res['mimetype'] ?? '' }}</span>
                        </div>
                    </div>
                    <div class="resource-actions">
                        <a href="{{ route('resources.show', $res['id']) }}" class="btn btn-outline btn-sm">Detail</a>
                        <a href="{{ $res['url'] ?? '#' }}" target="_blank" class="btn btn-primary btn-sm">Download</a>
                    </div>
                </div>
                @endforeach
            </section>

            @if(!empty($dataset['extras']))
            <section class="extras-section">
                <h3>Metadata Tambahan</h3>
                <table class="extras-table">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataset['extras'] as $extra)
                        <tr>
                            <td>{{ $extra['key'] }}</td>
                            <td>{{ $extra['value'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            @endif
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
        gsap.registerPlugin(ScrollTrigger);
    gsap.from('.page-hero', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });
    gsap.from('.resource-card', {
        scrollTrigger: { trigger: '.resources-section', start: 'top 88%' },
        opacity: 0, y: 24, duration: 0.45, stagger: 0.07, ease: 'power2.out'
    });
    </script>
    @endpush
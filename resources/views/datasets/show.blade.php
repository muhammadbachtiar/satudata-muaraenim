@extends('layouts.app')

@section('title', ($dataset['title'] ?? 'Dataset') . ' — Satu Data Muara Enim')

@push('styles')
<style>
    .dataset-detail {
        padding: 0 0 4.5rem;
    }

    /* ── Hero Badges ── */
    .hero-badges {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.15rem;
    }

    .org-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--gold-muted);
        color: var(--gold);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: var(--radius-full);
        text-decoration: none;
        letter-spacing: 0.01em;
        transition: var(--transition-fast);
        border: 1px solid transparent;
    }

    .org-badge:hover {
        background: var(--gold-glow);
        border-color: var(--border-gold);
        color: var(--gold);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        border-radius: var(--radius-full);
        letter-spacing: 0.02em;
    }

    .status-badge.public {
        background: #dcfce7;
        color: #15803d;
    }

    .status-badge.private {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-badge.open-data {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-badge.active-state {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* ── Title & Notes ── */
    .dataset-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.6rem, 3.8vw, 2.35rem);
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.25;
        letter-spacing: -0.02em;
        margin-bottom: 1.15rem;
    }

    .dataset-notes {
        font-size: 0.95rem;
        color: var(--text-body);
        line-height: 1.8;
        margin-bottom: 2rem;
        max-width: 860px;
    }

    .dataset-notes.is-empty {
        font-style: italic;
        color: var(--text-muted);
    }

    /* ── Meta Highlight Bar ── */
    .meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.75rem;
        padding: 1.2rem 0;
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-item .meta-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
    }

    .meta-item .meta-value {
        font-size: 0.88rem;
        color: var(--text-heading);
        font-weight: 600;
    }

    /* ── Main Layout Grid ── */
    .dataset-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 2.25rem;
        align-items: start;
        margin-top: 2.5rem;
    }

    .content-column {
        min-width: 0;
    }

    .sidebar-column {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* ── Section Titles ── */
    .section-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 1.15rem;
    }

    .section-header h2,
    .section-header h3 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text-heading);
    }

    .section-header .section-count {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ── Resource Card ── */
    .resource-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.35rem;
        display: flex;
        align-items: flex-start;
        gap: 1.15rem;
        transition: var(--transition);
        margin-bottom: 1rem;
        position: relative;
    }

    .resource-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .resource-card .format-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 52px;
        height: 52px;
        border-radius: var(--radius-sm);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        flex-shrink: 0;
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

    .resource-card .resource-info {
        flex: 1;
        min-width: 0;
    }

    .resource-card .resource-info h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.35rem;
        line-height: 1.35;
        word-break: break-word;
    }

    .resource-card .resource-info p {
        font-size: 0.83rem;
        color: var(--text-secondary);
        line-height: 1.55;
        margin-bottom: 0.6rem;
    }

    .resource-card .resource-meta {
        font-size: 0.73rem;
        color: var(--text-muted);
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
        align-items: center;
    }

    .resource-card .resource-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-datastore {
        background: #e0f2fe;
        color: #0284c7;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: var(--radius-xs);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .resource-card .resource-actions {
        display: flex;
        gap: 0.45rem;
        flex-shrink: 0;
        align-self: center;
    }

    /* ── Developer & API Box ── */
    .api-integration-card {
        background: linear-gradient(135deg, var(--bg-cream) 0%, #fdfbf7 100%);
        border: 1px solid var(--border-warm);
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .api-integration-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.6rem;
    }

    .api-integration-header h4 {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .api-integration-desc {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 0.85rem;
        line-height: 1.5;
    }

    .api-endpoint-box {
        background: var(--navy);
        color: #e2e8f0;
        border-radius: var(--radius-sm);
        padding: 0.65rem 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 0.78rem;
        overflow-x: auto;
    }

    .api-endpoint-box code {
        color: #38bdf8;
        white-space: nowrap;
    }

    .api-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .btn-copy-api {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border: none;
        padding: 0.35rem 0.65rem;
        border-radius: var(--radius-xs);
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-copy-api:hover {
        background: rgba(255, 255, 255, 0.22);
    }

    /* ── Sidebar Cards ── */
    .sidebar-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.35rem;
        transition: var(--transition);
    }

    .sidebar-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-sm);
    }

    .sidebar-card-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 1rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Org Profile Card */
    .org-profile {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .org-profile-header {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .org-avatar {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        background: var(--gold-muted);
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        overflow: hidden;
        border: 1px solid var(--border-gold);
    }

    .org-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .org-profile-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.35;
    }

    .org-profile-desc {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .org-btn-filter {
        margin-top: 0.4rem;
        width: 100%;
        font-size: 0.78rem;
    }

    /* Key-Value Specs Table */
    .specs-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .specs-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        padding-bottom: 0.55rem;
        border-bottom: 1px solid var(--border-light);
    }

    .specs-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .specs-label {
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
    }

    .specs-value {
        font-size: 0.83rem;
        color: var(--text-body);
        font-weight: 600;
        word-break: break-all;
    }

    .specs-value.code-font {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.75rem;
        background: var(--bg-cream);
        padding: 0.2rem 0.45rem;
        border-radius: var(--radius-xs);
        border: 1px solid var(--border-light);
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
    }

    .specs-value .copy-mini-btn {
        background: transparent;
        border: none;
        color: var(--gold);
        cursor: pointer;
        padding: 0 0.25rem;
        font-size: 0.75rem;
    }

    /* Tags */
    .tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .tag-link {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.8rem;
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        transition: var(--transition-fast);
    }

    .tag-link:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-glow);
    }

    /* Extras Table */
    .extras-section {
        margin-top: 2.5rem;
        padding-top: 1.75rem;
        border-top: 1px solid var(--border-light);
    }

    .extras-section h3 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.85rem;
    }

    .extras-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        overflow: hidden;
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

    /* ── Responsive ── */
    @media (max-width: 992px) {
        .dataset-layout {
            grid-template-columns: 1fr;
        }

        .sidebar-column {
            margin-top: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .resource-card {
            flex-direction: column;
            align-items: stretch;
        }

        .resource-card .resource-actions {
            align-self: flex-start;
            margin-top: 0.5rem;
        }

        .meta-row {
            gap: 1rem;
        }

        .api-endpoint-box {
            flex-direction: column;
            align-items: flex-start;
        }

        .api-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')

@php
function formatBytes($bytes, $precision = 1) {
    if ($bytes <= 0) return '-';
    $units = ['B', 'KB', 'MB', 'GB'];
    $pow = floor(log($bytes) / log(1024));
    return round($bytes / pow(1024, $pow), $precision) . ' ' . ($units[$pow] ?? 'B');
}

$cleanNotes = trim($dataset['notes'] ?? '');
$isPrivate = (bool) ($dataset['private'] ?? false);
$isOpen = (bool) ($dataset['isopen'] ?? false);
$state = strtolower($dataset['state'] ?? 'active');
$org = $dataset['organization'] ?? null;
$orgTitle = $org['title'] ?? ($org['name'] ?? null);

$rawImageUrl = $org['image_url'] ?? null;
$orgImage = null;
if (!empty($rawImageUrl)) {
    if (str_starts_with($rawImageUrl, 'http://') || str_starts_with($rawImageUrl, 'https://')) {
        $orgImage = $rawImageUrl;
    } else {
        $orgImage = 'https://opendata.muaraenimkab.go.id/uploads/group/' . $rawImageUrl;
    }
}

$resources = $dataset['resources'] ?? [];
$uniqueFormats = array_unique(array_filter(array_map(fn($r) => strtoupper($r['format'] ?? ''), $resources)));
$createdDate = !empty($dataset['metadata_created']) ? \Carbon\Carbon::parse($dataset['metadata_created']) : null;
$modifiedDate = !empty($dataset['metadata_modified']) ? \Carbon\Carbon::parse($dataset['metadata_modified']) : null;
$apiUrl = 'https://opendata.muaraenimkab.go.id/api/3/action/package_show?id=' . ($dataset['id'] ?? '');
@endphp

<section class="page-hero" style="text-align: left;">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Beranda</a>
            <span class="separator">/</span>
            <a href="/dataset">Dataset</a>
            <span class="separator">/</span>
            <span class="current">{{ Str::limit($dataset['title'] ?? 'Detail Dataset', 50) }}</span>
        </div>

        <div class="hero-badges">
            @if(!empty($orgTitle))
            <a href="/dataset?q={{ urlencode($orgTitle) }}" class="org-badge" title="Cari dataset dari organisasi ini">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                    <path d="M9 22v-4h6v4"/>
                    <path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>
                </svg>
                {{ $orgTitle }}
            </a>
            @endif

            @if(!$isPrivate)
            <span class="status-badge public" title="Dataset dapat diakses oleh publik">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 4.24 4.24"/><path d="m14.83 9.17 4.24-4.24"/><path d="m14.83 14.83 4.24 4.24"/><path d="m9.17 14.83-4.24 4.24"/>
                </svg>
                Publik
            </span>
            @else
            <span class="status-badge private" title="Dataset dibatasi">Privat</span>
            @endif

            @if($isOpen)
            <span class="status-badge open-data" title="Memiliki lisensi Open Data resmi">Open Data</span>
            @endif

            <span class="status-badge active-state">
                <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#16a34a;"></span>
                {{ ucfirst($state) }}
            </span>
        </div>

        <h1 class="dataset-title">{{ $dataset['title'] ?? 'Tanpa Judul' }}</h1>

        @if(!empty($cleanNotes))
        <p class="dataset-notes">{!! nl2br(e($cleanNotes)) !!}</p>
        @else
        <p class="dataset-notes is-empty">Tidak ada deskripsi rinci yang disertakan untuk dataset ini.</p>
        @endif

        <div class="meta-row">
            <div class="meta-item">
                <span class="meta-label">Dibuat</span>
                <span class="meta-value">{{ $createdDate ? $createdDate->format('d M Y') : '-' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Terakhir Diperbarui</span>
                <span class="meta-value">
                    {{ $modifiedDate ? $modifiedDate->format('d M Y') : '-' }}
                    @if($modifiedDate)
                    <small style="color: var(--text-muted); font-weight: normal;">({{ $modifiedDate->diffForHumans() }})</small>
                    @endif
                </span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Lisensi</span>
                <span class="meta-value">{{ $dataset['license_title'] ?? ($dataset['license_id'] ?? 'Tidak ditentukan') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Total Berkas</span>
                <span class="meta-value">{{ count($resources) }} Sumber Data</span>
            </div>
            @if(!empty($uniqueFormats))
            <div class="meta-item">
                <span class="meta-label">Format Tersedia</span>
                <span class="meta-value" style="display:flex; gap:0.3rem;">
                    @foreach($uniqueFormats as $ufmt)
                    <span class="badge" style="background:var(--bg-warm); font-size:0.68rem; padding:0.15rem 0.45rem;">{{ $ufmt }}</span>
                    @endforeach
                </span>
            </div>
            @endif
        </div>
    </div>
</section>

<div class="dataset-detail">
    <div class="container">
        <div class="dataset-layout">
            <!-- Left Column: Resources and Developer API -->
            <div class="content-column">
                <section class="resources-section">
                    <div class="section-header">
                        <h2>Berkas & Sumber Data</h2>
                        <span class="section-count">{{ count($resources) }} Berkas Tersedia</span>
                    </div>

                    @forelse($resources as $res)
                    @php
                    $fmt = strtolower($res['format'] ?? '');
                    $pillClass = in_array($fmt, ['csv','pdf','xls','xlsx','json','xml']) ? $fmt : 'default';
                    $resCreated = !empty($res['created']) ? \Carbon\Carbon::parse($res['created']) : null;
                    $resModified = !empty($res['last_modified']) ? \Carbon\Carbon::parse($res['last_modified']) : ($resCreated);
                    @endphp
                    <div class="resource-card">
                        <span class="format-pill pill-{{ $pillClass }}">{{ $res['format'] ?? 'FILE' }}</span>
                        <div class="resource-info">
                            <h4>{{ $res['name'] ?: 'Berkas ' . strtoupper($res['format'] ?? 'Data') }}</h4>
                            @if(!empty($res['description']))
                            <p>{{ $res['description'] }}</p>
                            @endif
                            <div class="resource-meta">
                                <span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                                    </svg>
                                    {{ formatBytes($res['size'] ?? 0) }}
                                </span>
                                @if(!empty($res['mimetype']))
                                <span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                    {{ $res['mimetype'] }}
                                </span>
                                @endif
                                @if($resModified)
                                <span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    Diperbarui {{ $resModified->format('d M Y') }}
                                </span>
                                @endif
                                @if(!empty($res['datastore_active']))
                                <span class="badge-datastore">DataStore API Ready</span>
                                @endif
                            </div>
                        </div>
                        <div class="resource-actions">
                            <a href="{{ route('resources.show', $res['id']) }}" class="btn btn-outline btn-sm">Pratinjau</a>
                            <a href="{{ $res['url'] ?? '#' }}" target="_blank" class="btn btn-primary btn-sm" download>Unduh</a>
                        </div>
                    </div>
                    @empty
                    <div style="background:var(--bg-card); border:1px solid var(--border-light); border-radius:var(--radius); padding:2.5rem; text-align:center; color:var(--text-muted);">
                        <p>Belum ada berkas atau sumber data yang dilampirkan pada dataset ini.</p>
                    </div>
                    @endforelse
                </section>

                @if(!empty($dataset['extras']))
                <section class="extras-section">
                    <h3>Metadata Tambahan</h3>
                    <table class="extras-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Parameter / Kunci</th>
                                <th>Nilai Metadata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataset['extras'] as $extra)
                            <tr>
                                <td><strong>{{ $extra['key'] }}</strong></td>
                                <td>{{ is_array($extra['value']) ? json_encode($extra['value']) : $extra['value'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
                @endif
            </div>

            <!-- Right Column: Sidebar (Organization profile, specifications, tags, contacts) -->
            <aside class="sidebar-column">
                @if(!empty($org))
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title">
                        <span>Produsen Data</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--gold);">
                            <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-3"/>
                        </svg>
                    </h3>
                    <div class="org-profile">
                        <div class="org-profile-header">
                            <div class="org-avatar">
                                @if(!empty($orgImage))
                                <img src="{{ $orgImage }}" alt="{{ $orgTitle }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <span style="display:none;">{{ strtoupper(substr($orgTitle ?? 'O', 0, 1)) }}</span>
                                @else
                                <span>{{ strtoupper(substr($orgTitle ?? 'O', 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="org-profile-name">{{ $orgTitle }}</div>
                        </div>

                        @if(!empty($org['description']))
                        <p class="org-profile-desc">{{ Str::limit($org['description'], 180) }}</p>
                        @endif

                        <a href="/dataset?q={{ urlencode($orgTitle) }}" class="btn btn-outline org-btn-filter">
                            Lihat Semua Dataset Instansi
                        </a>
                    </div>
                </div>
                @endif

                <div class="sidebar-card">
                    <h3 class="sidebar-card-title">
                        <span>Informasi Teknis</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--text-muted);">
                            <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/>
                        </svg>
                    </h3>
                    <div class="specs-list">
                        <div class="specs-item">
                            <span class="specs-label">ID Dataset (UUID)</span>
                            <div class="specs-value code-font">
                                <span>{{ Str::limit($dataset['id'] ?? '-', 22) }}</span>
                                @if(!empty($dataset['id']))
                                <button type="button" class="copy-mini-btn" title="Salin ID Lengkap" onclick="copyApiUrl('{{ $dataset['id'] }}', this)">
                                    Salin
                                </button>
                                @endif
                            </div>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">Identifier Sistem / Slug</span>
                            <span class="specs-value" style="font-family:monospace; font-size:0.8rem; color:var(--text-secondary);">
                                {{ $dataset['name'] ?? '-' }}
                            </span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">Tipe Metadata</span>
                            <span class="specs-value">{{ ucfirst($dataset['type'] ?? 'Dataset') }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">Visibilitas Data</span>
                            <span class="specs-value">{{ $isPrivate ? 'Dibatasi (Privat)' : 'Publik (Akses Terbuka)' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">Hak Cipta / Lisensi</span>
                            <span class="specs-value">{{ $dataset['license_title'] ?? ($dataset['license_id'] ?? 'Tidak ada lisensi') }}</span>
                        </div>

                        @if(!empty($dataset['version']))
                        <div class="specs-item">
                            <span class="specs-label">Versi Data</span>
                            <span class="specs-value">{{ $dataset['version'] }}</span>
                        </div>
                        @endif

                        <div class="specs-item">
                            <span class="specs-label">Waktu Dibuat</span>
                            <span class="specs-value">{{ $createdDate ? $createdDate->format('d M Y, H:i') . ' WIB' : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">Waktu Diperbarui</span>
                            <span class="specs-value">{{ $modifiedDate ? $modifiedDate->format('d M Y, H:i') . ' WIB' : '-' }}</span>
                        </div>
                    </div>
                </div>

                @if(!empty($dataset['author']) || !empty($dataset['maintainer']))
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title">
                        <span>Penanggung Jawab</span>
                    </h3>
                    <div class="specs-list">
                        @if(!empty($dataset['author']))
                        <div class="specs-item">
                            <span class="specs-label">Pembuat Data (Author)</span>
                            <span class="specs-value">{{ $dataset['author'] }}</span>
                            @if(!empty($dataset['author_email']))
                            <small><a href="mailto:{{ $dataset['author_email'] }}" style="color:var(--gold);">{{ $dataset['author_email'] }}</a></small>
                            @endif
                        </div>
                        @endif

                        @if(!empty($dataset['maintainer']))
                        <div class="specs-item">
                            <span class="specs-label">Pengelola Data (Maintainer)</span>
                            <span class="specs-value">{{ $dataset['maintainer'] }}</span>
                            @if(!empty($dataset['maintainer_email']))
                            <small><a href="mailto:{{ $dataset['maintainer_email'] }}" style="color:var(--gold);">{{ $dataset['maintainer_email'] }}</a></small>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if(!empty($dataset['tags']) && count($dataset['tags']) > 0)
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title">
                        <span>Kata Kunci / Tag</span>
                        <span class="section-count">{{ count($dataset['tags']) }}</span>
                    </h3>
                    <div class="tags-list">
                        @foreach($dataset['tags'] as $tag)
                        @php
                        $tagName = $tag['display_name'] ?? ($tag['name'] ?? '');
                        @endphp
                        @if(!empty($tagName))
                        <a href="/dataset?q={{ urlencode($tagName) }}" class="tag-link" title="Cari dataset dengan kata kunci {{ $tagName }}">
                            #{{ $tagName }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    if (typeof gsap !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
        gsap.from('.page-hero', { opacity: 0, y: 20, duration: 0.6, ease: 'power2.out' });
        gsap.from('.resource-card', {
            scrollTrigger: { trigger: '.resources-section', start: 'top 88%' },
            opacity: 0, y: 20, duration: 0.45, stagger: 0.08, ease: 'power2.out'
        });
        gsap.from('.sidebar-card', {
            scrollTrigger: { trigger: '.sidebar-column', start: 'top 88%' },
            opacity: 0, y: 20, duration: 0.45, stagger: 0.1, ease: 'power2.out'
        });
    }

    function copyApiUrl(text, btnElement) {
        if (!navigator.clipboard) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showCopiedFeedback(btnElement);
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            showCopiedFeedback(btnElement);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    function showCopiedFeedback(btnElement) {
        const span = btnElement.querySelector('span') || btnElement;
        const originalText = span.textContent;
        span.textContent = 'Tersalin!';
        btnElement.style.color = '#38bdf8';
        setTimeout(() => {
            span.textContent = originalText;
            btnElement.style.color = '';
        }, 2000);
    }
</script>
@endpush
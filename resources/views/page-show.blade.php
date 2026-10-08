@extends('layouts.app')

@section('title', $page->title . ' — Satu Data Muara Enim')

@push('styles')
<style>
    .page-content {
        padding: 2rem 0 5rem;
    }

    .page-content-inner {
        max-width: 760px;
        margin: 0 auto;
    }

    .page-content-body {
        font-size: 0.95rem;
        line-height: 1.85;
        color: var(--text-body);
    }

    .page-content-body h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 2rem 0 0.85rem;
    }

    .page-content-body h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-heading);
        margin: 1.5rem 0 0.65rem;
    }

    .page-content-body p {
        margin-bottom: 1.15rem;
    }

    .page-content-body ul,
    .page-content-body ol {
        margin-bottom: 1.15rem;
        padding-left: 1.5rem;
    }

    .page-content-body li {
        margin-bottom: 0.4rem;
    }

    .page-content-body img {
        border-radius: var(--radius-sm);
        margin: 1rem 0;
    }

    .page-content-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
    }

    .page-content-body th,
    .page-content-body td {
        padding: 0.65rem 0.85rem;
        border: 1px solid var(--border-light);
        text-align: left;
        font-size: 0.88rem;
    }

    .page-content-body th {
        background: var(--bg-cream);
        font-weight: 600;
        color: var(--text-heading);
    }

    .page-content-body blockquote {
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
        <h1>{{ $page->title }}</h1>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="page-content-inner">
            <div class="page-content-body">
                {!! $page->body !!}
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    gsap.from('.page-hero', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });
    gsap.from('.page-content-body', { opacity: 0, y: 16, duration: 0.5, ease: 'power2.out', delay: 0.2 });
</script>
@endpush
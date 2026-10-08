@extends('layouts.app')

@section('title', 'Tentang — Satu Data Muara Enim')

@push('styles')
<style>
    .about-section {
        padding: 3rem 0 5rem;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3.5rem;
        align-items: start;
        margin-bottom: 3.5rem;
    }

    .about-content h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-heading);
        letter-spacing: -0.02em;
        margin-bottom: 1.15rem;
        line-height: 1.25;
    }

    .about-content p {
        font-size: 0.92rem;
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 0.85rem;
    }

    .about-content ul {
        list-style: none;
        padding: 0;
        margin: 1.15rem 0;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .about-content ul li {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .about-content ul li::before {
        content: '✓';
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        background: var(--gold-muted);
        color: var(--gold);
        border-radius: 50%;
        font-size: 0.6rem;
        font-weight: 800;
        flex-shrink: 0;
        margin-top: 0.15rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.5rem;
        transition: var(--transition);
    }

    .feature-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .feature-card .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-xs);
        background: var(--gold-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.85rem;
    }

    .feature-card h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
    }

    .feature-card p {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .legal-section {
        margin-bottom: 3.5rem;
        padding: 2.25rem;
        background: var(--bg-cream);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
    }

    .legal-section h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 1.15rem;
    }

    .legal-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .legal-section ul li {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.6;
        padding: 0.65rem 0.85rem;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xs);
    }

    .legal-section ul li strong {
        color: var(--text-heading);
        font-weight: 600;
    }

    .contact-section h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 1.35rem;
        text-align: center;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .contact-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        padding: 1.75rem 1.25rem;
        text-align: center;
        transition: var(--transition);
    }

    .contact-card:hover {
        border-color: var(--border-gold);
        box-shadow: var(--shadow-lg);
    }

    .contact-card .contact-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        background: var(--gold-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin: 0 auto 0.85rem;
    }

    .contact-card h3 {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 0.4rem;
    }

    .contact-card p {
        font-size: 0.82rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .contact-card a {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
    }

    .contact-card a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .contact-grid {
            grid-template-columns: 1fr;
        }

        .legal-section {
            padding: 1.35rem;
        }
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container">
        <h1>Tentang Satu Data</h1>
        <p>Portal data terbuka Kabupaten Muara Enim sebagai wujud transparansi dan akuntabilitas pemerintah daerah</p>
    </div>
</section>

<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-content">
                <h2>Apa itu Satu Data Muara Enim?</h2>
                <p>Portal Satu Data Kabupaten Muara Enim merupakan platform penyediaan data terbuka yang dikelola oleh
                    Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Muara Enim.</p>
                <p>Portal ini bertujuan untuk menyediakan data sektoral yang terintegrasi, akurat, dan mudah diakses
                    oleh seluruh masyarakat, akademisi, pelaku usaha, dan pemangku kepentingan lainnya.</p>
                <ul>
                    <li>Menyediakan data terbuka dari berbagai OPD dalam format yang dapat digunakan ulang</li>
                    <li>Mendukung kebijakan Satu Data Indonesia di tingkat kabupaten</li>
                    <li>Mendorong transparansi dan akuntabilitas pemerintah daerah</li>
                </ul>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Transparansi</h3>
                    <p>Membuka akses informasi publik untuk mendorong transparansi dan akuntabilitas pemerintah daerah.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Data Terintegrasi</h3>
                    <p>Mengintegrasikan data dari seluruh OPD dalam satu platform yang mudah diakses dan dikelola.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💡</div>
                    <h3>Inovasi</h3>
                    <p>Mendorong inovasi berbasis data untuk pengembangan daerah dan peningkatan pelayanan publik.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🤝</div>
                    <h3>Kolaborasi</h3>
                    <p>Memfasilitasi kolaborasi antar instansi dan stakeholder dalam pemanfaatan data daerah.</p>
                </div>
            </div>
        </div>

        <div class="legal-section">
            <h2>📜 Dasar Hukum</h2>
            <ul>
                <li><strong>Perpres No. 39/2019</strong> — Peraturan Presiden Nomor 39 Tahun 2019 tentang Satu Data
                    Indonesia</li>
                <li><strong>Permen PPN No. 16/2020</strong> — Peraturan Menteri PPN/Kepala Bappenas Nomor 16 Tahun 2020
                    tentang Manajemen Data Satu Data Indonesia</li>
                <li><strong>Perda Kab. Muara Enim</strong> — Peraturan Daerah Kabupaten Muara Enim tentang
                    Penyelenggaraan Komunikasi dan Informatika</li>
            </ul>
        </div>

        <div class="contact-section">
            <h2>Hubungi Kami</h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">📍</div>
                    <h3>Alamat</h3>
                    <p>Jl. Jend. Sudirman No. 1<br>Muara Enim, Sumatera Selatan 31311</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">✉️</div>
                    <h3>Email</h3>
                    <p><a href="mailto:diskominfo@muaraenimkab.go.id">diskominfo@muaraenimkab.go.id</a></p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">🌐</div>
                    <h3>Website</h3>
                    <p><a href="https://muaraenimkab.go.id" target="_blank">muaraenimkab.go.id</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    gsap.registerPlugin(ScrollTrigger);
    gsap.from('.page-hero', { opacity: 0, y: 24, duration: 0.7, ease: 'power2.out' });
    gsap.from('.about-content', { scrollTrigger: { trigger: '.about-section', start: 'top 85%' }, opacity: 0, x: -24, duration: 0.7, ease: 'power2.out' });
    gsap.from('.feature-card', { scrollTrigger: { trigger: '.features-grid', start: 'top 85%' }, opacity: 0, y: 24, duration: 0.45, stagger: 0.08, ease: 'power2.out' });
    gsap.from('.contact-card', { scrollTrigger: { trigger: '.contact-grid', start: 'top 85%' }, opacity: 0, y: 24, duration: 0.45, stagger: 0.08, ease: 'power2.out' });
</script>
@endpush
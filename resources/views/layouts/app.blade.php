<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal data terbuka Kabupaten Muara Enim untuk transparansi dan inovasi daerah">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Satu Data Kabupaten Muara Enim')</title>

    <link rel="icon" type="image/x-icon" href="{{URL::asset('logo.png')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/Flip.min.js"></script>

    <style>
        :root {
            --navy: #0f1d35;
            --navy-deep: #0a1628;
            --navy-light: #1a2d4d;
            --gold: #b8973a;
            --gold-light: #d4b04a;
            --gold-muted: rgba(184, 151, 58, 0.12);
            --gold-glow: rgba(184, 151, 58, 0.06);
            --teal: #1a7a6d;
            --teal-light: #e6f5f2;

            --bg-white: #ffffff;
            --bg-cream: #faf8f5;
            --bg-warm: #f5f2ed;
            --bg-card: #ffffff;

            --text-heading: #0f1d35;
            --text-body: #374151;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --text-on-dark: #e8e4dd;
            --text-on-dark-muted: #9ba3a0;

            --border-light: #e8e4dd;
            --border-warm: #d6d0c5;
            --border-gold: rgba(184, 151, 58, 0.25);

            --radius: 10px;
            --radius-sm: 6px;
            --radius-xs: 4px;
            --radius-full: 9999px;

            --shadow-sm: 0 1px 3px rgba(15, 29, 53, 0.04);
            --shadow-md: 0 4px 12px rgba(15, 29, 53, 0.06);
            --shadow-lg: 0 8px 24px rgba(15, 29, 53, 0.08);
            --shadow-card: 0 1px 3px rgba(15, 29, 53, 0.04), 0 0 0 1px rgba(15, 29, 53, 0.03);

            --transition: 0.25s ease;
            --transition-fast: 0.15s ease;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-body);
            background: var(--bg-white);
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a {
            color: inherit;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        button {
            cursor: pointer;
            font-family: inherit;
        }

        .site-main {
            min-height: 100vh;
        }

        /* ── Container ── */
        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.75rem;
        }

        /* ── Page Hero (shared across sub-pages) ── */
        .page-hero {
            text-align: center;
            padding: 7rem 2rem 3rem;
            background: var(--bg-cream);
            border-bottom: 1px solid var(--border-light);
        }

        .page-hero h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .page-hero p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ── Section Titles ── */
        .section-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .section-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 520px;
        }

        /* ── Card Base ── */
        .card-base {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            padding: 1.75rem;
            transition: var(--transition);
        }

        .card-base:hover {
            border-color: var(--border-gold);
            box-shadow: var(--shadow-lg);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: var(--radius-sm);
            padding: 0.7rem 1.4rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            line-height: 1;
            letter-spacing: 0.01em;
        }

        .btn-primary {
            background: var(--navy);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--navy-light);
            box-shadow: 0 4px 12px rgba(15, 29, 53, 0.2);
        }

        .btn-gold {
            background: var(--gold);
            color: #fff;
        }

        .btn-gold:hover {
            background: var(--gold-light);
            box-shadow: 0 4px 12px rgba(184, 151, 58, 0.25);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-heading);
            border: 1.5px solid var(--border-warm);
        }

        .btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-glow);
        }

        .btn-sm {
            font-size: 0.78rem;
            padding: 0.45rem 0.9rem;
            border-radius: var(--radius-xs);
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: var(--radius-full);
            letter-spacing: 0.02em;
        }

        .badge-primary {
            background: var(--gold-muted);
            color: var(--gold);
        }

        /* ── Format Badges ── */
        .format-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.18rem 0.45rem;
            border-radius: 3px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .format-csv {
            background: #dcfce7;
            color: #15803d;
        }

        .format-pdf {
            background: #fee2e2;
            color: #b91c1c;
        }

        .format-xls,
        .format-xlsx {
            background: #ccfbf1;
            color: #0f766e;
        }

        .format-json {
            background: #fef3c7;
            color: #b45309;
        }

        .format-xml {
            background: #e0e7ff;
            color: #4338ca;
        }

        .format-default {
            background: var(--bg-warm);
            color: var(--text-secondary);
        }

        /* ── Breadcrumb ── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--text-muted);
            padding: 1rem 0;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition-fast);
        }

        .breadcrumb a:hover {
            color: var(--gold);
        }

        .breadcrumb .separator {
            color: var(--border-warm);
        }

        .breadcrumb .current {
            color: var(--text-body);
            font-weight: 500;
        }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 2.5rem 0;
        }

        .pagination .page-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.55rem 1.15rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--border-light);
            background: var(--bg-card);
            color: var(--text-secondary);
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination .page-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-glow);
        }

        .pagination .page-btn.disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        .pagination .page-info {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ════════════════════════════════════════
           NAVIGATION
           ════════════════════════════════════════ */
        .site-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-image:
                linear-gradient(90deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.76)),
                url('/navbar-fixed.png');
            background-size: auto 132px;
            background-position: center 57%;
            background-repeat: repeat-x;
            border-bottom: 1px solid rgba(7, 106, 117, 0.22);
            box-shadow: 0 1px 12px rgba(7, 106, 117, 0.08);
            transition: var(--transition);
        }

        .site-nav.scrolled {
            background-image:
                linear-gradient(90deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.82)),
                url('/navbar-fixed.png');
            background-size: auto 126px;
            background-position: center 57%;
            background-repeat: repeat-x;
            box-shadow: 0 2px 16px rgba(7, 106, 117, 0.1);
        }

        .nav-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-logo img {
            height: 38px;
            width: auto;
        }

        .nav-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .nav-logo-title {
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--text-heading);
            letter-spacing: -0.01em;
        }

        .nav-logo-sub {
            font-weight: 400;
            font-size: 0.68rem;
            color: var(--text-muted);
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.2rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            padding: 0.52rem 0.9rem;
            text-decoration: none;
            color: #26364d;
            font-size: 0.88rem;
            font-weight: 750;
            letter-spacing: -0.01em;
            border-radius: var(--radius-xs);
            transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--navy-deep);
            background: rgba(255, 255, 255, 0.48);
            box-shadow: 0 8px 18px rgba(15, 29, 53, 0.06);
            transform: translateY(-1px);
        }

        .nav-links a.active {
            color: #9a7419;
            font-weight: 850;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0.85rem;
            right: 0.85rem;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 999px;
            box-shadow: 0 3px 10px rgba(184, 151, 58, 0.35);
        }

        .nav-login-wrap {
            position: relative;
            margin-left: 0.75rem;
        }

        .nav-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.7rem 1.2rem;
            border-radius: var(--radius-full);
            background: var(--navy);
            color: #fff;
            border: 0;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 850;
            line-height: 1;
            text-decoration: none;
            box-shadow: 0 10px 22px rgba(15, 29, 53, 0.18);
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            white-space: nowrap;
        }

        .nav-login:hover {
            background: var(--gold);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(184, 151, 58, 0.26);
        }

        .nav-login .chevron {
            width: 13px;
            height: 13px;
            transition: transform 0.22s ease;
        }

        .nav-login-wrap.open .nav-login .chevron {
            transform: rotate(180deg);
        }

        .nav-login-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            min-width: 230px;
            padding: 0.55rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid var(--border-light);
            box-shadow: 0 18px 38px rgba(15, 29, 53, 0.16);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px) scale(0.98);
            transform-origin: top right;
            transition: opacity 0.18s ease, visibility 0.18s ease, transform 0.18s ease;
            z-index: 120;
        }

        .nav-login-wrap.open .nav-login-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .nav-login-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 0.85rem;
            border-radius: var(--radius-xs);
            color: var(--text-heading);
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 750;
            transition: background 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .nav-login-menu a:hover {
            background: var(--gold-glow);
            color: var(--gold);
            transform: translateX(2px);
        }

        .nav-login-mobile {
            margin-top: 0.5rem;
            background: var(--navy);
            color: #fff !important;
            text-align: center;
            font-weight: 700 !important;
        }

        .nav-mobile-login-sub {
            display: grid;
            gap: 0.35rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            background: rgba(15, 29, 53, 0.04);
        }

        /* Hamburger */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: var(--radius-xs);
            transition: var(--transition-fast);
        }

        .nav-hamburger:hover {
            background: var(--bg-cream);
        }

        .nav-hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--text-heading);
            border-radius: 1px;
            transition: var(--transition);
        }

        .nav-hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .nav-hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .nav-hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Dropdown */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown>a {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .nav-dropdown>a .chevron {
            width: 12px;
            height: 12px;
            transition: transform 0.2s;
        }

        .nav-dropdown:hover>a .chevron {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-50%) translateY(8px);
            transition: opacity 0.2s, visibility 0.2s, transform 0.2s;
            z-index: 100;
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .nav-dropdown-menu a {
            display: block;
            padding: 0.55rem 0.9rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: var(--radius-xs);
            transition: var(--transition-fast);
            white-space: nowrap;
        }

        .nav-dropdown-menu a:hover {
            background: var(--bg-cream);
            color: var(--text-heading);
        }

        .nav-dropdown-menu a.active {
            color: var(--gold);
            background: var(--gold-glow);
        }

        /* Mobile nav */
        .nav-mobile {
            display: none;
            position: fixed;
            top: 72px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem;
            box-shadow: var(--shadow-lg);
            z-index: 999;
        }

        .nav-mobile.active {
            display: block;
        }

        .nav-mobile a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: var(--radius-xs);
            transition: var(--transition-fast);
        }

        .nav-mobile a:hover {
            background: var(--bg-cream);
            color: var(--text-heading);
        }

        .nav-mobile a.active {
            color: var(--gold);
            background: var(--gold-glow);
            font-weight: 600;
        }

        .nav-mobile-sub {
            padding-left: 1rem;
            border-left: 2px solid var(--border-light);
            margin-left: 1rem;
        }

        .nav-mobile-sub a {
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .nav-hamburger {
                display: flex;
            }
        }

        /* ════════════════════════════════════════
           FOOTER
           ════════════════════════════════════════ */
        .site-footer {
            background-image:
                linear-gradient(180deg, rgba(10, 22, 40, 0.94), rgba(10, 22, 40, 0.98)),
                url('/navbar-fixed.png');
            background-size: auto 360px;
            background-position: center top;
            background-repeat: repeat;
            color: var(--text-on-dark-muted);
            padding-top: 4rem;
            position: relative;
        }

        .footer-batik-strip {
            display: block;
            width: 100%;
            height: 5px;
            object-fit: cover;
            object-position: center;
        }

        .footer-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.75rem 3.5rem;
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1fr;
            gap: 3rem;
        }

        .footer-brand {}

        .footer-brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .footer-brand-logo img {
            height: 36px;
            width: auto;
            opacity: 0.9;
            filter: brightness(10);
        }

        .footer-brand-logo span {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-on-dark);
            letter-spacing: -0.01em;
        }

        .footer-brand p {
            font-size: 0.85rem;
            line-height: 1.7;
            color: var(--text-on-dark-muted);
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 0.5rem;
        }

        .footer-social a {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-xs);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-on-dark-muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .footer-social a:hover {
            background: var(--gold);
            color: white;
            border-color: var(--gold);
        }

        .footer-section h4 {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
            margin-bottom: 1.25rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .footer-section a {
            color: var(--text-on-dark-muted);
            text-decoration: none;
            font-size: 0.84rem;
            transition: var(--transition-fast);
        }

        .footer-section a:hover {
            color: var(--text-on-dark);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.84rem;
            color: var(--text-on-dark-muted);
            margin-bottom: 0.65rem;
            line-height: 1.5;
        }

        .footer-contact-icon {
            flex-shrink: 0;
            width: 16px;
            text-align: center;
            margin-top: 2px;
            font-size: 0.8rem;
        }

        /* Visitor stats */
        .footer-visitor {
            max-width: 1180px;
            margin: 0 auto;
            padding: 2rem 1.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .footer-visitor h4 {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .visitor-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .visitor-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-sm);
        }

        .visitor-item .number {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-on-dark);
            letter-spacing: -0.02em;
        }

        .visitor-item .label {
            font-size: 0.68rem;
            color: var(--text-on-dark-muted);
            margin-top: 0.2rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 500;
        }

        .footer-bottom {
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.5rem 1.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .footer-bottom a {
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            transition: var(--transition-fast);
        }

        .footer-bottom a:hover {
            color: var(--gold);
        }

        @media (max-width: 768px) {
            .footer-inner {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .visitor-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-bottom {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .site-footer {
                padding-top: 2.5rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="site-nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="/logo.png" alt="Logo Satu Data Muara Enim">
                <div class="nav-logo-text">
                    <span class="nav-logo-title">Satu Data</span>
                    <span class="nav-logo-sub">Kabupaten Muara Enim</span>
                </div>
            </a>

            <ul class="nav-links">
                @foreach(($navMenus ?? collect()) as $menu)
                @if($menu->children->count())
                <li class="nav-dropdown">
                    <a href="{{ $menu->resolved_url }}"
                        class="{{ request()->is(ltrim($menu->resolved_url, '/') . '*') ? 'active' : '' }}"
                        @if($menu->open_in_new_tab) target="_blank" rel="noopener" @endif>
                        {{ $menu->title }}
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </a>
                    <div class="nav-dropdown-menu">
                        @foreach($menu->children as $child)
                        <a href="{{ $child->resolved_url }}"
                            class="{{ request()->is(ltrim($child->resolved_url, '/') . '*') ? 'active' : '' }}"
                            @if($child->open_in_new_tab) target="_blank" rel="noopener" @endif>
                            {{ $child->title }}
                        </a>
                        @endforeach
                    </div>
                </li>
                @else
                <li>
                    <a href="{{ $menu->resolved_url }}"
                        class="{{ request()->is(ltrim($menu->resolved_url, '/') . '*') || (request()->is('/') && $menu->resolved_url === '/') ? 'active' : '' }}"
                        @if($menu->open_in_new_tab) target="_blank" rel="noopener" @endif>
                        {{ $menu->title }}
                    </a>
                </li>
                @endif
                @endforeach
            </ul>

            <div class="nav-login-wrap" id="navLoginWrap">
                <button type="button" class="nav-login" id="navLoginButton" aria-expanded="false"
                    aria-controls="navLoginMenu">
                    Login
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div class="nav-login-menu" id="navLoginMenu">
                    <a href="https://opendata.muaraenimkab.go.id/user/login" target="_blank" rel="noopener">
                        <span>Operator Satudata</span>
                        <span>↗</span>
                    </a>
                    <a href="{{ route('admin.login') }}">
                        <span>Operator Dashboard</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <button class="nav-hamburger" id="navHamburger" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <div class="nav-mobile" id="navMobile">
        @foreach(($navMenus ?? collect()) as $menu)
        <a href="{{ $menu->resolved_url }}"
            class="{{ request()->is(ltrim($menu->resolved_url, '/') . '*') || (request()->is('/') && $menu->resolved_url === '/') ? 'active' : '' }}"
            @if($menu->open_in_new_tab) target="_blank" rel="noopener" @endif>
            {{ $menu->title }}
        </a>
        @if($menu->children->count())
        <div class="nav-mobile-sub">
            @foreach($menu->children as $child)
            <a href="{{ $child->resolved_url }}"
                class="{{ request()->is(ltrim($child->resolved_url, '/') . '*') ? 'active' : '' }}"
                @if($child->open_in_new_tab) target="_blank" rel="noopener" @endif>
                {{ $child->title }}
            </a>
            @endforeach
        </div>
        @endif
        @endforeach
        <div class="nav-mobile-login-sub">
            <a href="https://opendata.muaraenimkab.go.id/user/login" target="_blank" rel="noopener">
                Operator Satudata ↗
            </a>
            <a href="{{ route('admin.login') }}" class="nav-login-mobile">Operator Dashboard</a>
        </div>
    </div>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        @php
        $vs = $visitorStats ?? ['total' => 0, 'today' => 0, 'this_month' => 0, 'this_year' => 0];
        @endphp
        <div class="footer-visitor">
            <h4>Statistik Pengunjung</h4>
            <div class="visitor-grid">
                <div class="visitor-item">
                    <div class="number">{{ number_format($vs['total']) }}</div>
                    <div class="label">Total Pengunjung</div>
                </div>
                <div class="visitor-item">
                    <div class="number">{{ number_format($vs['today']) }}</div>
                    <div class="label">Hari Ini</div>
                </div>
                <div class="visitor-item">
                    <div class="number">{{ number_format($vs['this_month']) }}</div>
                    <div class="label">Bulan Ini</div>
                </div>
                <div class="visitor-item">
                    <div class="number">{{ number_format($vs['this_year']) }}</div>
                    <div class="label">Tahun Ini</div>
                </div>
            </div>
        </div>

        <div class="footer-inner">
            <div class="footer-brand">
                <div class="footer-brand-logo">
                    <img src="/logo.png" alt="Logo">
                    <span>Satu Data Muara Enim</span>
                </div>
                <p>Portal data terbuka untuk mendukung transparansi, perencanaan, dan inovasi berbasis data di Kabupaten
                    Muara Enim, Sumatera Selatan.</p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/diskominfo.muaraenim/" target="_blank" title="Facebook">FB</a>
                    <a href="https://www.instagram.com/pemkab_muaraenim/" target="_blank" title="Instagram">IG</a>
                    <a href="https://www.youtube.com/@kominfo.muaraenim" target="_blank" title="YouTube">YT</a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Kontak</h4>
                <div class="footer-contact-item">
                    <span class="footer-contact-icon">📍</span>
                    <span>Jl. Ahmad Yani No.16, Ps. II Muara Enim, Kec. Muara Enim, Kabupaten Muara Enim, Sumatera
                        Selatan 31313</span>
                </div>
                <div class="footer-contact-item">
                    <span class="footer-contact-icon">✉️</span>
                    <span>diskominfo@muaraenimkab.go.id</span>
                </div>
            </div>

            <div class="footer-section">
                <h4>Sumber Data</h4>
                <ul>
                    <li><a href="https://opendata.muaraenimkab.go.id" target="_blank">CKAN Satu Data</a></li>
                    <li><a href="https://data.go.id" target="_blank">Satu Data Indonesia</a></li>
                    <li><a href="https://muaraenimkab.go.id" target="_blank">Website Kab. Muara Enim</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Pemerintah Kabupaten Muara Enim</span>
            <span>Diskominfo SP Kabupaten Muara Enim</span>
        </div>
    </footer>

    <script>
        // Hamburger toggle
        (function() {
            const hamburger = document.getElementById('navHamburger');
            const mobileNav = document.getElementById('navMobile');
            if (hamburger && mobileNav) {
                hamburger.addEventListener('click', function() {
                    this.classList.toggle('open');
                    mobileNav.classList.toggle('active');
                });
            }
        })();

        // Login dropdown
        (function() {
            const loginWrap = document.getElementById('navLoginWrap');
            const loginButton = document.getElementById('navLoginButton');

            if (!loginWrap || !loginButton) {
                return;
            }

            loginButton.addEventListener('click', function(event) {
                event.stopPropagation();
                const isOpen = loginWrap.classList.toggle('open');
                loginButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function(event) {
                if (!loginWrap.contains(event.target)) {
                    loginWrap.classList.remove('open');
                    loginButton.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    loginWrap.classList.remove('open');
                    loginButton.setAttribute('aria-expanded', 'false');
                }
            });
        })();

        // Scroll-aware nav
        const siteNav = document.querySelector('.site-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                siteNav.classList.add('scrolled');
            } else {
                siteNav.classList.remove('scrolled');
            }
        }, { passive: true });
    </script>

    @stack('scripts')
</body>

</html>
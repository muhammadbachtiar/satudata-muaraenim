# UI Redesign Plan — Satu Data Muara Enim

> **Goal**: Transform the current functional-but-mediocre UI into an elegant, professional government data portal.  
> **Constraint**: Keep inline `<style>` approach. Preserve ALL GSAP selectors/classes.  
> **No new dependencies**. Zero risk to existing animations.

---

## Table of Contents

1. [Current State Audit](#1-current-state-audit)
2. [Design System](#2-design-system)
3. [Component Redesign](#3-component-redesign)
4. [Page-by-Page Changes](#4-page-by-page-changes)
5. [GSAP Compatibility Matrix](#5-gsap-compatibility-matrix)
6. [Implementation Order](#6-implementation-order)

---

## 1. Current State Audit

### 1.1 Pain Points Identified

| #   | Issue                                                                     | Severity | Files Affected                                                                     |
| --- | ------------------------------------------------------------------------- | -------- | ---------------------------------------------------------------------------------- |
| 1   | `.container` duplicated 7 times identically                               | High     | All page templates                                                                 |
| 2   | `.page-hero` duplicated 6 times with near-identical CSS                   | High     | All subpages                                                                       |
| 3   | `.search-form`, `.search-input`, `.search-btn` duplicated                 | Medium   | `home.blade.php`, `datasets/search.blade.php`                                      |
| 4   | `.dataset-card` defined twice with slight differences                     | Medium   | `home.blade.php`, `datasets/search.blade.php`                                      |
| 5   | `.section-heading` duplicated in 3 files                                  | Low      | `datasets/show.blade.php`, `resources/show.blade.php`, `publikasi.blade.php`       |
| 6   | Format badge colors duplicated in 3 files                                 | Medium   | `datasets/search.blade.php`, `datasets/show.blade.php`, `resources/show.blade.php` |
| 7   | All card hover effects are identical `translateY(-3..4px)` — no variation | Low      | All files                                                                          |
| 8   | Typography has minimal hierarchy — headings all look similar              | Medium   | All files                                                                          |
| 9   | Color palette is flat — only one blue accent, no semantic colors          | Medium   | `:root` variables                                                                  |
| 10  | Footer social links use text abbreviations instead of icons               | Low      | `layouts/app.blade.php`                                                            |

### 1.2 What Already Works Well

- **CSS custom properties** in `:root` — good foundation to build on
- **`Inter` font family** — excellent choice for data portals
- **Glassmorphism nav** with `backdrop-filter` — modern touch
- **Responsive breakpoints** at 768px and 640px — solid
- **GSAP animations** are well-structured and performant
- **Card-based layouts** with consistent border/radius — easy to refine

### 1.3 Current CSS Architecture

```
layouts/app.blade.php        → :root vars, reset, .site-nav, .site-footer (391 lines)
home.blade.php               → .gallery-*, .hero-*, .container, all section cards (761 lines)
datasets/search.blade.php    → .container, .page-hero, .search-*, .dataset-card (273 lines)
datasets/show.blade.php      → .container, .page-hero, .breadcrumb, .resource-card (297 lines)
resources/show.blade.php     → .container, .page-hero, .breadcrumb, .info-*, .download-* (241 lines)
organizations.blade.php      → .container, .page-hero, .org-* (154 lines)
publikasi.blade.php          → .container, .page-hero, .coming-soon (73 lines)
tentang.blade.php            → .container, .page-hero, .about-*, .feature-*, .contact-* (181 lines)
                                                                        TOTAL: ~2,370 lines
```

**After consolidation target**: ~1,600 lines total (32% reduction) by moving shared styles to layout.

---

## 2. Design System

### 2.1 Color Palette — Refined

Replace the current flat `:root` variables with a more nuanced, government-grade palette.

```css
:root {
    /* ── Primary Surface ── */
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --bg-elevated: #ffffff; /* cards on colored backgrounds */

    /* ── Accent: Deeper Government Blue ── */
    --accent-50: #eff6ff;
    --accent-100: #dbeafe;
    --accent-200: #bfdbfe;
    --accent-500: #3b82f6;
    --accent: #1e40af; /* WAS: #2563eb — now deeper, more authoritative */
    --accent-hover: #1e3a8a; /* WAS: #1d4ed8 */
    --accent-light: #3b82f6;
    --accent-lighter: #dbeafe;
    --accent-glow: rgba(30, 64, 175, 0.06); /* subtler glow */
    --accent-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);

    /* ── Secondary Accent: Slate Teal ── */
    --secondary: #0f766e;
    --secondary-light: #14b8a6;
    --secondary-lighter: #ccfbf1;

    /* ── Text Hierarchy ── */
    --text-primary: #0f172a;
    --text-heading: #020617; /* NEW: near-black for headings */
    --text-secondary: #475569;
    --text-muted: #94a3b8;
    --text-caption: #64748b; /* NEW: between secondary and muted */

    /* ── Borders ── */
    --border: #e2e8f0;
    --border-hover: #cbd5e1;
    --border-subtle: #f1f5f9; /* NEW: barely visible dividers */
    --border-accent: rgba(30, 64, 175, 0.2); /* NEW: blue-tinted borders */

    /* ── Radius — Slightly more restrained ── */
    --radius: 14px; /* WAS: 16px — tighter feels more professional */
    --radius-sm: 10px;
    --radius-xs: 8px;
    --radius-pill: 100px;

    /* ── Shadows — Layered system ── */
    --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.03);
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
    --shadow-md:
        0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
    --shadow-lg:
        0 10px 25px -5px rgba(0, 0, 0, 0.06),
        0 8px 10px -6px rgba(0, 0, 0, 0.03);
    --shadow-xl: 0 20px 50px -12px rgba(0, 0, 0, 0.08);
    --shadow-card:
        0 1px 3px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.02); /* NEW */
    --shadow-card-hover:
        0 12px 28px -8px rgba(30, 64, 175, 0.12),
        0 4px 8px -4px rgba(0, 0, 0, 0.04); /* NEW: blue-tinted */

    /* ── Motion ── */
    --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1); /* WAS: 0.3s — slightly snappier */
    --transition-slow: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-bounce: 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}
```

### 2.2 Typography Scale

The current design uses arbitrary font sizes. Switch to a modular scale (1.2 ratio, base 1rem).

```css
/* ── Typography Scale (add to layouts/app.blade.php) ── */

/* Display — Hero headlines */
.text-display {
    font-size: clamp(2.8rem, 6vw, 4.5rem);
    font-weight: 800;
    letter-spacing: -0.035em;
    line-height: 1.05;
    color: var(--text-heading);
}

/* H1 — Page titles */
.text-h1 {
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    line-height: 1.15;
    color: var(--text-heading);
}

/* H2 — Section titles */
.text-h2 {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.2;
    color: var(--text-heading);
}

/* H3 — Card titles, subsections */
.text-h3 {
    font-size: 1.125rem;
    font-weight: 600;
    letter-spacing: -0.01em;
    line-height: 1.35;
    color: var(--text-primary);
}

/* Body Large — Subtitles, descriptions */
.text-body-lg {
    font-size: 1.0625rem;
    font-weight: 400;
    line-height: 1.75;
    color: var(--text-secondary);
}

/* Body — Default */
.text-body {
    font-size: 0.9375rem;
    font-weight: 400;
    line-height: 1.65;
    color: var(--text-secondary);
}

/* Caption — Meta info, labels */
.text-caption {
    font-size: 0.8125rem;
    font-weight: 500;
    line-height: 1.4;
    color: var(--text-caption);
}

/* Overline — Category labels, badges */
.text-overline {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text-muted);
}
```

### 2.3 Spacing System

Adopt a consistent 8px grid:

| Token        | Value | Usage                               |
| ------------ | ----- | ----------------------------------- |
| `--space-1`  | 4px   | Tight gaps within components        |
| `--space-2`  | 8px   | Icon gaps, inline spacing           |
| `--space-3`  | 12px  | Small padding, card internal gaps   |
| `--space-4`  | 16px  | Standard gap between related items  |
| `--space-5`  | 20px  | Grid gaps                           |
| `--space-6`  | 24px  | Card padding, section internal      |
| `--space-8`  | 32px  | Section header to content           |
| `--space-10` | 40px  | Between page sections               |
| `--space-12` | 48px  | Major section padding               |
| `--space-16` | 64px  | Section vertical padding (small)    |
| `--space-20` | 80px  | Section vertical padding (standard) |
| `--space-24` | 96px  | Section vertical padding (large)    |

### 2.4 Shadow & Depth Model

```
Layer 0: Page background (--bg-primary / --bg-secondary)
Layer 1: Cards at rest (--shadow-card + 1px border)
Layer 2: Cards on hover (--shadow-card-hover, border-color change)
Layer 3: Floating UI — nav, modals (--shadow-lg + backdrop-filter)
```

**Key change**: Cards should NOT rely on heavy shadows. Use `border + subtle shadow` at rest, and a **blue-tinted shadow** on hover to create a cohesive, branded depth effect.

---

## 3. Component Redesign

### 3.1 Navigation — Premium Polish

**File**: [`layouts/app.blade.php`](resources/views/layouts/app.blade.php)

**Current issues**:

- Nav height 64px feels cramped for a government portal
- Logo is just text with a colored square — needs more gravitas
- Active state uses flat `background: var(--accent-lighter)` — feels like a button, not a nav indicator
- No scroll-state change (it stays the same whether scrolled or at top)

**CSS changes**:

```css
/* ── Navigation Upgrade ── */
.site-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    backdrop-filter: blur(24px) saturate(180%); /* WAS: blur(20px) — more saturation */
    -webkit-backdrop-filter: blur(24px) saturate(180%);
    background: rgba(255, 255, 255, 0.82);
    border-bottom: 1px solid rgba(226, 232, 240, 0.6); /* softer border */
    transition: all var(--transition);
}

/* NEW: Scrolled state — add via JS: document.querySelector('.site-nav').classList.add('scrolled') */
.site-nav.scrolled {
    background: rgba(255, 255, 255, 0.95);
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.04),
        0 4px 12px rgba(0, 0, 0, 0.03);
}

.nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    height: 72px; /* WAS: 64px — more breathing room */
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.nav-logo {
    display: flex;
    align-items: center;
    gap: 12px; /* WAS: 10px */
    font-weight: 700;
    font-size: 1.125rem; /* WAS: 1.2rem — slightly smaller, more refined */
    color: var(--text-heading);
    letter-spacing: -0.02em;
}

.nav-logo-icon {
    width: 36px; /* WAS: 32px */
    height: 36px;
    border-radius: 10px; /* WAS: 8px */
    background: var(--accent-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 0.75rem;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.25); /* NEW: subtle glow */
}

.nav-logo-sub {
    font-weight: 400;
    font-size: 0.8rem; /* WAS: 0.85rem */
    color: var(--text-caption); /* WAS: --text-muted — slightly darker */
    margin-left: -2px;
}

/* ── Nav Links — Refined Active State ── */
.nav-links a {
    display: block;
    padding: 8px 14px; /* WAS: 8px 16px — tighter */
    font-size: 0.875rem; /* WAS: 0.9rem */
    font-weight: 500;
    color: var(--text-secondary);
    border-radius: var(--radius-xs);
    transition:
        color var(--transition),
        background var(--transition);
    position: relative;
}

.nav-links a:hover {
    color: var(--accent);
    background: var(--accent-glow);
}

/* NEW: Bottom-indicator active state instead of filled background */
.nav-links a.active {
    color: var(--accent);
    background: transparent; /* WAS: --accent-lighter — remove fill */
    font-weight: 600;
}

.nav-links a.active::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 14px;
    right: 14px;
    height: 2px;
    background: var(--accent);
    border-radius: 2px;
}
```

**HTML change**: None. All existing selectors preserved.

**JS addition** (add to existing hamburger script in `layouts/app.blade.php`):

```javascript
// Scroll-aware nav
window.addEventListener("scroll", function () {
    document
        .querySelector(".site-nav")
        .classList.toggle("scrolled", window.scrollY > 20);
});
```

**⚠️ GSAP note**: No GSAP selectors affected. `.site-nav` is not animated.

---

### 3.2 Cards — Sophisticated Depth

**Affects**: `.dataset-card`, `.berita-card`, `.infografis-card`, `.org-card`, `.stat-card`, `.feature-card`, `.contact-card`, `.resource-card`, `.info-item`

**Current issues**:

- All cards use same `translateY(-3..4px)` hover — monotonous
- Borders are visible but don't add elegance
- No inner structure refinement

**Universal card base** (add to `layouts/app.blade.php`):

```css
/* ── Card Base — move duplicated patterns to layout ── */
/* NOTE: These are visual-only enhancements. GSAP classes (.dataset-card etc) are PRESERVED. */

.dataset-card,
.berita-card,
.infografis-card,
.org-card,
.stat-card,
.feature-card,
.contact-card {
    background: var(--bg-elevated);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    transition:
        transform 0.25s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
        border-color 0.25s ease;
    box-shadow: var(--shadow-card);
}

.dataset-card:hover,
.berita-card:hover,
.infografis-card:hover,
.org-card:hover,
.feature-card:hover {
    transform: translateY(-3px);
    border-color: var(--border-accent);
    box-shadow: var(--shadow-card-hover);
}

/* Stat cards: subtler hover — just glow, no lift */
.stat-card:hover {
    transform: translateY(-2px);
    border-color: var(--accent-200);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.08);
}

/* Contact cards: no lift at all, just border highlight */
.contact-card:hover {
    border-color: var(--accent-200);
    box-shadow: var(--shadow-md);
}
```

**Per-card refinements** (stay in their respective page `<style>` blocks):

#### Dataset Card Refinement

```css
.dataset-card {
    padding: 28px 28px 24px; /* slightly less bottom padding */
}

/* NEW: Top accent line */
.dataset-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 24px;
    right: 24px;
    height: 2px;
    background: var(--accent-gradient);
    border-radius: 0 0 2px 2px;
    opacity: 0;
    transition: opacity var(--transition);
}

.dataset-card:hover::before {
    opacity: 1;
}

.dataset-card {
    position: relative; /* needed for ::before */
    overflow: hidden; /* clip the accent line */
}

.dataset-card-org {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--accent);
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.05em; /* WAS: 0.03em — more tracking */
}

.dataset-card-title {
    font-size: 1.0625rem; /* WAS: 1.05rem */
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 10px;
    color: var(--text-heading); /* WAS: --text-primary — darker */
}
```

#### Berita Card Refinement

```css
.berita-card {
    overflow: hidden; /* already set — keep */
}

/* Image zoom on hover */
.berita-card:hover .berita-card-img {
    transform: scale(1.04);
}

.berita-card-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); /* NEW: slow zoom */
}

.berita-card-body {
    padding: 24px 24px 28px; /* WAS: 24px — extra bottom */
}

.berita-card-date {
    font-size: 0.75rem;
    color: var(--accent); /* WAS: --text-muted — use accent for dates */
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 10px; /* WAS: 8px */
    font-weight: 600; /* NEW: bolder */
}
```

#### Org Card Refinement

```css
/* On home page (compact 4-col), keep current centered layout */
/* On organizations page (3-col), refine: */

.org-avatar {
    width: 52px; /* WAS: 56px — slightly smaller */
    height: 52px;
    border-radius: 12px; /* WAS: 14px — tighter */
    background: var(--accent-gradient); /* Already gradient — keep */
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.2); /* NEW: subtle glow */
}

.org-name {
    font-size: 1.05rem; /* WAS: 1.1rem on org page */
    font-weight: 600;
    color: var(--text-heading);
}

/* NEW: Org link arrow animation */
.org-link::after {
    content: " →";
    display: inline-block;
    transition: transform var(--transition);
}

.org-card:hover .org-link::after {
    transform: translateX(4px);
}
```

**⚠️ GSAP note**: All card class names (`.dataset-card`, `.berita-card`, `.infografis-card`, `.org-card`, `.stat-card`, `.feature-card`, `.contact-card`, `.resource-card`, `.info-item`) are **PRESERVED exactly**. Only CSS properties change. GSAP animates `opacity`, `y`, `transform` — our hover transitions use CSS `transition` which coexists cleanly.

---

### 3.3 Hero Sections — Elevated

#### Home Hero (no changes to HTML structure)

```css
/* ── Home Hero — Deeper, richer ── */
.gallery-wrap {
    background: #020617; /* WAS: #0f172a — near-black for drama */
}

.hero-badge {
    padding: 8px 22px; /* WAS: 6px 20px */
    font-size: 0.78rem; /* WAS: 0.8rem */
    font-weight: 600;
    letter-spacing: 0.06em; /* WAS: 0.04em */
    color: #93c5fd;
    background: rgba(59, 130, 246, 0.12); /* WAS: 0.15 — subtler */
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--radius-pill);
    margin-bottom: 28px; /* WAS: 24px */
    text-transform: uppercase; /* NEW: adds authority */
}

.hero-copy h1 {
    font-size: clamp(
        2.8rem,
        7vw,
        5.5rem
    ); /* WAS: clamp(2.5rem, 6vw, 5rem) — bigger */
    font-weight: 800;
    line-height: 1.02; /* WAS: 1.05 — tighter */
    letter-spacing: -0.04em; /* WAS: -0.03em */
}

.text-gradient {
    background: linear-gradient(
        135deg,
        #60a5fa 0%,
        #818cf8 50%,
        #a78bfa 100%
    ); /* WAS: blue-only — add purple */
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-desc {
    max-width: 600px; /* WAS: 640px — tighter for readability */
    font-size: clamp(1rem, 1.5vw, 1.125rem); /* WAS: up to 1.15rem */
    color: rgba(255, 255, 255, 0.6); /* WAS: 0.7 — subtler for elegance */
    line-height: 1.8; /* WAS: 1.7 — more air */
}

/* ── Hero Buttons — More refined ── */
.btn-primary {
    padding: 15px 36px; /* WAS: 14px 32px */
    font-size: 0.9375rem; /* WAS: 0.95rem */
    font-weight: 600;
    background: var(
        --accent-gradient
    ); /* WAS: solid --accent — gradient adds depth */
    border-radius: var(--radius-pill);
    box-shadow: 0 2px 10px rgba(30, 64, 175, 0.3); /* NEW: resting glow */
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 64, 175, 0.35);
}

.btn-outline-light {
    padding: 15px 36px;
    font-size: 0.9375rem;
    border: 1px solid rgba(255, 255, 255, 0.2); /* WAS: 0.25 — subtler */
    border-radius: var(--radius-pill);
    backdrop-filter: blur(4px); /* NEW: frosted glass effect */
}
```

#### Subpage Heroes (shared pattern)

All subpages currently share the same `.page-hero` with `background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%)`. **Upgrade**:

```css
/* ── Subpage Hero — move to layouts/app.blade.php ── */
.page-hero {
    padding: 128px 0 48px; /* WAS: 120px 0 40px — more space */
    text-align: center;
    background:
        radial-gradient(
            ellipse at 30% 0%,
            rgba(59, 130, 246, 0.06) 0%,
            transparent 60%
        ),
        radial-gradient(
            ellipse at 70% 100%,
            rgba(99, 102, 241, 0.04) 0%,
            transparent 50%
        ),
        linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); /* WAS: simple 2-color gradient */
    border-bottom: 1px solid var(--border-subtle); /* WAS: --border — softer */
}

.page-title {
    font-size: clamp(
        2.2rem,
        5vw,
        3rem
    ); /* WAS: clamp(2rem, 5vw, 2.8rem) — slightly bigger */
    font-weight: 800;
    letter-spacing: -0.035em;
    color: var(--text-heading);
    margin-bottom: 16px; /* WAS: 12px */
}

.page-subtitle {
    font-size: 1.0625rem; /* WAS: 1.05rem */
    color: var(--text-secondary);
    max-width: 580px; /* WAS: 560px */
    margin: 0 auto 36px; /* WAS: 0 auto 32px */
    line-height: 1.75; /* WAS: 1.7 */
}
```

**⚠️ GSAP note**: `.page-hero` class preserved. `.hero-copy`, `.hero-badge`, `.gallery-wrap` all preserved. GSAP animates these on `opacity` and `y`/`scale` — CSS changes are purely visual properties.

---

### 3.4 Search Form — Polished

```css
/* ── Search Form — move to layouts/app.blade.php ── */
.search-form {
    display: flex;
    max-width: 680px;
    width: 100%;
    margin: 0 auto;
    gap: 10px; /* WAS: 12px — tighter */
}

.search-input {
    flex: 1;
    min-width: 280px;
    padding: 15px 24px; /* WAS: 16px 24px / 14px 24px — standardize */
    font-size: 0.9375rem; /* WAS: 1rem — slightly smaller */
    font-family: "Inter", sans-serif;
    color: var(--text-primary);
    background: #fff;
    border: 1.5px solid var(--border); /* WAS: 2px — thinner, more refined */
    border-radius: 12px; /* WAS: 14px */
    outline: none;
    transition:
        border-color var(--transition),
        box-shadow var(--transition),
        background var(--transition);
}

.search-input::placeholder {
    color: var(--text-muted);
    font-weight: 400;
}

.search-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow); /* WAS: 4px — tighter ring */
    background: #fefefe; /* NEW: very subtle lighten */
}

.search-btn {
    padding: 15px 32px;
    font-size: 0.9375rem;
    font-weight: 600;
    font-family: "Inter", sans-serif;
    color: #fff;
    background: var(--accent-gradient); /* WAS: solid --accent */
    border: none;
    border-radius: 12px;
    transition: all var(--transition);
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.2); /* NEW */
}

.search-btn:hover {
    background: linear-gradient(135deg, #1e3a8a, #1e40af);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(30, 64, 175, 0.3);
}
```

**⚠️ GSAP note**: `.search-form` is animated on the home page (text entrance). Class name preserved.

---

### 3.5 Footer — Professional Upgrade

**File**: [`layouts/app.blade.php`](resources/views/layouts/app.blade.php)

```css
/* ── Footer — Elevated ── */
.site-footer {
    background: linear-gradient(
        180deg,
        #0c1222 0%,
        #0f172a 100%
    ); /* WAS: flat #0f172a */
    color: #e2e8f0;
    padding: 80px 24px 0; /* WAS: 64px 24px 0 — more spacious */
    position: relative;
}

/* NEW: Subtle top border gradient */
.site-footer::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(59, 130, 246, 0.3),
        transparent
    );
}

.footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr;
    gap: 56px; /* WAS: 48px */
}

.footer-brand h3 {
    font-size: 1.2rem; /* WAS: 1.15rem */
    font-weight: 700;
    color: #fff;
    margin-bottom: 14px;
    letter-spacing: -0.01em; /* NEW */
}

.footer-brand p {
    font-size: 0.875rem; /* WAS: 0.88rem */
    color: #94a3b8;
    line-height: 1.75; /* WAS: 1.7 */
    max-width: 300px;
}

.footer-section h4 {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em; /* WAS: 0.1em — more tracking */
    color: #475569; /* WAS: #64748b — slightly brighter */
    margin-bottom: 20px; /* WAS: 16px */
}

.footer-section a {
    font-size: 0.875rem;
    color: #94a3b8;
    transition:
        color var(--transition),
        transform var(--transition);
    display: inline-block; /* NEW: enables transform */
}

.footer-section a:hover {
    color: #e2e8f0; /* WAS: #fff — slightly softer */
    transform: translateX(2px); /* NEW: subtle slide */
}

/* ── Social Links — Refined ── */
.footer-social a {
    width: 38px; /* WAS: 36px */
    height: 38px;
    border-radius: 10px; /* WAS: 8px */
    background: rgba(255, 255, 255, 0.06); /* WAS: 0.08 — subtler */
    border: 1px solid rgba(255, 255, 255, 0.08); /* NEW: visible border */
    color: #94a3b8;
    font-size: 0.8rem;
    font-weight: 700;
    transition: all var(--transition);
}

.footer-social a:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    transform: translateY(-2px); /* NEW: subtle lift */
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3); /* NEW: glow */
}

/* ── Visitor Stats — Enhanced ── */
.footer-visitor {
    max-width: 1200px;
    margin: 56px auto 0; /* WAS: 48px */
    padding: 28px 0; /* WAS: 24px */
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    justify-content: center;
    gap: 48px; /* WAS: 32px — more space */
    flex-wrap: wrap;
}

.visitor-item .v-num {
    font-size: 1.5rem; /* WAS: 1.3rem — bigger */
    font-weight: 800; /* WAS: 700 */
    color: #fff;
    letter-spacing: -0.02em; /* NEW */
}

.visitor-item .v-label {
    font-size: 0.7rem; /* WAS: 0.75rem — smaller */
    color: #475569; /* WAS: #64748b */
    text-transform: uppercase;
    letter-spacing: 0.08em; /* WAS: 0.05em */
    margin-top: 4px; /* WAS: 2px */
}

/* ── Footer Bottom ── */
.footer-bottom {
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 0; /* WAS: 24px */
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    text-align: center;
    font-size: 0.8125rem; /* WAS: 0.78rem */
    color: #475569; /* WAS: #64748b — slightly brighter */
    margin-top: 0; /* WAS: 24px — remove double margin */
}
```

**⚠️ GSAP note**: Footer has no GSAP animations. Safe to change freely.

---

### 3.6 Buttons — Consistent System

Currently buttons are defined ad-hoc in each page. Consolidate to layout.

```css
/* ── Button System — add to layouts/app.blade.php ── */

/* Primary */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 32px;
    font-size: 0.9375rem;
    font-weight: 600;
    color: #fff;
    background: var(--accent-gradient);
    border: none;
    border-radius: var(--radius-pill);
    transition: all var(--transition);
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.2);
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30, 64, 175, 0.3);
}

/* Small variant */
.btn-sm {
    padding: 9px 20px;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: 8px;
}

.btn-sm.btn-primary {
    border-radius: 8px;
}

/* Outline */
.btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 32px;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--accent);
    background: transparent;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-pill);
    transition: all var(--transition);
    cursor: pointer;
}

.btn-outline:hover {
    border-color: var(--accent);
    background: var(--accent-glow);
    transform: translateY(-1px);
}
```

---

### 3.7 Format Badges — Unified

Currently duplicated across 3 files. Move to layout.

```css
/* ── Format Badges — add to layouts/app.blade.php ── */
.format-badge,
.format-pill,
.format-pill-lg {
    display: inline-block;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-radius: 6px;
}

.format-badge {
    padding: 3px 10px;
    font-size: 0.7rem;
}
.format-pill {
    padding: 8px 16px;
    font-size: 0.78rem;
}
.format-pill-lg {
    padding: 10px 24px;
    font-size: 0.9rem;
}

.format-badge.csv,
.format-pill.csv,
.format-pill-lg.csv {
    background: #dcfce7;
    color: #166534;
}
.format-badge.pdf,
.format-pill.pdf,
.format-pill-lg.pdf {
    background: #fee2e2;
    color: #991b1b;
}
.format-badge.xls,
.format-badge.xlsx,
.format-pill.xls,
.format-pill.xlsx,
.format-pill-lg.xls,
.format-pill-lg.xlsx {
    background: #d1fae5;
    color: #065f46;
}
.format-badge.json,
.format-pill.json,
.format-pill-lg.json {
    background: #fef3c7;
    color: #92400e;
}
.format-badge.xml,
.format-pill.xml {
    background: #e0e7ff;
    color: #3730a3;
}
.format-badge.default,
.format-pill.default,
.format-pill-lg.default {
    background: var(--bg-secondary);
    color: var(--text-secondary);
}
```

---

### 3.8 Breadcrumb — Refined

```css
/* ── Breadcrumb — add to layouts/app.blade.php ── */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8125rem; /* WAS: 0.85rem */
    color: var(--text-muted);
    margin-bottom: 28px; /* WAS: 24px */
    flex-wrap: wrap;
}

.breadcrumb a {
    color: var(--accent-light); /* WAS: --accent — lighter for breadcrumbs */
    transition: color var(--transition);
}

.breadcrumb a:hover {
    color: var(--accent);
    text-decoration: underline;
    text-underline-offset: 2px; /* NEW: offset underline */
}

.breadcrumb .sep {
    color: var(--border-hover); /* WAS: --text-muted — lighter separator */
    font-size: 0.7rem;
}
```

---

### 3.9 Pagination — Enhanced

```css
/* ── Pagination — move to layouts/app.blade.php ── */
.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-top: 56px; /* WAS: 48px */
}

.page-btn {
    padding: 11px 24px;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--accent);
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-xs);
    transition: all var(--transition);
}

.page-btn:hover {
    background: var(--accent-glow);
    border-color: var(--accent);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.page-info {
    font-size: 0.875rem;
    color: var(--text-caption);
    font-weight: 500;
}
```

---

## 4. Page-by-Page Changes

### 4.1 Home Page — [`home.blade.php`](resources/views/home.blade.php)

#### CSS Changes

| Section             | Change                | Detail                                                |
| ------------------- | --------------------- | ----------------------------------------------------- |
| Hero                | Deepen background     | `#0f172a` → `#020617`                                 |
| Hero                | Enlarge h1            | `clamp(2.5rem,6vw,5rem)` → `clamp(2.8rem,7vw,5.5rem)` |
| Hero                | Richer gradient text  | Add purple to gradient                                |
| Hero buttons        | Gradient + glow       | Solid blue → `--accent-gradient` + shadow             |
| Gallery items       | Rounded corners       | `20px` → `16px` (more professional)                   |
| Section backgrounds | Warmer, subtler tints | See below                                             |
| Stats section       | Add top/bottom border | Separate from adjacent sections                       |
| Search section      | Centered better       | Already centered; refine input focus                  |
| Dataset cards       | Add top accent line   | `::before` pseudo-element on hover                    |
| Berita cards        | Image zoom on hover   | `transform: scale(1.04)` on `.berita-card-img`        |
| Org cards (preview) | Subtle glow on avatar | `box-shadow` on `.org-icon`                           |

**Section background palette refinement**:

```css
/* Subtler, more cohesive tints */
.slides-wrapper > .section.search-section {
    background: #f8fafc;
} /* WAS: #eef2ff — less saturated */
.slides-wrapper > .section.recent-section {
    background: #ffffff;
} /* unchanged */
.slides-wrapper > .section.berita-section {
    background: #fffbf5;
} /* WAS: #fef7ee — softer warm */
.slides-wrapper > .section.infografis-section {
    background: #f0f9ff;
} /* WAS: #eef8ff — barely there */
.slides-wrapper > .section.org-section {
    background: #faf8ff;
} /* WAS: #f5f3ff — lighter purple */
```

#### HTML Changes

**None**. All selectors preserved. The `#gallery-satudata`, `.gallery__item`, `.hero-copy`, `.gallery-wrap`, `.slides-wrapper`, `.section`, `.container`, `.dataset-card`, `.berita-card`, `.infografis-card`, `.org-card`, `.stat-card`, `.section-title`, `.section-desc`, `.search-form` classes are all untouched.

#### GSAP Compatibility

| Selector                       | Animation Type        | Status       |
| ------------------------------ | --------------------- | ------------ |
| `#gallery-satudata`            | Flip target           | ✅ Preserved |
| `.gallery__item`               | Flip items            | ✅ Preserved |
| `.hero-copy`                   | Fade/scale on scroll  | ✅ Preserved |
| `.gallery-wrap`                | ScrollTrigger pin     | ✅ Preserved |
| `.slides-wrapper`              | Parent container      | ✅ Preserved |
| `.section`                     | Scroll-pinned panels  | ✅ Preserved |
| `.container` (inside sections) | Content scroll offset | ✅ Preserved |
| `.dataset-card`                | Stagger entrance      | ✅ Preserved |
| `.berita-card`                 | Stagger entrance      | ✅ Preserved |
| `.infografis-card`             | Stagger entrance      | ✅ Preserved |
| `.org-card`                    | Stagger entrance      | ✅ Preserved |
| `.stat-card`                   | Stagger entrance      | ✅ Preserved |
| `.section-title`               | Text entrance         | ✅ Preserved |
| `.section-desc`                | Text entrance         | ✅ Preserved |
| `.search-form`                 | Text entrance         | ✅ Preserved |

---

### 4.2 Dataset Search — [`datasets/search.blade.php`](resources/views/datasets/search.blade.php)

#### CSS Changes

| Change                                                                                        | Detail                             |
| --------------------------------------------------------------------------------------------- | ---------------------------------- |
| Remove `.container` definition                                                                | Moved to layout                    |
| Remove `.page-hero` definition                                                                | Moved to layout                    |
| Remove `.search-form`, `.search-input`, `.search-btn` definitions                             | Moved to layout                    |
| Remove `.dataset-card` base definition                                                        | Moved to layout                    |
| Keep `.card-header`, `.card-org`, `.card-formats`, `.card-title`, `.card-notes`, `.card-meta` | Page-specific                      |
| Remove format badge definitions                                                               | Moved to layout                    |
| Remove `.pagination` definitions                                                              | Moved to layout                    |
| Refine `.results-info`                                                                        | Better spacing, subtle left border |

```css
/* ── Results Info — enhanced ── */
.results-info {
    margin-bottom: 36px; /* WAS: 32px */
    padding-left: 16px; /* NEW */
    border-left: 3px solid var(--accent-200); /* NEW: visual accent */
    font-size: 0.9375rem;
    color: var(--text-secondary);
}

/* ── Dataset Grid — 2-col with larger gaps ── */
.dataset-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px; /* WAS: 20px */
}

/* ── Card refinements specific to search page ── */
.card-org {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 0.05em; /* WAS: 0.04em */
}

.card-title {
    font-size: 1.0625rem; /* WAS: 1.1rem — slightly smaller */
    font-weight: 700;
    line-height: 1.4;
    color: var(--text-heading); /* WAS: --text-primary */
}
```

#### HTML Changes

**None**. All existing selectors preserved.

#### GSAP Compatibility

| Selector        | Status                             |
| --------------- | ---------------------------------- |
| `.page-hero`    | ✅ Class preserved, moved CSS only |
| `.dataset-card` | ✅ Class preserved                 |
| `.dataset-grid` | ✅ Trigger preserved               |

---

### 4.3 Dataset Detail — [`datasets/show.blade.php`](resources/views/datasets/show.blade.php)

#### CSS Changes

| Change                                           | Detail                                                     |
| ------------------------------------------------ | ---------------------------------------------------------- |
| Remove `.container`, `.page-hero`, `.breadcrumb` | Moved to layout                                            |
| Remove format pill colors                        | Moved to layout                                            |
| Refine `.dataset-title`                          | Use `--text-heading`, add gradient underline on hover-none |
| Refine `.resource-card`                          | Better structure, subtle left border                       |
| Refine `.extras-table`                           | Cleaner header, striped rows softer                        |

```css
/* ── Resource Card — enhanced ── */
.resource-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px;
    background: #fff;
    border: 1px solid var(--border);
    border-left: 3px solid var(--accent-200); /* NEW: left accent border */
    border-radius: var(--radius-sm);
    margin-bottom: 12px;
    transition: all var(--transition);
}

.resource-card:hover {
    border-left-color: var(--accent); /* NEW: accent intensifies */
    border-color: var(--border-hover);
    box-shadow: var(--shadow-md);
    transform: translateX(2px); /* NEW: subtle slide right */
}

/* ── Tags — more polished ── */
.tag {
    padding: 6px 16px;
    background: var(--accent-glow); /* WAS: --bg-secondary — branded */
    color: var(--accent); /* WAS: --text-secondary — branded */
    border: 1px solid var(--border-accent); /* NEW: blue border */
    border-radius: var(--radius-pill);
    font-size: 0.8rem;
    font-weight: 500;
    transition: all var(--transition);
}

.tag:hover {
    background: var(--accent-lighter);
}

/* ── Extras Table — cleaner ── */
.extras-table {
    width: 100%;
    border-collapse: separate; /* WAS: collapse — enables border-radius */
    border-spacing: 0;
    border-radius: var(--radius-sm);
    overflow: hidden;
    border: 1px solid var(--border);
}

.extras-table thead th {
    background: var(--bg-tertiary); /* WAS: --bg-secondary */
    font-size: 0.8125rem; /* WAS: 0.9rem — smaller */
    font-weight: 600;
    color: var(--text-caption);
    text-transform: uppercase; /* NEW */
    letter-spacing: 0.04em; /* NEW */
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
}

.extras-table tbody tr:nth-child(even) {
    background: rgba(248, 250, 252, 0.5); /* WAS: --bg-secondary — subtler */
}
```

#### HTML Changes

**None**.

#### GSAP Compatibility

| Selector             | Status                          |
| -------------------- | ------------------------------- |
| `.page-hero`         | ✅ Preserved                    |
| `.resource-card`     | ✅ Preserved — stagger entrance |
| `.resources-section` | ✅ ScrollTrigger trigger        |

---

### 4.4 Resource Detail — [`resources/show.blade.php`](resources/views/resources/show.blade.php)

#### CSS Changes

| Change                                           | Detail                                       |
| ------------------------------------------------ | -------------------------------------------- |
| Remove `.container`, `.page-hero`, `.breadcrumb` | Moved to layout                              |
| Remove format pill colors                        | Moved to layout                              |
| Refine `.info-item`                              | Subtle left border, hover state              |
| Refine `.download-box`                           | Better visual hierarchy, gradient background |

```css
/* ── Info Item — enhanced ── */
.info-item {
    background: #fff;
    border: 1px solid var(--border);
    padding: 24px;
    border-radius: var(--radius-sm);
    transition: all var(--transition);
}

.info-item:hover {
    border-color: var(--border-hover);
    box-shadow: var(--shadow-sm);
}

.info-label {
    font-size: 0.6875rem; /* WAS: 0.72rem — smaller */
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em; /* WAS: 0.08em — more tracking */
    color: var(--text-muted);
    margin-bottom: 8px; /* WAS: 6px */
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-heading); /* WAS: --text-primary */
}

/* ── Download Box — premium feel ── */
.download-box {
    padding: 56px 48px; /* WAS: 48px all */
    background:
        radial-gradient(
            ellipse at 50% 0%,
            rgba(59, 130, 246, 0.04) 0%,
            transparent 70%
        ),
        var(--bg-secondary); /* WAS: flat --bg-secondary */
    border: 1px solid var(--border);
    border-radius: var(--radius);
    text-align: center;
}

.btn-lg {
    padding: 18px 44px; /* WAS: 16px 40px */
    font-size: 1.0625rem; /* WAS: 1.05rem */
    font-weight: 700;
    background: var(--accent-gradient);
    border-radius: var(--radius-sm); /* WAS: 14px */
    box-shadow: 0 4px 14px rgba(30, 64, 175, 0.25); /* NEW */
}

.download-url {
    margin-top: 20px; /* WAS: 16px */
    font-size: 0.75rem; /* WAS: 0.78rem */
    color: var(--text-muted);
    background: rgba(0, 0, 0, 0.02); /* NEW: subtle bg for URL */
    padding: 8px 16px; /* NEW */
    border-radius: 6px; /* NEW */
    display: inline-block; /* NEW */
}
```

#### GSAP Compatibility

| Selector            | Status                            |
| ------------------- | --------------------------------- |
| `.page-hero`        | ✅ Preserved                      |
| `.info-item`        | ✅ Preserved — stagger entrance   |
| `.info-grid`        | ✅ ScrollTrigger trigger          |
| `.download-box`     | ✅ Preserved — entrance animation |
| `.download-section` | ✅ ScrollTrigger trigger          |

---

### 4.5 Organizations — [`organizations.blade.php`](resources/views/organizations.blade.php)

#### CSS Changes

| Change                                                             | Detail                                 |
| ------------------------------------------------------------------ | -------------------------------------- |
| Remove `.container`, `.page-hero`, `.page-title`, `.page-subtitle` | Moved to layout                        |
| Refine `.stats-pill`                                               | Subtle animation dot                   |
| Refine `.org-card`                                                 | Better avatar, arrow animation on link |
| Change grid to 3-col gap 28px                                      | WAS: 24px                              |

```css
/* ── Stats Pill — enhanced ── */
.stats-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(30, 64, 175, 0.06); /* WAS: --accent-lighter — subtler */
    color: var(--accent);
    padding: 12px 28px; /* WAS: 10px 24px */
    border-radius: var(--radius-pill);
    font-size: 0.9375rem; /* WAS: 0.9rem */
    font-weight: 500;
    border: 1px solid rgba(30, 64, 175, 0.1);
}

.stats-number {
    font-weight: 800; /* WAS: 700 */
    font-size: 1.125rem; /* WAS: 1.05rem */
}

/* ── Org Grid ── */
.org-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px; /* WAS: 24px */
}

/* ── Org Card ── */
.org-card {
    padding: 36px 28px; /* WAS: 32px 28px */
}
```

#### GSAP Compatibility

| Selector      | Status                            |
| ------------- | --------------------------------- |
| `.page-hero`  | ✅ Preserved                      |
| `.stats-pill` | ✅ Preserved — entrance animation |
| `.org-card`   | ✅ Preserved — stagger entrance   |
| `.org-grid`   | ✅ ScrollTrigger trigger          |

---

### 4.6 Publikasi — [`publikasi.blade.php`](resources/views/publikasi.blade.php)

#### CSS Changes

| Change                                                             | Detail                                |
| ------------------------------------------------------------------ | ------------------------------------- |
| Remove `.container`, `.page-hero`, `.page-title`, `.page-subtitle` | Moved to layout                       |
| Refine `.coming-soon`                                              | Better visual, animated pulse on icon |

```css
/* ── Coming Soon — more polished placeholder ── */
.coming-soon {
    text-align: center;
    padding: 96px 24px; /* WAS: 80px 24px — more spacious */
    background: var(--bg-secondary);
    border: 2px dashed var(--border);
    border-radius: var(--radius);
}

.coming-soon-icon {
    font-size: 3.5rem; /* WAS: 3rem */
    margin-bottom: 20px; /* WAS: 16px */
    display: inline-block;
    animation: pulse-soft 3s ease-in-out infinite; /* NEW: gentle pulse */
}

@keyframes pulse-soft {
    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
}

.coming-soon h3 {
    font-size: 1.375rem; /* WAS: 1.3rem */
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 10px;
}

.coming-soon p {
    font-size: 0.9375rem; /* WAS: 0.95rem */
    color: var(--text-secondary);
    max-width: 420px; /* WAS: 400px */
    margin: 0 auto;
    line-height: 1.7;
}
```

#### GSAP Compatibility

| Selector       | Status                          |
| -------------- | ------------------------------- |
| `.page-hero`   | ✅ Preserved                    |
| `.coming-soon` | ✅ Preserved — stagger entrance |

---

### 4.7 Tentang — [`tentang.blade.php`](resources/views/tentang.blade.php)

#### CSS Changes

| Change                                                             | Detail                   |
| ------------------------------------------------------------------ | ------------------------ |
| Remove `.container`, `.page-hero`, `.page-title`, `.page-subtitle` | Moved to layout          |
| Refine `.about-content`                                            | Better paragraph spacing |
| Refine `.feature-card`                                             | Gradient icon background |
| Refine `.legal-section`                                            | Better list styling      |
| Refine `.contact-card`                                             | Centered with icon glow  |
| Remove inline `style=""` on h2                                     | Use class instead        |

```css
/* ── About Content ── */
.about-content h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-heading); /* WAS: --text-primary */
    margin-bottom: 20px; /* WAS: 16px */
    letter-spacing: -0.01em; /* NEW */
}

.about-content p {
    font-size: 0.9375rem; /* WAS: 0.95rem */
    color: var(--text-secondary);
    line-height: 1.85; /* WAS: 1.8 — slightly more */
    margin-bottom: 18px; /* WAS: 16px */
}

/* ── Feature Cards — enhanced ── */
.feature-card {
    padding: 32px; /* WAS: 28px */
}

.feature-icon {
    font-size: 1.75rem; /* WAS: 1.5rem */
    margin-bottom: 14px;
    width: 48px; /* NEW: contained in circle */
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-glow); /* NEW: subtle background */
    border-radius: 12px; /* NEW */
}

.feature-card h3 {
    font-size: 1.0625rem; /* WAS: 1rem */
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 10px; /* WAS: 8px */
}

/* ── Legal Section ── */
.legal-section {
    padding: 72px 0; /* WAS: 64px */
    background: var(
        --bg-tertiary
    ); /* WAS: --bg-secondary — slightly different */
}

.legal-section ul {
    padding-left: 0; /* WAS: 24px — remove default */
    list-style: none;
}

.legal-section li {
    position: relative;
    padding-left: 28px; /* NEW: custom bullet space */
    margin-bottom: 12px; /* WAS: 8px */
}

.legal-section li::before {
    content: "✦"; /* NEW: custom bullet */
    position: absolute;
    left: 0;
    color: var(--accent);
    font-size: 0.75rem;
}

/* ── Contact Cards ── */
.contact-card {
    padding: 36px; /* WAS: 32px */
    text-align: center;
    transition: all var(--transition);
}

.contact-card-icon {
    font-size: 2rem;
    margin-bottom: 18px; /* WAS: 16px */
    display: inline-flex;
    width: 56px; /* NEW */
    height: 56px;
    align-items: center;
    justify-content: center;
    background: var(--accent-glow); /* NEW */
    border-radius: 14px; /* NEW */
}
```

#### HTML Changes

Replace the inline style on the "Hubungi Kami" heading:

```html
<!-- BEFORE -->
<h2
    style="font-size:1.5rem; font-weight:700; margin-bottom:32px; text-align:center;"
>
    Hubungi Kami
</h2>

<!-- AFTER -->
<h2 class="section-heading" style="text-align:center; margin-bottom:32px;">
    Hubungi Kami
</h2>
```

Or better, add a `.section-heading--center` utility.

#### GSAP Compatibility

| Selector          | Status                          |
| ----------------- | ------------------------------- |
| `.page-hero`      | ✅ Preserved                    |
| `.about-content`  | ✅ Preserved — slide entrance   |
| `.about-section`  | ✅ ScrollTrigger trigger        |
| `.feature-card`   | ✅ Preserved — stagger entrance |
| `.about-features` | ✅ ScrollTrigger trigger        |
| `.contact-card`   | ✅ Preserved — stagger entrance |
| `.contact-grid`   | ✅ ScrollTrigger trigger        |

---

## 5. GSAP Compatibility Matrix

### 5.1 Critical Selectors — DO NOT RENAME OR REMOVE

| Selector                         | File                                          | Animation                         |
| -------------------------------- | --------------------------------------------- | --------------------------------- |
| `#gallery-satudata`              | `home.blade.php`                              | Flip target gallery               |
| `.gallery__item`                 | `home.blade.php`                              | Flip items                        |
| `.gallery--bento`                | `home.blade.php`                              | Grid layout state                 |
| `.gallery--final`                | `home.blade.php`                              | Final state class (toggled by JS) |
| `.hero-copy`                     | `home.blade.php`                              | Fade/scale on scroll              |
| `.gallery-wrap`                  | `home.blade.php`                              | ScrollTrigger pin trigger         |
| `.slides-wrapper`                | `home.blade.php`                              | Parent for section selection      |
| `.section`                       | `home.blade.php`                              | Scroll-pinned panels              |
| `.container` (inside `.section`) | `home.blade.php`                              | Content Y-offset scroll           |
| `.dataset-card`                  | `home.blade.php`, `datasets/search.blade.php` | Card stagger entrance             |
| `.berita-card`                   | `home.blade.php`                              | Card stagger entrance             |
| `.infografis-card`               | `home.blade.php`                              | Card stagger entrance             |
| `.org-card`                      | `home.blade.php`, `organizations.blade.php`   | Card stagger entrance             |
| `.stat-card`                     | `home.blade.php`                              | Card stagger entrance             |
| `.section-title`                 | `home.blade.php`                              | Text entrance                     |
| `.section-desc`                  | `home.blade.php`                              | Text entrance                     |
| `.search-form`                   | `home.blade.php`                              | Text entrance                     |
| `.page-hero`                     | All subpages                                  | Entrance animation                |
| `.resource-card`                 | `datasets/show.blade.php`                     | Card stagger                      |
| `.info-item`                     | `resources/show.blade.php`                    | Grid stagger                      |
| `.download-box`                  | `resources/show.blade.php`                    | Entrance animation                |
| `.coming-soon`                   | `publikasi.blade.php`                         | Stagger entrance                  |
| `.about-content`                 | `tentang.blade.php`                           | Slide entrance                    |
| `.feature-card`                  | `tentang.blade.php`                           | Stagger entrance                  |
| `.contact-card`                  | `tentang.blade.php`                           | Stagger entrance                  |
| `.stats-pill`                    | `organizations.blade.php`                     | Entrance animation                |

### 5.2 Safe to Modify

These properties can be changed WITHOUT affecting GSAP:

- ✅ `background`, `background-color`, `background-image`
- ✅ `border`, `border-color`, `border-radius`
- ✅ `padding`, `margin` (except on pinned elements)
- ✅ `font-size`, `font-weight`, `color`, `letter-spacing`
- ✅ `box-shadow`
- ✅ CSS `transition` property (GSAP overrides inline transforms)
- ✅ `::before`, `::after` pseudo-elements (GSAP doesn't target these)

### 5.3 CAUTION — Modify Carefully

- ⚠️ `width`, `height` on `.gallery__item` — affects Flip state calculation
- ⚠️ `transform` in CSS — GSAP sets inline `transform`, CSS transitions may conflict. Use `will-change: transform` only on hover-animated cards, NOT on GSAP-animated cards at rest.
- ⚠️ `position` on `.section`, `.gallery-wrap` — pinning depends on this
- ⚠️ `overflow` on `.section`, `.gallery-wrap` — affects pin behavior
- ⚠️ `display` or `grid` changes on `.gallery--bento` — affects Flip calculation

### 5.4 CSS Transition vs GSAP Conflict Prevention

Cards like `.dataset-card` have both:

- CSS `transition: all var(--transition)` for hover effects
- GSAP `gsap.from()` for scroll-triggered entrance

**This works fine** because:

1. GSAP sets `opacity` and `transform` directly via inline styles during entrance
2. After GSAP finishes, CSS `transition` takes over for hover `transform` and `box-shadow`
3. GSAP's `clearProps` is not used here, so the final state persists

**Rule**: Do NOT add `transition: transform` to elements that GSAP is currently animating during scroll. The current setup is correct — GSAP entrance completes, THEN CSS hover works.

---

## 6. Implementation Order

### Phase 1: Foundation (Layout File) — Estimated: 2-3 hours

**File**: [`layouts/app.blade.php`](resources/views/layouts/app.blade.php)

1. **Update `:root` variables** — new color palette, shadows, spacing tokens
2. **Add shared component styles** — `.container`, `.page-hero`, `.page-title`, `.page-subtitle`, `.breadcrumb`, `.search-form/.search-input/.search-btn`, format badges, pagination, button system
3. **Update `.site-nav`** — new heights, active indicator, scroll-aware class
4. **Update `.site-footer`** — gradient bg, accent border, refined social links, visitor stats
5. **Add scroll-aware nav JS** — `window.scroll` listener for `.scrolled` class
6. **Update nav height** — `64px` → `72px` (also update `.page-hero` padding-top from `120px` → `128px`)

**Test**: Every page should still look correct (just with improved nav/footer). GSAP animations should work unchanged.

### Phase 2: Home Page — Estimated: 2-3 hours

**File**: [`home.blade.php`](resources/views/home.blade.php)

1. **Remove `.container` definition** (now in layout)
2. **Update hero styles** — darker bg, bigger h1, richer gradient, refined buttons
3. **Update section backgrounds** — subtler tints
4. **Add card refinements** — dataset card `::before` accent, berita image zoom, org avatar glow
5. **Update stat cards** — more refined hover
6. **Verify GSAP** — scroll through entire page, confirm Flip animation, section stacking, card entrances all work

**Test**: Full scroll test. Flip animation, section pin/stack, all card entrances, resize behavior.

### Phase 3: Dataset Pages — Estimated: 1-2 hours

**Files**: [`datasets/search.blade.php`](resources/views/datasets/search.blade.php), [`datasets/show.blade.php`](resources/views/datasets/show.blade.php)

1. **Search page**: Remove duplicated styles (container, page-hero, search form, dataset-card base, format badges, pagination). Refine remaining styles.
2. **Show page**: Remove duplicated styles. Enhance resource cards (left border), tags (branded), extras table (cleaner header).
3. **Verify GSAP** — page-hero entrance, card stagger on search, resource-card stagger on detail.

### Phase 4: Resource Detail — Estimated: 1 hour

**File**: [`resources/show.blade.php`](resources/views/resources/show.blade.php)

1. Remove duplicated styles
2. Enhance info-item grid (hover, label styling)
3. Upgrade download-box (gradient background, URL display)
4. **Verify GSAP** — info-item stagger, download-box entrance

### Phase 5: Remaining Pages — Estimated: 1-2 hours

**Files**: [`organizations.blade.php`](resources/views/organizations.blade.php), [`publikasi.blade.php`](resources/views/publikasi.blade.php), [`tentang.blade.php`](resources/views/tentang.blade.php)

1. **Organizations**: Remove duplicated styles. Enhance stats-pill, org-card.
2. **Publikasi**: Remove duplicated styles. Enhance coming-soon with pulse animation.
3. **Tentang**: Remove duplicated styles. Enhance feature-card icons, legal list, contact cards. Fix inline `style=""` on heading.
4. **Verify GSAP** on all three pages.

### Phase 6: Final QA — Estimated: 1 hour

1. Full cross-page navigation test
2. Mobile responsive check (resize to 768px, 640px, 480px)
3. GSAP animation regression test (every animated element)
4. Color contrast check (ensure WCAG AA on all text)
5. Performance check (no layout shifts from CSS changes)

---

## Appendix: Files Modified Summary

| File                        | Changes                                                  | Lines Reduced              |
| --------------------------- | -------------------------------------------------------- | -------------------------- |
| `layouts/app.blade.php`     | Add shared styles, update nav/footer, new `:root`        | +~200 lines                |
| `home.blade.php`            | Remove `.container`, refine hero/cards/sections          | -~30 lines                 |
| `datasets/search.blade.php` | Remove 6 duplicated blocks, refine remaining             | -~120 lines                |
| `datasets/show.blade.php`   | Remove 5 duplicated blocks, enhance resource cards/table | -~100 lines                |
| `resources/show.blade.php`  | Remove 4 duplicated blocks, enhance info/download        | -~80 lines                 |
| `organizations.blade.php`   | Remove 3 duplicated blocks, enhance org cards            | -~40 lines                 |
| `publikasi.blade.php`       | Remove 3 duplicated blocks, enhance coming-soon          | -~30 lines                 |
| `tentang.blade.php`         | Remove 3 duplicated blocks, enhance features/contact     | -~50 lines                 |
| **NET**                     |                                                          | **~250 fewer lines total** |

---

## Appendix: Before/After Visual Summary

### Color Shift

| Element           | Before                  | After                                     |
| ----------------- | ----------------------- | ----------------------------------------- |
| Accent            | `#2563eb` (bright blue) | `#1e40af` (deep government blue)          |
| Headings          | `#0f172a`               | `#020617` (near-black)                    |
| Hero background   | `#0f172a`               | `#020617`                                 |
| Card hover shadow | Generic gray            | Blue-tinted `rgba(30,64,175,0.12)`        |
| Active nav        | Filled background       | Bottom indicator line                     |
| Footer            | Flat `#0f172a`          | Gradient `#0c1222` → `#0f172a` + top glow |

### Typography Shift

| Element                    | Before                         | After                            |
| -------------------------- | ------------------------------ | -------------------------------- |
| Hero h1                    | `clamp(2.5rem,6vw,5rem)`       | `clamp(2.8rem,7vw,5.5rem)`       |
| Page titles                | `clamp(2rem,5vw,2.8rem)`       | `clamp(2.2rem,5vw,3rem)`         |
| Body text                  | `0.88rem-0.95rem` inconsistent | `0.9375rem` standardized         |
| Nav height                 | `64px`                         | `72px`                           |
| Letter spacing on headings | `-0.02em` to `-0.03em`         | `-0.02em` to `-0.04em` (tighter) |

### Shadow Shift

| State      | Before                         | After                                                                    |
| ---------- | ------------------------------ | ------------------------------------------------------------------------ |
| Card rest  | `0 1px 2px rgba(0,0,0,0.04)`   | `0 1px 3px rgba(0,0,0,0.04), 0 0 0 1px rgba(0,0,0,0.02)`                 |
| Card hover | `0 12px 40px rgba(0,0,0,0.08)` | `0 12px 28px -8px rgba(30,64,175,0.12), 0 4px 8px -4px rgba(0,0,0,0.04)` |

---

_Document version: 1.0 — Created 2026-03-27_  
_Total estimated implementation time: 8-12 hours_

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hero Animasi Satu Data Muara Enim</title>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/Flip.min.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #07111f;
      color: #fff;
    }

    .gallery-wrap {
      position: relative;
      width: 100%;
      height: 100vh;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .gallery {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .gallery--bento {
      display: grid;
      gap: 1vh;
      grid-template-columns: repeat(3, 32vw);
      grid-template-rows: repeat(4, 22vh);
      justify-content: center;
      align-content: center;
      padding: 1vh;
    }

    .gallery--final.gallery--bento {
      grid-template-columns: repeat(3, 100vw);
      grid-template-rows: repeat(4, 49.5vh);
      gap: 1vh;
    }

    .gallery__item {
      position: relative;
      overflow: hidden;
      border-radius: 20px;
      background: linear-gradient(135deg, #0d1b2a, #1b263b);
      border: 1px solid rgba(255,255,255,0.08);
    }

    .gallery__item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      filter: brightness(0.75);
    }

    .gallery__label {
      position: absolute;
      left: 16px;
      bottom: 16px;
      z-index: 2;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: 0.2px;
      text-shadow: 0 2px 10px rgba(0,0,0,0.45);
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.5), rgba(0,0,0,0.1));
      z-index: 1;
    }

    .gallery--bento .gallery__item:nth-child(1) { grid-area: 1 / 1 / 3 / 2; }
    .gallery--bento .gallery__item:nth-child(2) { grid-area: 1 / 2 / 2 / 3; }
    .gallery--bento .gallery__item:nth-child(3) { grid-area: 2 / 2 / 4 / 3; }
    .gallery--bento .gallery__item:nth-child(4) { grid-area: 1 / 3 / 3 / 4; }
    .gallery--bento .gallery__item:nth-child(5) { grid-area: 3 / 1 / 4 / 2; }
    .gallery--bento .gallery__item:nth-child(6) { grid-area: 3 / 3 / 5 / 4; }
    .gallery--bento .gallery__item:nth-child(7) { grid-area: 4 / 1 / 5 / 2; }
    .gallery--bento .gallery__item:nth-child(8) { grid-area: 4 / 2 / 5 / 3; }

    .hero-copy {
      position: absolute;
      z-index: 20;
      text-align: center;
      max-width: 900px;
      padding: 0 24px;
      pointer-events: none;
    }

    .hero-copy h1 {
      margin: 0 0 12px;
      font-size: clamp(2rem, 5vw, 4.5rem);
      line-height: 1.05;
    }

    .hero-copy p {
      margin: 0 auto;
      max-width: 720px;
      font-size: clamp(1rem, 1.7vw, 1.2rem);
      color: rgba(255,255,255,0.88);
    }

    .section {
      padding: 80px 24px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .section h2 {
      font-size: 2rem;
      margin-bottom: 16px;
    }

    .section p {
      font-size: 1.05rem;
      line-height: 1.75;
      color: #d8e1ea;
    }

    @media (max-width: 768px) {
      .gallery--bento {
        grid-template-columns: repeat(2, 48vw);
        grid-template-rows: repeat(4, 18vh);
      }

      .gallery--final.gallery--bento {
        grid-template-columns: repeat(2, 100vw);
        grid-template-rows: repeat(4, 50vh);
      }
    }

  </style>
</head>
<body>

  <div class="gallery-wrap">
    <div class="hero-copy">
      <h1>Satu Data Muara Enim</h1>
      <p>Portal data sektoral terintegrasi untuk mendukung transparansi, perencanaan, dan pengambilan keputusan berbasis data.</p>
    </div>

    <div class="gallery gallery--bento" id="gallery-satudata">
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop" alt="Dashboard statistik" />
        <div class="overlay"></div>
        <div class="gallery__label">Statistik</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200&auto=format&fit=crop" alt="Peta dan wilayah" />
        <div class="overlay"></div>
        <div class="gallery__label">Geospasial</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop" alt="Dataset publik" />
        <div class="overlay"></div>
        <div class="gallery__label">Dataset Publik</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200&auto=format&fit=crop" alt="Analitik data" />
        <div class="overlay"></div>
        <div class="gallery__label">Analitik</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?q=80&w=1200&auto=format&fit=crop" alt="Layanan organisasi" />
        <div class="overlay"></div>
        <div class="gallery__label">Organisasi</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=1200&auto=format&fit=crop" alt="Kolaborasi data" />
        <div class="overlay"></div>
        <div class="gallery__label">Kolaborasi</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?q=80&w=1200&auto=format&fit=crop" alt="Dokumentasi API" />
        <div class="overlay"></div>
        <div class="gallery__label">API</div>
      </div>
      <div class="gallery__item">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop" alt="Muara Enim" />
        <div class="overlay"></div>
        <div class="gallery__label">Muara Enim</div>
      </div>
    </div>

  </div>

  <section class="section">
    <h2>Konten Lanjutan</h2>
    <p>
      Bagian ini bisa diisi pencarian dataset, kategori data, organisasi, statistik unggahan,
      atau shortcut menuju dokumentasi API CKAN.
    </p>
    <p>
      Jangan goblok dengan memenuhi hero pakai animasi doang lalu isi situsnya kosong. Hero cuma pembuka.
      Nilai portal tetap ditentukan oleh pencarian, kualitas metadata, dan kemudahan akses dataset.
    </p>
  </section>

  <script>
    gsap.registerPlugin(ScrollTrigger, Flip);

    let flipCtx;

    function createGalleryTween() {
      const gallery = document.querySelector("#gallery-satudata");
      const items = gallery.querySelectorAll(".gallery__item");

      if (flipCtx) flipCtx.revert();
      gallery.classList.remove("gallery--final");

      flipCtx = gsap.context(() => {
        gallery.classList.add("gallery--final");
        const state = Flip.getState(items);
        gallery.classList.remove("gallery--final");

        const flipAnim = Flip.to(state, {
          targets: items,
          duration: 1,
          ease: "power2.inOut",
          absolute: true,
          stagger: 0.02,
          paused: true
        });

        const tl = gsap.timeline({
          scrollTrigger: {
            trigger: ".gallery-wrap",
            start: "top top",
            end: "+=140%",
            scrub: true,
            pin: true,
            anticipatePin: 1
          }
        });

        tl.to(".hero-copy", {
          scale: 0.92,
          opacity: 0.25,
          ease: "none"
        }, 0);

        tl.to(flipAnim, {
          progress: 1,
          ease: "none"
        }, 0);

        return () => {
          gsap.set(items, { clearProps: "all" });
        };
      });
    }

    createGalleryTween();
    window.addEventListener("resize", createGalleryTween);
  </script>

</body>
</html>

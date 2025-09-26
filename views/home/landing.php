<?php
// views/home/landing.php
$page_title = 'Mizza Store | Cosméticos & Makeup';
$MZ_HIDE_CHROME = false;

require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';
?>

<style>
  /* --------- Hero --------- */
  .mz-hero {
    background: #fce1e6;
    background: linear-gradient(180deg,#ffdbe3 0%, #ffe7eb 35%, #ffeef1 100%);
  }
  .mz-hero .lead {
    color:#5b3a3e;
  }
  .mz-btn-primary {
    background:#2C0703; border-color:#2C0703;
  }
  .mz-btn-primary:hover {
    background:#890620; border-color:#890620;
  }

  /* --------- Reveal on scroll --------- */
  .reveal { opacity:0; transform: translateY(24px); transition: all .8s ease; }
  .reveal.visible { opacity:1; transform: translateY(0); }

  /* --------- Sección Categorías --------- */
  .mz-section-title{
    font-weight:700; text-align:center; margin-bottom:1.25rem;
  }
  .mz-section-underline{
    width:70px; height:6px; border-radius:3px; background:#c24a60; margin:.25rem auto 2rem;
  }
  .mz-cat-card {
    position:relative; overflow:hidden; border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
  }
  .mz-cat-img {
    width:100%; height: 380px; object-fit:cover; display:block;
    transition: transform .6s ease;
  }
  .mz-cat-card::after{
    content:''; position:absolute; inset:0; background:rgba(0,0,0,.0);
    transition: background .4s ease;
  }
  .mz-cat-caption{
    position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
    color:#fff; font-weight:700; font-size:clamp(20px,3vw,28px); opacity:0; transition:.35s ease;
    text-shadow: 0 3px 10px rgba(0,0,0,.35);
  }
  .mz-cat-card:hover .mz-cat-img{ transform: scale(1.05); }
  .mz-cat-card:hover::after{ background:rgba(0,0,0,.35); }
  .mz-cat-card:hover .mz-cat-caption{ opacity:1; }

  /* --------- Novedades (grid) --------- */
  .mz-product-card{
    text-align:center; padding:1rem; border-radius:16px; transition: box-shadow .25s ease;
  }
  .mz-product-card:hover{ box-shadow:0 14px 26px rgba(0,0,0,.08); }
  .mz-product-img{ height:180px; width:auto; object-fit:contain; margin:0 auto 12px; display:block; }

  /* --------- Blog cards --------- */
  .mz-blog-card{
    border-radius:16px; padding:2rem; background:#fff; box-shadow:0 12px 24px rgba(0,0,0,.06);
    height:100%;
  }
  .mz-blog-quote{ color:#c24a60; font-size:40px; line-height:0; }

  /* --------- Métodos de pago --------- */
  .mz-pay-logos img{
    height:70px; width:auto; filter: grayscale(1) contrast(1.1);
    opacity:.7; transition: all .25s ease;
  }
  .mz-pay-logos img:hover{ filter:none; opacity:1; transform: translateY(-3px); }

  @media (max-width: 991px){
    .mz-cat-img{ height:300px; }
  }
</style>

<!-- ================= HERO ================= -->
<section class="mz-hero">
  <div class="container py-5">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold">Novedades Verano 2025</h1>
        <p class="lead mt-3">
          Encontrá los productos de belleza más virales en TikTok e Instagram,
          incluidos los esenciales que recomendamos para tu rutina diaria.
        </p>
        <a href="index.php?controller=productos&action=catalogo" class="btn mz-btn-primary btn-lg mt-2">
          Enterate de más →
        </a>
      </div>
      <div class="col-lg-6 text-center">
        <!-- Reemplazá por tu imagen hero -->
        <img src="assets/images/hero-amuse.png" class="img-fluid rounded-4 shadow-sm" alt="AMUSE">
      </div>
    </div>
  </div>
</section>

<!-- ================= CATEGORÍAS ================= -->
<section class="py-5">
  <div class="container">
    <div class="reveal">
      <h2 class="mz-section-title">Categorías</h2>
      <div class="mz-section-underline"></div>
    </div>

    <div class="row g-4 reveal">
      <!-- Skincare -->
      <div class="col-md-4">
        <a class="mz-cat-card d-block" href="index.php?controller=productos&action=catalogo&cat=skincare" aria-label="Skincare">
          <img class="mz-cat-img" src="assets/images/cat-skincare.png" alt="Skincare">
          <div class="mz-cat-caption">Skincare</div>
        </a>
      </div>
      <!-- Brochas y pinceles -->
      <div class="col-md-4">
        <a class="mz-cat-card d-block" href="index.php?controller=productos&action=catalogo&cat=brochas" aria-label="Brochas y pinceles">
          <img class="mz-cat-img" src="assets/images/cat-brochas.png" alt="Brochas y pinceles">
          <div class="mz-cat-caption">Brochas y pinceles</div>
        </a>
      </div>
      <!-- Maquillaje -->
      <div class="col-md-4">
        <a class="mz-cat-card d-block" href="index.php?controller=productos&action=catalogo&cat=maquillaje" aria-label="Maquillaje">
          <img class="mz-cat-img" src="assets/images/cat-makeup.png" alt="Maquillaje">
          <div class="mz-cat-caption">Maquillaje</div>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ================= ÚLTIMAS NOVEDADES ================= -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="reveal">
      <h2 class="mz-section-title">Últimas Novedades</h2>
      <div class="mz-section-underline"></div>
    </div>

    <div class="row g-4 reveal">
      <!-- Estos 4 items son de muestra; podés reemplazarlos por un loop PHP si tenés $ultimosProductos -->
      <div class="col-6 col-lg-3">
        <div class="mz-product-card bg-white">
          <img class="mz-product-img" src="assets/images/prod-mask-watermelon.png" alt="Mask">
          <h6 class="mb-1">Glow Recipe – Mascarilla</h6>
          <div class="text-muted">$10.300</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="mz-product-card bg-white">
          <img class="mz-product-img" src="assets/images/prod-patrickta-duo.png" alt="Duo sombras">
          <h6 class="mb-1">PATRICK TA – Sombra de Ojos Duo</h6>
          <div class="text-muted">$62.000</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="mz-product-card bg-white">
          <img class="mz-product-img" src="assets/images/prod-fenty-gloss.png" alt="Fenty Gloss">
          <h6 class="mb-1">Fenty Beauty – Lip Gloss</h6>
          <div class="text-muted">$30.000</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="mz-product-card bg-white">
          <img class="mz-product-img" src="assets/images/prod-dior-oil.png" alt="Dior Lip Oil">
          <h6 class="mb-1">DIOR – Óleo Labial</h6>
          <div class="text-muted">$63.000</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= BLOG ================= -->
<section class="py-5">
  <div class="container">
    <div class="reveal">
      <h2 class="mz-section-title">Artículos recientes de nuestro blog</h2>
      <div class="mz-section-underline"></div>
    </div>

    <div class="row g-4 reveal">
      <div class="col-md-4">
        <article class="mz-blog-card h-100">
          <div class="mz-blog-quote">“</div>
          <h4 class="fw-bold">¿El fin del Clean Girl look?, ¿Qué se viene?</h4>
          <p class="text-muted">Lorem ipsum is simply dummy text of the printing and typesetting industry…</p>
          <div class="d-flex align-items-center gap-3 mt-3">
            <img src="assets/images/avatar1.jpg" class="rounded-circle" width="44" height="44" alt="Zoel">
            <strong>Zoel Gauler</strong>
          </div>
        </article>
      </div>
      <div class="col-md-4">
        <article class="mz-blog-card h-100">
          <div class="mz-blog-quote">“</div>
          <h4 class="fw-bold">Productos que marcaron la era de las E-Girl: 2020–2022</h4>
          <p class="text-muted">Lorem ipsum is simply dummy text of the printing and typesetting industry…</p>
          <div class="d-flex align-items-center gap-3 mt-3">
            <img src="assets/images/avatar2.jpg" class="rounded-circle" width="44" height="44" alt="Milo">
            <strong>Milo Vargas</strong>
          </div>
        </article>
      </div>
      <div class="col-md-4">
        <article class="mz-blog-card h-100">
          <div class="mz-blog-quote">“</div>
          <h4 class="fw-bold">Panteras y Vampiros: Furor de Halloween</h4>
          <p class="text-muted">Lorem ipsum is simply dummy text of the printing and typesetting industry…</p>
          <div class="d-flex align-items-center gap-3 mt-3">
            <img src="assets/images/avatar2.jpg" class="rounded-circle" width="44" height="44" alt="Milo">
            <strong>Milo Vargas</strong>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- ================= MÉTODOS DE PAGO ================= -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="reveal">
      <h2 class="mz-section-title">Aceptamos los siguientes métodos de pago</h2>
      <div class="mz-section-underline"></div>
    </div>

    <div class="row justify-content-center align-items-center text-center g-4 mz-pay-logos reveal">
      <div class="col-6 col-md-3 col-lg-2"><img src="assets/images/tarjetas.png" alt="Tarjetas"></div>
      <div class="col-6 col-md-3 col-lg-2"><img src="assets/images/mercadopago.jpg" alt="Mercado Pago"></div>
      <div class="col-6 col-md-3 col-lg-2"><img src="assets/images/pagofacil.png" alt="Pago Fácil"></div>
      <div class="col-6 col-md-3 col-lg-2"><img src="assets/images/paypal.png" alt="PayPal"></div>
      <div class="col-6 col-md-3 col-lg-2"><img src="assets/images/transferenciabancaria.png" alt="Transferencia bancaria"></div>
    </div>
  </div>
</section>

<script>
  // Reveal on scroll
  (function(){
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{
        if(e.isIntersecting){
          e.target.classList.add('visible');
          io.unobserve(e.target);
        }
      });
    }, {threshold: .12});
    els.forEach(el=>io.observe(el));
  })();
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

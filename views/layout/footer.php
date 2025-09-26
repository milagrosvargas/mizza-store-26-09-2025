
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- Favicon (opcional) -->
  <!-- <link rel="icon" href="assets/img/w-logo.png" type="image/png"> -->

  <!-- Tipografías y iconos -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Estilos propios (usa tu style.css de la maqueta) -->
  <!-- Si prefieres otra ubicación, muévelo a assets/css/style.css y ajusta la ruta -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="footer">
    <div class="container">
      <div class="row">
        <div class="footer-col2">
          <!-- Ajusta la ruta del logo si lo mueves a assets/img -->
          <img src="assets/images/logo2.png" alt="MizzaStore">
          <p>Que la comodidad y los beneficios de la belleza, inspirados en las últimas tendencias globales, sean accesibles para todos en Formosa.</p>
        </div>
        <div class="footer-col3">
          <h3>¿Querés saber más?</h3>
          <ul>
            <li><a href="#" class="text-decoration-none text-light">Cupones de descuento</a></li>
            <li><a href="#" class="text-decoration-none text-light">Mizza Blog</a></li>
            <li><a href="#" class="text-decoration-none text-light">Influencers Partners</a></li>
            <li><a href="#" class="text-decoration-none text-light">Cosméticos</a></li>
          </ul>
        </div>
        <div class="footer-col4">
          <h3>¡Seguinos en nuestras redes sociales!</h3>
          <ul>
            <li><a href="#" class="text-decoration-none text-light">Facebook</a></li>
            <li><a href="#" class="text-decoration-none text-light">Twitter</a></li>
            <li><a href="#" class="text-decoration-none text-light">Instagram</a></li>
            <li><a href="#" class="text-decoration-none text-light">YouTube</a></li>
          </ul>
        </div>
      </div>
      <hr>
      <p class="copyright">Mizza Store <?= date('Y') ?> - Formosa, Argentina.</p>
    </div>
  </div>

  

  <!-- SweetAlert2 + tus utilidades -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/global.js"></script>
  <script src="assets/js/alerts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts específicos de cada vista pueden agregarse debajo desde la propia vista -->
</body>
</html>
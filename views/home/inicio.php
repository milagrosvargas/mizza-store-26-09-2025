<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>MizzaStore - Argentina</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #fffaf7;
      color: #333;
    }

    header {
      background: linear-gradient(90deg, #ff7e5f, #feb47b);
      color: white;
      padding: 40px 20px;
      text-align: center;
    }

    header h1 {
      margin: 0 0 10px;
      font-size: 2.5rem;
    }

    header p {
      margin: 0;
      font-size: 1.2rem;
    }

    main {
      padding: 40px 20px;
      text-align: center;
    }

    .botones {
      margin-top: 30px;
    }

    .botones a {
      display: inline-block;
      margin: 10px;
      padding: 12px 24px;
      font-size: 16px;
      text-decoration: none;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .btn-login {
      background-color: #c0284eff;
      color: white;
    }

    .btn-login:hover {
      background-color: #35021aff;
    }

    .btn-catalogo {
      background-color: #3498db;
      color: white;
    }

    .btn-catalogo:hover {
      background-color: #217dbb;
    }

    footer {
      text-align: center;
      padding: 20px;
      background: #f0f0f0;
      margin-top: 40px;
      font-size: 14px;
      color: #666;
    }
  </style>
</head>

<body>

  <header>
    <h1>MizzaStore</h1>
    <p>Bienvenida a tu tienda de cosméticos naturales</p>
  </header>

  <main>
    <h2>Descubrí tu belleza con nosotros</h2>
    <p>Navegá nuestro catálogo o iniciá sesión para acceder a tu cuenta personalizada.</p>

    <div class="botones">
      <!-- Botón para ir al formulario de inicio de sesión -->
      <a href="index.php?controller=login&action=login" class="btn-login">Iniciar sesión</a>

      <a href="index.php?controller=catalogo&action=index" class="btn-catalogo">Ver productos</a>
    </div>
  </main>

</body>

</html>
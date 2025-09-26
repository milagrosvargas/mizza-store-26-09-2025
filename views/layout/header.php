<?php
// mizzastore/views/layout/header.php
// Variables admitidas: $page_title (string), $MZ_HIDE_CHROME (bool)
$page_title = isset($page_title) ? ($page_title . ' | MizzaStore') : 'MizzaStore';
$MZ_HIDE_CHROME = $MZ_HIDE_CHROME ?? false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- Fuentes / Iconos -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tus estilos (ajusta la ruta si tu CSS está en otra carpeta) -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- fallback si tu CSS está en la raíz (déjalo si lo usas) -->
  <!-- <link rel="stylesheet" href="style.css"> -->

  <style>
    body { font-family: 'Poppins', sans-serif; }
    /* Botón primario con tu paleta */
    .btn-primary { background:#2C0703; border-color:#2C0703; color:#fff; }
    .btn-primary:hover { background:#890620; border-color:#890620; color:#fff; }
    .navbar .nav-link.active,
    .navbar .dropdown-item.active { color:#890620 !important; }
  </style>
</head>
<body>

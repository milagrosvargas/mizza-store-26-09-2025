<?php
// navbar.php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$controller = strtolower($_GET['controller'] ?? 'home');
$action     = strtolower($_GET['action'] ?? 'landing');

$user    = $_SESSION['usuario'] ?? null;
$role    = (int)($user['id_perfil'] ?? 0);   // 0: público, 1: admin, 2: cliente
$isAdmin = $role === 1;
$isClient= $role === 2;

if (!function_exists('mz_is_active')) {
  function mz_is_active($controllers, $actions = null) {
    $c = strtolower($_GET['controller'] ?? 'home');
    $a = strtolower($_GET['action'] ?? 'landing');
    $controllers = (array)$controllers;
    $matchesCtrl = in_array($c, $controllers, true);
    if ($actions === null) return $matchesCtrl ? 'active' : '';
    $actions = (array)$actions;
    return ($matchesCtrl && in_array($a, $actions, true)) ? 'active' : '';
  }
}
?>
<style>
  /* Estilo del topbar + botones */
  .mz-topbar { background: radial-gradient(#fff,#ffd6d6); border-bottom: 1px solid #f2c9c9; }
  .navbar .nav-link { font-weight: 500; }
  .navbar .nav-link.active,
  .navbar .dropdown-item.active { color: #890620 !important; }
  .navbar-brand img { height: 36px; }
  .mz-btn {
    background:#2C0703; color:#fff; border:1px solid #2C0703;
    padding:.45rem 1rem; border-radius:.65rem; text-decoration:none;
  }
  .mz-btn:hover { background:#4a0b06; border-color:#4a0b06; color:#fff; }
  .search-pill { border-radius: 9999px; padding-left: 1rem; }
</style>

<header class="mz-topbar">
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php?controller=home&action=<?=
        $user ? ($isAdmin ? 'dashboard' : 'landing') : 'landing' ?>">
        <img src="assets/images/logo.png" alt="MizzaStore">
        <span class="fw-semibold">MizzaStore</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mzNav"
              aria-controls="mzNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mzNav">
        <!-- IZQUIERDA -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <li class="nav-item">
            <a class="nav-link <?= mz_is_active('home',['landing','dashboard']) ?>"
               href="index.php?controller=home&action=<?= $user ? ($isAdmin ? 'dashboard' : 'landing') : 'landing' ?>">
              Inicio
            </a>
          </li>

          <?php if ($isAdmin): ?>
            <li class="nav-item">
              <a class="nav-link <?= mz_is_active('clientes') ?>"
                 href="index.php?controller=clientes&action=index">Clientes</a>
            </li>
          <?php endif; ?>

          <!-- Productos (para todos; en admin suele ser gestión) -->
          <li class="nav-item">
            <a class="nav-link <?= mz_is_active('productos') ?>"
               href="index.php?controller=productos&action=index">Productos</a>
          </li>

          <!-- Pedidos: solo cliente y admin -->
          <?php if ($isAdmin || $isClient): ?>
            <li class="nav-item">
              <a class="nav-link <?= mz_is_active('pedidos') ?>"
                 href="index.php?controller=pedidos&action=index">Pedidos</a>
            </li>
          <?php endif; ?>

          <!-- Mizza Blog (visible para todos) -->
          <li class="nav-item">
            <a class="nav-link <?= mz_is_active('blog') ?>"
               href="index.php?controller=blog&action=index">Mizza Blog</a>
          </li>

          <!-- Configuración: solo Admin (dropdown tablas maestras) -->
          <?php if ($isAdmin): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle <?= mz_is_active('config') ?>" href="#" id="navbarConfig"
                 role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Configuración
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarConfig">
                <li><a class="dropdown-item <?= mz_is_active('config',['tipo_documento']) ?>"
                       href="index.php?controller=config&action=tipo_documento">Tipos de Documento</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['estado_logico']) ?>"
                       href="index.php?controller=config&action=estado_logico">Estado lógico</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['pais']) ?>"
                       href="index.php?controller=config&action=pais">País</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['provincia']) ?>"
                       href="index.php?controller=config&action=provincia">Provincia</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['localidad']) ?>"
                       href="index.php?controller=config&action=localidad">Localidad</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['barrio']) ?>"
                       href="index.php?controller=config&action=barrio">Barrio</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['tipo_contacto']) ?>"
                       href="index.php?controller=config&action=tipo_contacto">Tipos de contacto</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['genero']) ?>"
                       href="index.php?controller=config&action=genero">Género</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['categoria']) ?>"
                       href="index.php?controller=config&action=categoria">Categorías</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['sub_categoria']) ?>"
                       href="index.php?controller=config&action=sub_categoria">Sub-categorías</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['marca']) ?>"
                       href="index.php?controller=config&action=marca">Marcas</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['unidad_medida']) ?>"
                       href="index.php?controller=config&action=unidad_medida">Unidades de medida</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['metodo_pago']) ?>"
                       href="index.php?controller=config&action=metodo_pago">Métodos de pago</a></li>
                <li><a class="dropdown-item <?= mz_is_active('config',['tipo_nota']) ?>"
                       href="index.php?controller=config&action=tipo_nota">Tipos de nota</a></li>
              </ul>
            </li>
          <?php endif; ?>

        </ul>

        <!-- DERECHA: búsqueda + sesión -->
        <form class="d-flex align-items-center gap-3" action="index.php" method="get">
          <input type="hidden" name="controller" value="productos">
          <input type="hidden" name="action" value="buscar">
          <input class="form-control search-pill" type="search" name="q"
                 placeholder="Buscar productos, marca" aria-label="Buscar">
          <?php if ($user): ?>
            <a class="mz-btn" href="index.php?controller=login&action=logout">Cerrar sesión</a>
          <?php else: ?>
            <a class="mz-btn" href="index.php?controller=login&action=index&tab=login">Iniciar sesión</a>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </nav>
</header>

<?php
// --------------------------------------------------------------------
// Login / Registro (vista)
// Esta vista espera que el LoginController::index() haya seteado:
//  - $csrf_token_login  en $_SESSION['csrf_login']
//  - $csrf_token_reg    en $_SESSION['csrf_reg']
//  - $tab = 'login' | 'register'  (opcional)
// --------------------------------------------------------------------
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

// Evitamos warnings aunque lleguen sin pasar por el controlador:
$csrf_token_login = $csrf_token_login ?? ($_SESSION['csrf_login'] ?? '');
$csrf_token_reg   = $csrf_token_reg   ?? ($_SESSION['csrf_reg']   ?? '');
$tab = ($tab ?? ($_GET['tab'] ?? 'login')) === 'register' ? 'register' : 'login';

// Layout flags
$page_title    = 'Iniciar sesión / Registrarme';
$MZ_HIDE_CHROME = true;

// Si usás header/footer del layout, mantenelos:
require __DIR__ . '/../layout/header.php';
?>

<style>
  .mz-auth-card{max-width:520px;margin:40px auto;padding:24px;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(0,0,0,.06)}
  .mz-tabs{display:flex;gap:24px;justify-content:center;margin-bottom:16px}
  .mz-tab{font-weight:600;color:#7a1a26;opacity:.6;text-decoration:none;padding-bottom:6px;border-bottom:2px solid transparent}
  .mz-tab.active{opacity:1;border-color:#7a1a26}
  .mz-logo{display:block;margin:8px auto 16px;height:44px}
  .form-text{font-size:.9rem;color:#6c757d}
  .mz-btn{background:#2C0703;border:#2C0703}
  .mz-btn:hover{background:#5a0f0a;border:#5a0f0a}
</style>

<div class="container py-4">
  <div class="mz-auth-card">
    <div class="mz-tabs">
      <a class="mz-tab <?= $tab==='login'?'active':'' ?>" href="index.php?controller=login&action=index&tab=login">Iniciar sesión</a>
      <a class="mz-tab <?= $tab==='register'?'active':'' ?>" href="index.php?controller=login&action=index&tab=register">Registrarme</a>
    </div>

    <img class="mz-logo" src="assets/images/logo.png" alt="Mizza">

    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger py-2 mb-3"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- ======================== LOGIN ========================= -->
    <?php if ($tab === 'login'): ?>
      <form action="index.php?controller=login&action=autenticar" method="post" class="d-grid gap-3">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_login, ENT_QUOTES, 'UTF-8') ?>">

        <div>
          <label class="form-label">Usuario o email</label>
          <input type="text" name="usuario" class="form-control" required>
        </div>

        <div>
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn btn-primary mz-btn">Iniciar sesión</button>

        <div class="text-center">
          <a href="index.php?controller=login&action=index&tab=register">¿No tenés cuenta? Registrate</a>
        </div>
      </form>

    <!-- ====================== REGISTRO ======================== -->
    <?php else: ?>
      <form action="index.php?controller=login&action=register" method="post" class="d-grid gap-3">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_reg, ENT_QUOTES, 'UTF-8') ?>">

        <div>
          <label class="form-label">Nombre completo</label>
          <input type="text" name="nombre_completo" class="form-control" required minlength="3">
          <div class="form-text">Mínimo 3 caracteres.</div>
        </div>

        <div>
          <label class="form-label">Correo electrónico</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div>
          <label class="form-label">Nombre de usuario</label>
          <input type="text" name="usuario" class="form-control" required pattern="[A-Za-z0-9._-]{4,20}">
          <div class="form-text">4–20 alfanuméricos (puede incluir . _ -).</div>
        </div>

        <div>
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required minlength="6">
          <div class="form-text">Mín. 6, incluir un número y un caracter especial.</div>
        </div>

        <button type="submit" class="btn btn-primary mz-btn">Registrarme</button>

        <div class="text-center">
          <a href="index.php?controller=login&action=index&tab=login">¿Ya tenés cuenta? Iniciá sesión</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

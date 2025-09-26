<?php
// mizzastore/views/carrito/index.php
$page_title = 'Carrito';
$MZ_HIDE_CHROME = false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';
$BASE = htmlspecialchars($_SERVER['SCRIPT_NAME']);
?>
<div class="container py-4" style="max-width:900px">
  <h1 class="h4 mb-3">Carrito</h1>

  <?php if (empty($items)): ?>
    <div class="alert alert-info">Tu carrito está vacío.</div>
  <?php else: ?>
    <div class="card mb-3">
      <div class="card-body">
        <?php foreach ($items as $id => $cant): ?>
          <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <div>Producto #<?= (int)$id ?></div>
            <div class="d-flex align-items-center gap-3">
              <span class="text-muted">Cant: <?= (int)$cant ?></span>
              <form action="<?= $BASE ?>?controller=carrito&action=quitar" method="post" class="m-0">
                <input type="hidden" name="id_producto" value="<?= (int)$id ?>">
                <button class="btn btn-sm btn-outline-danger">Quitar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="d-flex gap-2">
      <form action="<?= $BASE ?>?controller=carrito&action=vaciar" method="post">
        <button class="btn btn-outline-secondary">Vaciar</button>
      </form>
      <a class="btn btn-primary" href="<?= $BASE ?>?controller=carrito&action=checkout">Ir a pagar</a>
    </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>

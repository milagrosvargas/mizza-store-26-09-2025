<?php
// mizzastore/views/pedidos/index.php
$page_title = $page_title ?? 'Pedidos';
$MZ_HIDE_CHROME = $MZ_HIDE_CHROME ?? false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';
?>
<div class="container py-4">
  <h1 class="h4 mb-3">Pedidos</h1>
  <div class="alert alert-info">Aquí irá el listado/gestión de pedidos.</div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>

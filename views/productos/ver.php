<?php
// mizzastore/views/productos/ver.php
$page_title = $row['nombre_producto'] ?? 'Producto';
$MZ_HIDE_CHROME = false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';

$BASE = htmlspecialchars($_SERVER['SCRIPT_NAME']);
?>
<div class="container py-4" style="max-width:1100px">
  <div class="row g-4">
    <div class="col-md-6">
      <?php if (!empty($row['imagen_producto'])): ?>
        <img src="<?= htmlspecialchars($row['imagen_producto']) ?>"
             class="img-fluid rounded" alt=""
             style="max-height:480px; object-fit:cover;">
      <?php endif; ?>
    </div>
    <div class="col-md-6">
      <h1 class="h3 mb-2"><?= htmlspecialchars($row['nombre_producto']) ?></h1>
      <div class="mb-3 fs-5 fw-semibold">
        $<?= number_format((float)$row['precio_producto'], 2, ',', '.') ?>
      </div>

      <?php if (!empty($row['nombre_marca'])): ?>
        <div class="mb-1"><span class="text-muted">Marca: </span><?= htmlspecialchars($row['nombre_marca']) ?></div>
      <?php endif; ?>
      <?php if (!empty($row['nombre_categoria'])): ?>
        <div class="mb-1"><span class="text-muted">Categoría: </span><?= htmlspecialchars($row['nombre_categoria']) ?></div>
      <?php endif; ?>
      <?php if (!empty($row['nombre_sub_categoria'])): ?>
        <div class="mb-1"><span class="text-muted">Sub-categoría: </span><?= htmlspecialchars($row['nombre_sub_categoria']) ?></div>
      <?php endif; ?>
      <?php if (!empty($row['nombre_unidad'])): ?>
        <div class="mb-3"><span class="text-muted">Unidad: </span><?= htmlspecialchars($row['nombre_unidad']) ?></div>
      <?php endif; ?>

      <form class="d-flex align-items-center gap-2"
            action="<?= $BASE ?>?controller=carrito&action=agregar" method="post">
        <input type="hidden" name="id_producto" value="<?= (int)$row['id_producto'] ?>">
        <input type="number" class="form-control" name="cantidad" min="1" value="1" style="width:90px;">
        <button class="btn btn-primary">Añadir al Carrito</button>
      </form>

      <?php if (!empty($row['descripcion_producto'])): ?>
        <hr>
        <h2 class="h6">Detalles del producto</h2>
        <p class="mb-0"><?= nl2br(htmlspecialchars($row['descripcion_producto'])) ?></p>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>

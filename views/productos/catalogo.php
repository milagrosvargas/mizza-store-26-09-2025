<?php
// mizzastore/views/productos/catalogo.php
$page_title = 'Productos';
$MZ_HIDE_CHROME = false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';

$BASE = htmlspecialchars($_SERVER['SCRIPT_NAME']);
$catSel = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$p      = isset($_GET['p']) ? (int)$_GET['p'] : 1;
?>
<div class="container py-4" style="max-width:1100px">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 m-0">Todos los productos</h1>
    <form class="d-flex align-items-center gap-2" method="get" action="<?= $BASE ?>">
      <input type="hidden" name="controller" value="productos">
      <input type="hidden" name="action" value="catalogo">
      <select class="form-select" name="cat" onchange="this.form.submit()">
        <option value="0">Todo</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= (int)$c['id_categoria'] ?>" <?= $catSel===(int)$c['id_categoria'] ? 'selected':'' ?>>
            <?= htmlspecialchars($c['nombre_categoria']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <?php if (empty($items)): ?>
    <div class="alert alert-info">No hay productos para mostrar.</div>
  <?php else: ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
      <?php foreach ($items as $it): ?>
        <div class="col">
          <div class="card h-100">
            <?php if (!empty($it['imagen_producto'])): ?>
              <a href="<?= $BASE ?>?controller=productos&action=ver&id=<?= (int)$it['id_producto'] ?>">
                <img src="<?= htmlspecialchars($it['imagen_producto']) ?>"
                     class="card-img-top" alt=""
                     style="height:220px; object-fit:cover;">
              </a>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <a class="stretched-link text-decoration-none"
                 href="<?= $BASE ?>?controller=productos&action=ver&id=<?= (int)$it['id_producto'] ?>">
                <div class="fw-semibold mb-1"><?= htmlspecialchars($it['nombre_producto']) ?></div>
              </a>
              <div class="mt-auto fw-semibold">$<?= number_format((float)$it['precio_producto'], 2, ',', '.') ?></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Paginación -->
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php
        // helper para URL manteniendo cat
        $u = function(int $page) use ($BASE, $catSel) {
          $qs = http_build_query([
            'controller'=>'productos',
            'action'    =>'catalogo',
            'cat'       => $catSel ?: null,
            'p'         => $page
          ]);
          return $BASE . '?' . $qs;
        };
        ?>
        <li class="page-item <?= $p<=1?'disabled':'' ?>">
          <a class="page-link" href="<?= $u(max(1,$p-1)) ?>">&laquo;</a>
        </li>
        <?php for ($i=1; $i<=$pages; $i++): ?>
          <li class="page-item <?= $i===$p?'active':'' ?>">
            <a class="page-link" href="<?= $u($i) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?= $p>=$pages?'disabled':'' ?>">
          <a class="page-link" href="<?= $u(min($pages,$p+1)) ?>">&raquo;</a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

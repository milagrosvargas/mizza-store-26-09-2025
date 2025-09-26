<?php
// mizzastore/views/productos/index.php
$page_title = 'Productos';
$MZ_HIDE_CHROME = false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';

$BASE = htmlspecialchars($_SERVER['SCRIPT_NAME']);
?>
<div class="container" style="max-width:1100px;">

  <h2 class="mt-4 mb-3">Productos</h2>

  <?php if (!empty($_SESSION['flash'])): ?>
    <?php $f = $_SESSION['flash']; ?>
    <div class="alert alert-<?= $f['type']==='success'?'success':'danger' ?> alert-dismissible fade show" role="alert">
      <strong><?= htmlspecialchars($f['title']) ?>:</strong> <?= htmlspecialchars($f['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if (function_exists('is_admin') && is_admin()): ?>
  <!-- Alta -->
  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3"
            action="<?= $BASE ?>?controller=productos&action=store"
            method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <div class="col-md-6">
          <label class="form-label">Nombre *</label>
          <input class="form-control" name="nombre_producto" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Imagen</label>
          <input type="file" class="form-control" name="imagen_producto" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="col-md-6">
          <label class="form-label">Código de barras (opcional)</label>
          <input class="form-control" name="codigo_barras" placeholder="EAN/UPC/GTIN">
        </div>

        <div class="col-md-6">
          <label class="form-label">Precio *</label>
          <input type="number" step="0.01" class="form-control" name="precio_producto" required>
        </div>

        <div class="col-md-12">
          <label class="form-label">Descripción</label>
          <textarea class="form-control" name="descripcion_producto" rows="2"></textarea>
        </div>

        <div class="col-md-3">
          <label class="form-label">Stock</label>
          <input type="number" class="form-control" name="stock_producto" value="0">
        </div>

        <div class="col-md-3">
          <label class="form-label">Unidad *</label>
          <select class="form-select" name="id_unidad_medida" required>
            <option value="">— Seleccionar —</option>
            <?php foreach ($unidades as $u): ?>
              <option value="<?= (int)$u['id_unidad_medida'] ?>"><?= htmlspecialchars($u['nombre_unidad']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Estado *</label>
          <select class="form-select" name="id_estado_logico" required>
            <?php foreach ($estados as $e): ?>
              <option value="<?= (int)$e['id_estado_logico'] ?>"><?= htmlspecialchars($e['nombre_estado_logico']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Marca *</label>
          <select class="form-select" name="id_marca" required>
            <option value="">— Seleccionar —</option>
            <?php foreach ($marcas as $m): ?>
              <option value="<?= (int)$m['id_marca'] ?>"><?= htmlspecialchars($m['nombre_marca']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Categoría *</label>
          <select class="form-select" name="id_categoria" id="selCategoria" required>
            <option value="">— Seleccionar —</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= (int)$c['id_categoria'] ?>"><?= htmlspecialchars($c['nombre_categoria']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Sub-categoría</label>
          <select class="form-select" name="id_sub_categoria" id="selSubcat">
            <option value="">— (opcional) —</option>
            <?php foreach ($subcats as $sc): ?>
              <option value="<?= (int)$sc['id_sub_categoria'] ?>" data-cat="<?= (int)$sc['id_categoria'] ?>">
                <?= htmlspecialchars($sc['nombre_sub_categoria']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary">Añadir</button>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <!-- Listado -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th style="width:80px;">ID</th>
              <th style="width:80px;">Img</th>
              <th>Producto</th>
              <th>Código</th>
              <th>Categoría</th>
              <th>Subcat</th>
              <th>Marca</th>
              <th>Unidad</th>
              <th class="text-end">Precio</th>
              <?php if (function_exists('is_admin') && is_admin()): ?>
                <th class="text-end" style="width:160px;">Acciones</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $it): ?>
              <tr>
                <td><?= (int)$it['id_producto'] ?></td>
                <td>
                  <?php if (!empty($it['imagen_producto'])): ?>
                    <img src="<?= htmlspecialchars($it['imagen_producto']) ?>" alt=""
                         style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                  <?php endif; ?>
                </td>
                <td>
                  <div class="fw-semibold"><?= htmlspecialchars($it['nombre_producto']) ?></div>
                  <div class="text-muted small"><?= htmlspecialchars($it['descripcion_producto'] ?? '') ?></div>
                </td>
                <td><?= htmlspecialchars($it['codigo_barras'] ?? '—') ?></td>
                <td><?= htmlspecialchars($it['nombre_categoria'] ?? '-') ?></td>
                <td><?= htmlspecialchars($it['nombre_sub_categoria'] ?? '-') ?></td>
                <td><?= htmlspecialchars($it['nombre_marca'] ?? '-') ?></td>
                <td><?= htmlspecialchars($it['nombre_unidad'] ?? '-') ?></td>
                <td class="text-end">$<?= number_format((float)$it['precio_producto'], 2, ',', '.') ?></td>

                <?php if (function_exists('is_admin') && is_admin()): ?>
                <td class="text-end">
                  <!-- Editar -->
                  <button class="btn btn-sm btn-outline-secondary me-1"
                          onclick="editarProducto(
                            <?= (int)$it['id_producto'] ?>,
                            '<?= htmlspecialchars($it['nombre_producto'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($it['descripcion_producto'] ?? '', ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($it['codigo_barras'] ?? '', ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($it['imagen_producto'] ?? '', ENT_QUOTES) ?>',
                            <?= (float)$it['precio_producto'] ?>,
                            <?= (int)($it['stock_producto'] ?? 0) ?>
                          )">
                    <i class="bi bi-pencil"></i>
                  </button>

                  <!-- Borrar -->
                  <form action="<?= $BASE ?>?controller=productos&action=delete"
                        method="POST" class="d-inline"
                        onsubmit="return confirmarBorrar(this)">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" name="id_producto" value="<?= (int)$it['id_producto'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal edición (solo admin) -->
<?php if (function_exists('is_admin') && is_admin()): ?>
<div class="modal" tabindex="-1" id="modalEditarProducto">
  <div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar producto</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="<?= $BASE ?>?controller=productos&action=update" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_producto" id="edit_id">
      <input type="hidden" name="imagen_actual" id="edit_img_actual">

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre *</label>
            <input class="form-control" id="edit_nombre" name="nombre_producto" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Imagen (reemplazar)</label>
            <input type="file" class="form-control" name="imagen_producto" accept=".jpg,.jpeg,.png,.webp">
          </div>
          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" id="edit_desc" name="descripcion_producto" rows="2"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Código de barras (opcional)</label>
            <input class="form-control" id="edit_barcode" name="codigo_barras" placeholder="EAN/UPC/GTIN">
          </div>
          <div class="col-md-3">
            <label class="form-label">Precio *</label>
            <input type="number" step="0.01" class="form-control" id="edit_precio" name="precio_producto" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" id="edit_stock" name="stock_producto">
          </div>
          <div class="col-md-3">
            <label class="form-label">Unidad *</label>
            <select class="form-select" name="id_unidad_medida" required>
              <?php foreach ($unidades as $u): ?>
                <option value="<?= (int)$u['id_unidad_medida'] ?>"><?= htmlspecialchars($u['nombre_unidad']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Estado *</label>
            <select class="form-select" name="id_estado_logico" required>
              <?php foreach ($estados as $e): ?>
                <option value="<?= (int)$e['id_estado_logico'] ?>"><?= htmlspecialchars($e['nombre_estado_logico']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Categoría *</label>
            <select class="form-select" name="id_categoria" required>
              <?php foreach ($categorias as $c): ?>
                <option value="<?= (int)$c['id_categoria'] ?>"><?= htmlspecialchars($c['nombre_categoria']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Sub-categoría</label>
            <select class="form-select" name="id_sub_categoria">
              <option value="">— (opcional) —</option>
              <?php foreach ($subcats as $sc): ?>
                <option value="<?= (int)$sc['id_sub_categoria'] ?>" data-cat="<?= (int)$sc['id_categoria'] ?>">
                  <?= htmlspecialchars($sc['nombre_sub_categoria']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Marca *</label>
            <select class="form-select" name="id_marca" required>
              <?php foreach ($marcas as $m): ?>
                <option value="<?= (int)$m['id_marca'] ?>"><?= htmlspecialchars($m['nombre_marca']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div></div>
</div>
<?php endif; ?>

<script>
// Filtro de subcategorías por categoría en el alta
document.getElementById('selCategoria')?.addEventListener('change', function(){
  const cat = this.value;
  const sub = document.getElementById('selSubcat');
  if (!sub) return;
  [...sub.options].forEach(op => {
    const c = op.getAttribute('data-cat');
    if (!c) return; // opción '(opcional)'
    op.hidden = (cat && c !== cat);
  });
  sub.value = '';
});

function confirmarBorrar(form){
  if (typeof mostrarPregunta === 'function') {
    mostrarPregunta('¿Eliminar?', 'Esta acción no se puede deshacer.', 'Sí, eliminar', 'Cancelar', () => form.submit());
    return false;
  }
  return confirm('¿Eliminar registro?');
}

function editarProducto(id, nombre, desc, barcode, img, precio, stock){
  const m = document.getElementById('modalEditarProducto');
  if (!m) return; // si no sos admin no hay modal
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_nombre').value = nombre;
  document.getElementById('edit_desc').value = desc;
  document.getElementById('edit_barcode').value = barcode || '';
  document.getElementById('edit_precio').value = precio;
  document.getElementById('edit_stock').value = stock ?? 0;
  document.getElementById('edit_img_actual').value = img || '';
  new bootstrap.Modal(m).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php
$page_title = 'Categorías';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>
<div class="container" style="max-width:980px;">
  <h2 class="mt-4 mb-3">Configuración » Categoría</h2>

  <!-- Alta -->
  <form action="index.php?controller=config&action=categoria_store" method="POST"
        class="row g-2 mb-3" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="col-md-5">
      <input class="form-control" name="nombre_categoria" placeholder="Nueva categoría" required>
    </div>

    <div class="col-md-5">
      <input class="form-control" type="file" name="imagen_categoria" accept="image/*">
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-primary">Añadir</button>
    </div>
  </form>

  <!-- Listado -->
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th style="width:90px;">ID</th>
              <th>Nombre</th>
              <th style="width:100px;">Imagen</th>
              <th class="text-end" style="width:180px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= (int)$it['id_categoria'] ?></td>
              <td><?= htmlspecialchars($it['nombre_categoria']) ?></td>
              <td>
                <?php if (!empty($it['imagen_categoria'])): ?>
                  <img src="<?= $imgBaseUrl . rawurlencode($it['imagen_categoria']) ?>"
                       alt="img" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #eee;">
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary me-1"
                        onclick="editarCategoria(
                          <?= (int)$it['id_categoria'] ?>,
                          '<?= htmlspecialchars($it['nombre_categoria'], ENT_QUOTES) ?>',
                          '<?= !empty($it['imagen_categoria']) ? ($imgBaseUrl . rawurlencode($it['imagen_categoria'])) : '' ?>'
                        )">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="index.php?controller=config&action=categoria_delete" method="POST"
                      class="d-inline" onsubmit="return confirmarBorrar(this)">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                  <input type="hidden" name="id_categoria" value="<?= (int)$it['id_categoria'] ?>">
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal edición -->
<div class="modal" tabindex="-1" id="modalEditarCategoria">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar categoría</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="index.php?controller=config&action=categoria_update" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_categoria" id="edit_id_categoria">
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" id="edit_nombre_categoria" name="nombre_categoria" required>
        </div>
        <div class="mb-2">
          <label class="form-label d-block">Imagen actual</label>
          <img id="edit_preview_img" src="" alt="preview" style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
          <div class="text-muted small mt-1" id="edit_no_img" style="display:none;">(sin imagen)</div>
        </div>
        <div>
          <label class="form-label">Reemplazar imagen (opcional)</label>
          <input class="form-control" type="file" name="imagen_categoria" accept="image/*">
          <div class="form-text">Formatos permitidos: JPG, PNG, WEBP. Máx: 2MB.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div></div>
</div>

<script>
function confirmarBorrar(form){
  if (typeof mostrarPregunta === 'function') {
    mostrarPregunta('¿Eliminar?', 'Esta acción no se puede deshacer.', 'Sí, eliminar', 'Cancelar', () => form.submit());
    return false;
  }
  return confirm('¿Eliminar registro?');
}
function editarCategoria(id, nombre, imgUrl){
  document.getElementById('edit_id_categoria').value = id;
  document.getElementById('edit_nombre_categoria').value = nombre;

  const img = document.getElementById('edit_preview_img');
  const noImg = document.getElementById('edit_no_img');
  if (imgUrl) {
    img.src = imgUrl;
    img.style.display = '';
    noImg.style.display = 'none';
  } else {
    img.src = '';
    img.style.display = 'none';
    noImg.style.display = '';
  }

  new bootstrap.Modal(document.getElementById('modalEditarCategoria')).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

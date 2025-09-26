<?php
$page_title = 'Sub Categorías';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>

<div class="container" style="max-width:980px;">
  <h2 class="mt-4 mb-3">Configuración » Sub‑categoría</h2>

  <!-- Alta -->
  <form action="index.php?controller=config&action=sub_categoria_store" method="POST" class="row g-2 mb-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="col-md-6">
      <input class="form-control" name="nombre_sub_categoria" placeholder="Nueva sub‑categoría" required>
    </div>

    <div class="col-md-4">
      <select class="form-select" name="id_categoria">
        <option value="">— Categoría —</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= (int)$c['id_categoria'] ?>">
            <?= htmlspecialchars($c['nombre_categoria']) ?>
          </option>
        <?php endforeach; ?>
      </select>
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
              <th>Sub‑categoría</th>
              <th>Categoría</th>
              <th class="text-end" style="width:180px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= (int)$it['id_sub_categoria'] ?></td>
              <td><?= htmlspecialchars($it['nombre_sub_categoria']) ?></td>
              <td><?= htmlspecialchars($it['nombre_categoria'] ?? '—') ?></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary me-1"
                        onclick="editarSubCategoria(
                          <?= (int)$it['id_sub_categoria'] ?>,
                          '<?= htmlspecialchars($it['nombre_sub_categoria'], ENT_QUOTES) ?>',
                          '<?= isset($it['id_categoria']) ? (int)$it['id_categoria'] : '' ?>'
                        )">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="index.php?controller=config&action=sub_categoria_delete" method="POST"
                      class="d-inline" onsubmit="return confirmarBorrar(this)">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                  <input type="hidden" name="id_sub_categoria" value="<?= (int)$it['id_sub_categoria'] ?>">
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
<div class="modal" tabindex="-1" id="modalEditarSubCategoria">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar sub‑categoría</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="index.php?controller=config&action=sub_categoria_update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_sub_categoria" id="edit_id_sub_categoria">
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" id="edit_nombre_sub_categoria" name="nombre_sub_categoria" required>
        </div>
        <div>
          <label class="form-label">Categoría</label>
          <select class="form-select" id="edit_id_categoria" name="id_categoria">
            <option value="">— Categoría —</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= (int)$c['id_categoria'] ?>">
                <?= htmlspecialchars($c['nombre_categoria']) ?>
              </option>
            <?php endforeach; ?>
          </select>
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
function editarSubCategoria(id, nombre, id_categoria){
  document.getElementById('edit_id_sub_categoria').value = id;
  document.getElementById('edit_nombre_sub_categoria').value = nombre;
  const sel = document.getElementById('edit_id_categoria');
  if (id_categoria !== '' && sel) sel.value = id_categoria;
  new bootstrap.Modal(document.getElementById('modalEditarSubCategoria')).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

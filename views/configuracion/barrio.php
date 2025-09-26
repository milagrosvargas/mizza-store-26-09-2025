<?php
$page_title = 'Barrios';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>
<div class="container" style="max-width:980px;">
  <h2 class="mt-4 mb-3">Configuración » Barrio</h2>

  <!-- Alta -->
  <form action="index.php?controller=config&action=barrio_store" method="POST" class="row g-2 mb-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="col-md-6">
      <input class="form-control" name="nombre_barrio" placeholder="Nuevo barrio" required>
    </div>

    <div class="col-md-4">
      <select class="form-select" name="id_localidad">
        <option value="">— Localidad —</option>
        <?php foreach ($localidades as $l): ?>
          <option value="<?= (int)$l['id_localidad'] ?>">
            <?= htmlspecialchars($l['nombre_localidad']) ?>
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
              <th>Barrio</th>
              <th>Localidad</th>
              <th class="text-end" style="width:160px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= (int)$it['id_barrio'] ?></td>
              <td><?= htmlspecialchars($it['nombre_barrio']) ?></td>
              <td><?= htmlspecialchars($it['nombre_localidad'] ?? '—') ?></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary me-1"
                        onclick="editarBarrio(
                          <?= (int)$it['id_barrio'] ?>,
                          '<?= htmlspecialchars($it['nombre_barrio'], ENT_QUOTES) ?>',
                          '<?= isset($it['id_localidad']) ? (int)$it['id_localidad'] : '' ?>'
                        )">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="index.php?controller=config&action=barrio_delete" method="POST"
                      class="d-inline" onsubmit="return confirmarBorrar(this)">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                  <input type="hidden" name="id_barrio" value="<?= (int)$it['id_barrio'] ?>">
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
<div class="modal" tabindex="-1" id="modalEditarBarrio">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar barrio</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="index.php?controller=config&action=barrio_update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_barrio" id="edit_id_barrio">
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" id="edit_nombre_barrio" name="nombre_barrio" required>
        </div>
        <div>
          <label class="form-label">Localidad</label>
          <select class="form-select" id="edit_id_localidad" name="id_localidad">
            <option value="">— Localidad —</option>
            <?php foreach ($localidades as $l): ?>
              <option value="<?= (int)$l['id_localidad'] ?>">
                <?= htmlspecialchars($l['nombre_localidad']) ?>
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
function editarBarrio(id, nombre, id_localidad){
  document.getElementById('edit_id_barrio').value = id;
  document.getElementById('edit_nombre_barrio').value = nombre;
  const sel = document.getElementById('edit_id_localidad');
  if (id_localidad !== '' && sel) sel.value = id_localidad;
  new bootstrap.Modal(document.getElementById('modalEditarBarrio')).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

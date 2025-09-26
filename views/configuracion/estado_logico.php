<?php
$page_title = 'Estados Lógicos';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>

<div class="container" style="max-width:980px;">
  <h2 class="mt-4 mb-3">Configuración » Estado lógico</h2>

  <!-- Alta -->
  <form action="index.php?controller=config&action=estado_logico_store" method="POST" class="row g-2 mb-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <div class="col-md-9">
      <input class="form-control" name="nombre_estado_logico" placeholder="Nuevo estado lógico" required>
    </div>
    <div class="col-md-3 d-grid">
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
              <th class="text-end" style="width:140px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= (int)$it['id_estado_logico'] ?></td>
              <td><?= htmlspecialchars($it['nombre_estado_logico']) ?></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary me-1"
                  onclick="editarEL(<?= (int)$it['id_estado_logico'] ?>,'<?= htmlspecialchars($it['nombre_estado_logico'],ENT_QUOTES) ?>')">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="index.php?controller=config&action=estado_logico_delete" method="POST"
                      class="d-inline" onsubmit="return confirmarBorrar(this)">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                  <input type="hidden" name="id_estado_logico" value="<?= (int)$it['id_estado_logico'] ?>">
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
<div class="modal" tabindex="-1" id="modalEditarEL">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar estado lógico</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="index.php?controller=config&action=estado_logico_update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_estado_logico" id="edit_id_estado_logico">
      <div class="modal-body">
        <label class="form-label">Nombre</label>
        <input class="form-control" id="edit_nombre_estado_logico" name="nombre_estado_logico" required>
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
function editarEL(id, nombre){
  document.getElementById('edit_id_estado_logico').value = id;
  document.getElementById('edit_nombre_estado_logico').value = nombre;
  new bootstrap.Modal(document.getElementById('modalEditarEL')).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php
$page_title = 'País';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>

<div class="container" style="max-width:980px;">
  <h2 class="mt-4 mb-3">Configuración » País</h2>

  <!-- Alta -->
  <form action="index.php?controller=config&action=pais_store" method="POST" class="row g-2 mb-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <div class="col-md-9">
      <input class="form-control" name="nombre_pais" placeholder="Nuevo país" required>
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
              <td><?= (int)$it['id_pais'] ?></td>
              <td><?= htmlspecialchars($it['nombre_pais']) ?></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary me-1"
                  onclick="editarPais(<?= (int)$it['id_pais'] ?>,'<?= htmlspecialchars($it['nombre_pais'],ENT_QUOTES) ?>')">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="index.php?controller=config&action=pais_delete" method="POST"
                      class="d-inline" onsubmit="return confirmarBorrar(this)">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                  <input type="hidden" name="id_pais" value="<?= (int)$it['id_pais'] ?>">
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
<div class="modal" tabindex="-1" id="modalEditarPais">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar país</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="index.php?controller=config&action=pais_update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_pais" id="edit_id_pais">
      <div class="modal-body">
        <label class="form-label">Nombre</label>
        <input class="form-control" id="edit_nombre_pais" name="nombre_pais" required>
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
function editarPais(id, nombre){
  document.getElementById('edit_id_pais').value = id;
  document.getElementById('edit_nombre_pais').value = nombre;
  new bootstrap.Modal(document.getElementById('modalEditarPais')).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

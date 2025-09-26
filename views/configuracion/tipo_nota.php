<?php
$page_title = 'Tipo de Nota';  
$MZ_HIDE_CHROME = false; 

require __DIR__ . '/../layout/header.php';   
require __DIR__ . '/../layout/navbar.php';  
?>
<div class="container" style="max-width:800px;">
  <h2 class="mt-4 mb-3">Configuración » Tipo de nota</h2>

  <!-- Alta -->
  <form action="index.php?controller=config&action=tipo_nota_store" method="POST" class="row g-2 mb-3">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <div class="col-md-8">
      <input class="form-control" name="nombre_tipo_nota" placeholder="Nuevo tipo de nota" required>
    </div>

    <div class="col-md-4 d-grid">
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
              <th>Tipo de nota</th>
              <th class="text-end" style="width:160px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= (int)$it['id_tipo_nota'] ?></td>
              <td><?= htmlspecialchars($it['nombre_tipo_nota']) ?></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary me-1"
                        onclick="editarTipoNota(
                          <?= (int)$it['id_tipo_nota'] ?>,
                          '<?= htmlspecialchars($it['nombre_tipo_nota'], ENT_QUOTES) ?>'
                        )">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="index.php?controller=config&action=tipo_nota_delete" method="POST"
                      class="d-inline" onsubmit="return confirmarBorrar(this)">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                  <input type="hidden" name="id_tipo_nota" value="<?= (int)$it['id_tipo_nota'] ?>">
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
<div class="modal" tabindex="-1" id="modalEditarTipoNota">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Editar tipo de nota</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="index.php?controller=config&action=tipo_nota_update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <input type="hidden" name="id_tipo_nota" id="edit_id_tipo_nota">
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" id="edit_nombre_tipo_nota" name="nombre_tipo_nota" required>
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
function editarTipoNota(id, nombre){
  document.getElementById('edit_id_tipo_nota').value = id;
  document.getElementById('edit_nombre_tipo_nota').value = nombre;
  new bootstrap.Modal(document.getElementById('modalEditarTipoNota')).show();
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

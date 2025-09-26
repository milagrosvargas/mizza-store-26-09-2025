<?php
$page_title = 'Blog';
$MZ_HIDE_CHROME = false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';
$BASE = htmlspecialchars($_SERVER['SCRIPT_NAME']);
?>
<div class="container py-4" style="max-width:900px">
  <h1 class="h4 mb-3">Blog</h1>
  <div class="list-group">
    <?php foreach (($posts ?? []) as $p): ?>
      <a class="list-group-item list-group-item-action"
         href="<?= $BASE ?>?controller=blog&action=ver&id=<?= (int)$p['id'] ?>">
        <div class="fw-semibold"><?= htmlspecialchars($p['titulo']) ?></div>
        <div class="small text-muted"><?= htmlspecialchars($p['resumen']) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>

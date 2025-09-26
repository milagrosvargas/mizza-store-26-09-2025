<?php
$page_title = $post['titulo'] ?? 'Artículo';
$MZ_HIDE_CHROME = false;
require __DIR__ . '/../layout/header.php';
require __DIR__ . '/../layout/navbar.php';
?>
<div class="container py-4" style="max-width:900px">
  <h1 class="h4 mb-3"><?= htmlspecialchars($post['titulo'] ?? 'Artículo') ?></h1>
  <p><?= nl2br(htmlspecialchars($post['contenido'] ?? '')) ?></p>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>

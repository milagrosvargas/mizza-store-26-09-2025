<?php
// mizzastore/controllers/BlogController.php
class BlogController
{
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $page_title = 'Blog';
        $MZ_HIDE_CHROME = false;
        // $posts = ... (si tenés tabla; por ahora ejemplo)
        $posts = [
            ['id'=>1,'titulo'=>'Rutina de skincare','resumen'=>'Cómo armar tu rutina básica.'],
            ['id'=>2,'titulo'=>'Maquillaje diario','resumen'=>'Tips para un look natural.'],
        ];
        require __DIR__ . '/../views/blog/index.php';
    }

    public function ver(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $id = (int)($_GET['id'] ?? 0);
        $page_title = 'Artículo';
        $MZ_HIDE_CHROME = false;
        $post = ['id'=>$id, 'titulo'=>'Artículo demo', 'contenido'=>'Contenido del artículo...'];
        require __DIR__ . '/../views/blog/ver.php';
    }
}

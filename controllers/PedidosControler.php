<?php
// mizzastore/controllers/PedidosController.php
require_once __DIR__ . '/../config/auth.php';

class PedidosController
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_login_or_redirect(); // solo usuarios logueados
    }

    public function index(): void
    {
        $page_title = 'Pedidos';
        $MZ_HIDE_CHROME = false;
        require __DIR__ . '/../views/pedidos/index.php';
    }

    // si más adelante agregas acciones:
    public function ver(): void   { $this->index(); }
    public function crear(): void { $this->index(); }
}

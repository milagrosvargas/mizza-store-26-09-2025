<?php
// mizzastore/controllers/CarritoController.php
require_once __DIR__ . '/../config/auth.php';

class CarritoController
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = []; // id_producto => cantidad
    }

    private function base(): string { return $_SERVER['SCRIPT_NAME']; }

    public function index(): void
    {
        $page_title = 'Carrito';
        $MZ_HIDE_CHROME = false;
        $items = $_SESSION['carrito'];
        require __DIR__ . '/../views/carrito/index.php';
    }

    public function agregar(): void
    {
        $id = (int)($_POST['id_producto'] ?? 0);
        $q  = max(1, (int)($_POST['cantidad'] ?? 1));
        if ($id > 0) {
            $_SESSION['carrito'][$id] = ($_SESSION['carrito'][$id] ?? 0) + $q;
        }
        header('Location: ' . $this->base() . '?controller=carrito&action=index');
        exit;
    }

    public function quitar(): void
    {
        $id = (int)($_POST['id_producto'] ?? 0);
        if ($id > 0 && isset($_SESSION['carrito'][$id])) unset($_SESSION['carrito'][$id]);
        header('Location: ' . $this->base() . '?controller=carrito&action=index');
        exit;
    }

    public function vaciar(): void
    {
        $_SESSION['carrito'] = [];
        header('Location: ' . $this->base() . '?controller=carrito&action=index');
        exit;
    }

    public function checkout(): void
    {
        require_login_or_redirect(); // acá sí exigimos login
        // Implementá tu flujo de compra real
        header('Location: ' . $this->base() . '?controller=pedidos&action=crear');
        exit;
    }
}

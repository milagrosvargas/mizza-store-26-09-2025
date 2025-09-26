
<?php
// controllers/HomeController.php

class HomeController
{
    /**
     * Landing pública (sin sesión).
     * Muestra la home marketing con hero, categorías, novedades, blog, pagos.
     */
    public function landing(): void
    {
        // La vista usa header/navbar/footer del layout
        require __DIR__ . '/../views/home/landing.php';
    }

    /**
     * Dashboard interno (requiere sesión).
     */
    public function dashboard(): void
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: index.php?controller=home&action=landing');
            exit;
        }

        // Si tenés dashboard para admin/cliente distintos, podés ramificar aquí:
        // $perfil = $_SESSION['usuario']['id_perfil'] ?? null;  // 1=admin, 2=cliente ...
        // if ($perfil == 1) { require admin_dashboard.php; } else { require cliente_dashboard.php; }

        require __DIR__ . '/../views/home/dashboard_principal.php';
    }

    /**
     * Inicio “histórico” por compatibilidad. Redirige a landing si no hay sesión.
     */
    public function index(): void
    {
        if (empty($_SESSION['usuario'])) {
            $this->landing();
            return;
        }
        $this->dashboard();
    }
}

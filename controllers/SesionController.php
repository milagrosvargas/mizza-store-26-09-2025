<?php
// mizzastore/controllers/SesionController.php

class SesionController
{
    /** Permite usar SesionController::iniciar() donde lo necesites */
    public static function iniciar(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /** Alias semántico de cierre para llamadas estáticas existentes */
    public static function cerrar(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();

        header('Location: index.php?controller=home&action=dashboard');
        exit;
    }

    /** Interno de instancia */
    private function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    public function index(): void
    {
        $this->ensureSession();
        require __DIR__ . '/../views/home/login.php';
    }

    /** Acción para /sesion&action=logout */
    public function logout(): void
    {
        $this->ensureSession();

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();

        $this->redirect('index.php?controller=home&action=dashboard');
    }
}

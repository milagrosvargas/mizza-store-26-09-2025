<?php
// mizzastore/config/auth.php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

/* IDs de perfiles según tu BD */
const ROLE_ADMIN   = 1;
const ROLE_CLIENTE = 2;

function current_user(): ?array {
    return $_SESSION['usuario'] ?? null;
}

function is_logged_in(): bool {
    return !empty($_SESSION['usuario']['id_usuario']);
}

function user_role_id(): ?int {
    return isset($_SESSION['usuario']['perfil']) ? (int)$_SESSION['usuario']['perfil'] : null;
}

function is_admin(): bool {
    return user_role_id() === ROLE_ADMIN;
}

function is_cliente(): bool {
    return user_role_id() === ROLE_CLIENTE;
}

/* Guards reutilizables */
function require_login_or_redirect(): void {
    if (!is_logged_in()) {
        $base = $_SERVER['SCRIPT_NAME'];
        header('Location: ' . $base . '?controller=login&action=index&tab=login');
        exit;
    }
}

function require_admin_or_403(): void {
    if (!is_admin()) {
        http_response_code(403);
        exit('Acceso denegado.');
    }
}

function require_roles_or_redirect(array $allowedRoleIds): void {
    $role = user_role_id();
    if (!$role || !in_array($role, $allowedRoleIds, true)) {
        $base = $_SERVER['SCRIPT_NAME'];
        header('Location: ' . $base . '?controller=home&action=dashboard');
        exit;
    }
}

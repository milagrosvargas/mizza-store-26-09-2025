<?php
require_once __DIR__ . '/../config/database.php';

class LoginController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    }

    // ------------------------------------------------------------
    // Mostrar pestañas Login / Register
    // ------------------------------------------------------------
    public function index(): void
    {
        // CSRF para cada formulario
        $_SESSION['csrf_login'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_reg']   = bin2hex(random_bytes(32));

        $csrf_token_login = $_SESSION['csrf_login'];
        $csrf_token_reg   = $_SESSION['csrf_reg'];

        // Tab activa
        $tab = $_GET['tab'] ?? 'login';

        require __DIR__ . '/../views/home/login.php';
    }

    // ------------------------------------------------------------
    // Autenticar
    // ------------------------------------------------------------
    public function autenticar(): void
    {
        // CSRF
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_login']) ||
            !hash_equals($_SESSION['csrf_login'], $_POST['csrf_token'])
        ) {
            $_SESSION['flash_error'] = 'Token CSRF inválido. Volvé a intentar.';
            header('Location: index.php?controller=login&action=index&tab=login');
            exit;
        }

        $usuarioOEmail = trim($_POST['usuario'] ?? '');
        $password      = trim($_POST['password'] ?? '');

        if ($usuarioOEmail === '' || $password === '') {
            $_SESSION['flash_error'] = 'Completá usuario/email y contraseña.';
            header('Location: index.php?controller=login&action=index&tab=login');
            exit;
        }

        // IMPORTANTE: en tu BD, el email vive en detalle_contacto.descripcion_contacto
        // y el usuario en usuarios.nombre_usuario.
        // Unimos usuarios -> persona -> detalle_contacto
        $sql = "
            SELECT 
                u.id_usuario,
                u.nombre_usuario,
                u.password_usuario,
                u.estado_usuario,
                u.relacion_perfil,
                u.relacion_persona,
                p.nombre_persona,
                p.apellido_persona,
                dc.descripcion_contacto AS email
            FROM usuarios u
            LEFT JOIN persona p
                ON p.id_persona = u.relacion_persona
            LEFT JOIN detalle_contacto dc
                ON dc.id_detalle_contacto = p.id_detalle_contacto
            WHERE u.nombre_usuario = :u
               OR dc.descripcion_contacto = :u
            LIMIT 1
        ";

        $st = $this->pdo->prepare($sql);
        $st->execute([':u' => $usuarioOEmail]);
        $row = $st->fetch();

        if (!$row) {
            $_SESSION['flash_error'] = 'Usuario o contraseña inválidos.';
            header('Location: index.php?controller=login&action=index&tab=login');
            exit;
        }

        // Estado de usuario
        if ((int)$row['estado_usuario'] !== 1) {
            $_SESSION['flash_error'] = 'Tu usuario está inactivo.';
            header('Location: index.php?controller=login&action=index&tab=login');
            exit;
        }

        // Verificar contraseña
        if (!password_verify($password, $row['password_usuario'])) {
            $_SESSION['flash_error'] = 'Usuario o contraseña inválidos.';
            header('Location: index.php?controller=login&action=index&tab=login');
            exit;
        }

        // OK → setear sesión
        $_SESSION['usuario'] = [
            'id_usuario'   => (int)$row['id_usuario'],
            'usuario'      => $row['nombre_usuario'],
            'id_perfil'    => (int)$row['relacion_perfil'],   // 1 = Admin, 2 = Cliente (según tu BD)
            'id_persona'   => (int)$row['relacion_persona'],
            'nombre'       => $row['nombre_persona'] ?? '',
            'apellido'     => $row['apellido_persona'] ?? '',
            'email'        => $row['email'] ?? '',
        ];

        // Limpio CSRF de login para no reusar
        unset($_SESSION['csrf_login']);

        // Redirección según perfil
        if ((int)$row['relacion_perfil'] === 1) {
            // Admin → dashboard
            header('Location: index.php?controller=home&action=dashboard');
        } else {
            // Cliente → landing
            header('Location: index.php?controller=home&action=landing');
        }
        exit;
    }

    // ------------------------------------------------------------
    // Registro básico (opcional: ajuste a tu modelo actual)
    // ------------------------------------------------------------
    public function register(): void
    {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_reg']) ||
            !hash_equals($_SESSION['csrf_reg'], $_POST['csrf_token'])
        ) {
            $_SESSION['flash_error'] = 'Token CSRF inválido. Volvé a intentar.';
            header('Location: index.php?controller=login&action=index&tab=register');
            exit;
        }

        $nombre   = trim($_POST['nombre_completo'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $usuario  = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($nombre === '' || $email === '' || $usuario === '' || $password === '') {
            $_SESSION['flash_error'] = 'Completá todos los campos.';
            header('Location: index.php?controller=login&action=index&tab=register');
            exit;
        }

        // Validaciones mínimas (ajustá a tus reglas)
        if (strlen($password) < 6) {
            $_SESSION['flash_error'] = 'La contraseña debe tener 6 o más caracteres.';
            header('Location: index.php?controller=login&action=index&tab=register');
            exit;
        }

        // Unicidad de usuario o email
        $sqlCheck = "
            SELECT 1
            FROM usuarios u
            LEFT JOIN persona p ON p.id_persona = u.relacion_persona
            LEFT JOIN detalle_contacto dc ON dc.id_detalle_contacto = p.id_detalle_contacto
            WHERE u.nombre_usuario = :u OR dc.descripcion_contacto = :e
            LIMIT 1
        ";
        $st = $this->pdo->prepare($sqlCheck);
        $st->execute([':u' => $usuario, ':e' => $email]);
        if ($st->fetch()) {
            $_SESSION['flash_error'] = 'El usuario o email ya existen.';
            header('Location: index.php?controller=login&action=index&tab=register');
            exit;
        }

        // Alta rápida de persona + detalle_contacto + usuario
        try {
            $this->pdo->beginTransaction();

            // 1) detalle_contacto (email). Suponemos id_tipo_contacto=1 es "Correo"
            $st = $this->pdo->prepare("
                INSERT INTO detalle_contacto (descripcion_contacto, id_tipo_contacto)
                VALUES (:e, 1)
            ");
            $st->execute([':e' => $email]);
            $id_detalle_contacto = (int)$this->pdo->lastInsertId();

            // 2) persona (guardamos nombre completo en nombre_persona)
            $st = $this->pdo->prepare("
                INSERT INTO persona (nombre_persona, id_genero, id_domicilio, id_detalle_documento, id_detalle_contacto)
                VALUES (:n, NULL, NULL, NULL, :idc)
            ");
            $st->execute([':n' => $nombre, ':idc' => $id_detalle_contacto]);
            $id_persona = (int)$this->pdo->lastInsertId();

            // 3) usuarios (perfil 2 = Cliente, estado 1 = activo)
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $st = $this->pdo->prepare("
                INSERT INTO usuarios (nombre_usuario, password_usuario, estado_usuario, relacion_perfil, relacion_persona)
                VALUES (:u, :p, 1, 2, :idp)
            ");
            $st->execute([
                ':u'   => $usuario,
                ':p'   => $hash,
                ':idp' => $id_persona
            ]);

            $id_usuario = (int)$this->pdo->lastInsertId();

            $this->pdo->commit();

            // Login directo
            $_SESSION['usuario'] = [
                'id_usuario' => $id_usuario,
                'usuario'    => $usuario,
                'id_perfil'  => 2,
                'id_persona' => $id_persona,
                'nombre'     => $nombre,
                'apellido'   => '',
                'email'      => $email,
            ];
            unset($_SESSION['csrf_reg']);

            header('Location: index.php?controller=home&action=landing');
            exit;
        } catch (Throwable $th) {
            $this->pdo->rollBack();
            $_SESSION['flash_error'] = 'No se pudo registrar: ' . $th->getMessage();
            header('Location: index.php?controller=login&action=index&tab=register');
            exit;
        }
    }

    // ------------------------------------------------------------
    // Cerrar sesión
    // ------------------------------------------------------------
    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?controller=home&action=landing');
        exit;
    }
}

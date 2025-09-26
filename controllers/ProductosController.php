<?php
// mizzastore/controllers/ProductosController.php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../config/auth.php';

class ProductosController
{
    private Producto $model;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->model = new Producto();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
        }
        // No exigimos login: el catálogo y ver son públicos.
    }

    private function base(): string { return $_SERVER['SCRIPT_NAME']; }
    private function csrfCheck(): void {
        $t = $_POST['csrf_token'] ?? '';
        if (!$t || !hash_equals($_SESSION['csrf_token'], $t)) { http_response_code(403); exit('CSRF token inválido'); }
    }
    private function flash(string $type, string $title, string $msg): void {
        $_SESSION['flash'] = ['type'=>$type,'title'=>$title,'message'=>$msg];
    }
    private function redirectList(): never {
        header('Location: ' . $this->base() . '?controller=productos&action=index');
        exit;
    }

    /* =================== PÚBLICO =================== */
    public function catalogo(): void
    {
        $page_title = 'Productos';
        $MZ_HIDE_CHROME = false;

        // Filtros y paginación
        $catId = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
        $perPage = 12;
        $p = max(1, (int)($_GET['p'] ?? 1));
        $total = $this->model->countAll($catId);
        $pages = max(1, (int)ceil($total / $perPage));
        if ($p > $pages) $p = $pages;
        $offset = ($p - 1) * $perPage;

        $items      = $this->model->page($perPage, $offset, $catId);
        $categorias = $this->model->categorias();

        require __DIR__ . '/../views/productos/catalogo.php';
    }

    public function ver(): void
    {
        $page_title = 'Producto';
        $MZ_HIDE_CHROME = false;

        $id = (int)($_GET['id'] ?? 0);
        $row = $this->model->find($id);
        if (!$row) {
            http_response_code(404);
            exit('Producto no encontrado');
        }

        require __DIR__ . '/../views/productos/ver.php';
    }

    /* =================== ADMIN =================== */
    public function index(): void
    {
        require_login_or_redirect();
        require_admin_or_403();

        $page_title = 'Productos (admin)';
        $MZ_HIDE_CHROME = false;

        $items       = $this->model->all();
        $categorias  = $this->model->categorias();
        $subcats     = $this->model->subCategorias();
        $marcas      = $this->model->marcas();
        $unidades    = $this->model->unidades();
        $estados     = $this->model->estados();
        $csrf_token  = $_SESSION['csrf_token'];

        require __DIR__ . '/../views/productos/index.php';
        unset($_SESSION['flash']);
    }

    public function store(): void
    {
        require_login_or_redirect();
        require_admin_or_403();
        $this->csrfCheck();

        $nombre  = trim($_POST['nombre_producto'] ?? '');
        $desc    = trim($_POST['descripcion_producto'] ?? '');
        $barcode = trim($_POST['codigo_barras'] ?? '');
        $precio  = (float)($_POST['precio_producto'] ?? 0);
        $stock   = (int)($_POST['stock_producto'] ?? 0);
        $cat     = (int)($_POST['id_categoria'] ?? 0);
        $subcat  = (int)($_POST['id_sub_categoria'] ?? 0);
        $marca   = (int)($_POST['id_marca'] ?? 0);
        $unidad  = (int)($_POST['id_unidad_medida'] ?? 0);
        $estado  = (int)($_POST['id_estado_logico'] ?? 1);

        if ($nombre === '' || $precio <= 0 || $cat <= 0 || $marca <= 0 || $unidad <= 0) {
            $this->flash('error','Datos inválidos','Completá los campos requeridos.');
            $this->redirectList();
        }

        $imgPath = null;
        if (!empty($_FILES['imagen_producto']['name'])) {
            $imgPath = $this->guardarImagen($_FILES['imagen_producto']);
            if ($imgPath === null) {
                $this->flash('error','Imagen','Formato no permitido (jpg, jpeg, png, webp) o error de subida.');
                $this->redirectList();
            }
        }

        $ok = $this->model->create([
            'nombre_producto'      => $nombre,
            'descripcion_producto' => $desc,
            'codigo_barras'        => $barcode !== '' ? $barcode : null,
            'imagen_producto'      => $imgPath,
            'precio_producto'      => $precio,
            'stock_producto'       => $stock,
            'id_categoria'         => $cat,
            'id_sub_categoria'     => $subcat ?: null,
            'id_marca'             => $marca,
            'id_unidad_medida'     => $unidad,
            'id_estado_logico'     => $estado ?: 1,
        ]);

        $this->flash($ok ? 'success' : 'error',
                     $ok ? 'Guardado' : 'Error',
                     $ok ? 'Producto creado correctamente.' : 'No se pudo crear el producto.');
        $this->redirectList();
    }

    public function update(): void
    {
        require_login_or_redirect();
        require_admin_or_403();
        $this->csrfCheck();

        $id      = (int)($_POST['id_producto'] ?? 0);
        $nombre  = trim($_POST['nombre_producto'] ?? '');
        $desc    = trim($_POST['descripcion_producto'] ?? '');
        $barcode = trim($_POST['codigo_barras'] ?? '');
        $precio  = (float)($_POST['precio_producto'] ?? 0);
        $stock   = (int)($_POST['stock_producto'] ?? 0);
        $cat     = (int)($_POST['id_categoria'] ?? 0);
        $subcat  = (int)($_POST['id_sub_categoria'] ?? 0);
        $marca   = (int)($_POST['id_marca'] ?? 0);
        $unidad  = (int)($_POST['id_unidad_medida'] ?? 0);
        $estado  = (int)($_POST['id_estado_logico'] ?? 1);
        $imgOld  = $_POST['imagen_actual'] ?? null;

        if ($id<=0 || $nombre==='' || $precio<=0 || $cat<=0 || $marca<=0 || $unidad<=0) {
            $this->flash('error','Datos inválidos','Revisá los campos.');
            $this->redirectList();
        }

        $imgPath = $imgOld;
        if (!empty($_FILES['imagen_producto']['name'])) {
            $imgPath = $this->guardarImagen($_FILES['imagen_producto']);
            if ($imgPath === null) {
                $this->flash('error','Imagen','Formato no permitido (jpg, jpeg, png, webp) o error de subida.');
                $this->redirectList();
            }
        }

        $ok = $this->model->update($id, [
            'nombre_producto'      => $nombre,
            'descripcion_producto' => $desc,
            'codigo_barras'        => $barcode !== '' ? $barcode : null,
            'imagen_producto'      => $imgPath,
            'precio_producto'      => $precio,
            'stock_producto'       => $stock,
            'id_categoria'         => $cat,
            'id_sub_categoria'     => $subcat ?: null,
            'id_marca'             => $marca,
            'id_unidad_medida'     => $unidad,
            'id_estado_logico'     => $estado ?: 1,
        ]);

        $this->flash($ok ? 'success' : 'error',
                     $ok ? 'Actualizado' : 'Error',
                     $ok ? 'Producto actualizado.' : 'No se pudo actualizar.');
        $this->redirectList();
    }

    public function delete(): void
    {
        require_login_or_redirect();
        require_admin_or_403();
        $this->csrfCheck();

        $id = (int)($_POST['id_producto'] ?? 0);
        if ($id<=0) { $this->flash('error','ID inválido',''); $this->redirectList(); }

        $ok = $this->model->delete($id);
        $this->flash($ok ? 'success' : 'error',
                     $ok ? 'Eliminado' : 'Error',
                     $ok ? 'Producto eliminado.' : 'No se pudo eliminar (FK).');
        $this->redirectList();
    }

    private function guardarImagen(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed, true)) return null;

        $dir = __DIR__ . '/../assets/images/productos';
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $name = uniqid('prd_') . '.' . $ext;
        $dest = $dir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;

        return 'assets/images/productos/' . $name;
    }
}



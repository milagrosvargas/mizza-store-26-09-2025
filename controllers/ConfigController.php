<?php
// mizzastore/controllers/ConfigController.php

class ConfigController
{
    /* =====================  Helpers de sesión / CSRF / alertas  ===================== */
    private function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    private function csrfCheck(): void
    {
        $this->ensureSession();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = isset($_POST['csrf_token']) &&
                  hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
            if (!$ok) {
                http_response_code(400);
                die('CSRF token inválido.');
            }
        }
    }

    private function redirectWithAlert(string $type, string $title, string $msg): never
    {
        $this->ensureSession();
        $_SESSION['flash'] = ['type' => $type, 'title' => $title, 'msg' => $msg];
        $dest = !empty($_SERVER['HTTP_REFERER'])
              ? $_SERVER['HTTP_REFERER']
              : 'index.php?controller=home&action=dashboard';
        header('Location: ' . $dest);
        exit;
    }

    /* ===================== util: manejo de imagen ===================== */
    private function handleImageUpload(string $field, ?string $oldFile = null): ?string
    {
        if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            // No se subió archivo: devolvemos null para “no cambiar”
            return null;
        }

        $file   = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->redirectWithAlert('error', 'Subida fallida', 'Error al subir la imagen.');
        }

        // Validaciones básicas
        $maxBytes = 2 * 1024 * 1024; // 2 MB
        if ($file['size'] > $maxBytes) {
            $this->redirectWithAlert('error', 'Imagen muy grande', 'Máximo permitido: 2 MB.');
        }

        $allowedExt = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            $this->redirectWithAlert('error', 'Formato no permitido', 'Usa JPG, JPEG, PNG o WEBP.');
        }

        // (Opcional) validar MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowedMime = ['image/jpeg','image/png','image/webp'];
        if (!in_array($mime, $allowedMime, true)) {
            $this->redirectWithAlert('error', 'Archivo no válido', 'El archivo no parece ser una imagen válida.');
        }

        // Carpeta destino
        $uploadDir = __DIR__ . '/../assets/images/categorias';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        // Nombre único
        $newName = 'cat_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destAbs = $uploadDir . '/' . $newName;

        if (!move_uploaded_file($file['tmp_name'], $destAbs)) {
            $this->redirectWithAlert('error', 'No se pudo guardar', 'No se pudo mover la imagen al destino.');
        }

        // Si hay archivo anterior y se está reemplazando, borrarlo
        if ($oldFile) {
            $oldAbs = $uploadDir . '/' . $oldFile;
            if (is_file($oldAbs)) {
                @unlink($oldAbs);
            }
        }

        // Devolvemos solo el nombre de archivo (lo guardamos en BD)
        return $newName;
    }

    /* =====================  TIPO_DOCUMENTO  ===================== */
    public function tipo_documento(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/TipoDocumento.php';
        $model = new TipoDocumento();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/tipo_documento.php';
    }

    public function tipo_documento_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_tipo_documento'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/TipoDocumento.php';
        $ok = (new TipoDocumento())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function tipo_documento_update(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_tipo_documento'] ?? 0);
        $nombre = trim($_POST['nombre_tipo_documento'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/TipoDocumento.php';
        $ok = (new TipoDocumento())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function tipo_documento_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_tipo_documento'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/TipoDocumento.php';
        $ok = (new TipoDocumento())->delete($id);
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica dependencias/uso.');
    }

    /* =====================  ESTADO_LOGICO  ===================== */
    public function estado_logico(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/EstadoLogico.php';
        $model = new EstadoLogico();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/estado_logico.php';
    }

    public function estado_logico_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_estado_logico'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/EstadoLogico.php';
        $ok = (new EstadoLogico())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function estado_logico_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_estado_logico'] ?? 0);
        $nombre = trim($_POST['nombre_estado_logico'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/EstadoLogico.php';
        $ok = (new EstadoLogico())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function estado_logico_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_estado_logico'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/EstadoLogico.php';
        $ok = (new EstadoLogico())->delete($id);
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica dependencias/uso.');
    }

    /* =====================  PAIS  ===================== */
    public function pais(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Pais.php';
        $model = new Pais();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/pais.php';
    }

    public function pais_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_pais'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/Pais.php';
        $ok = (new Pais())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function pais_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_pais'] ?? 0);
        $nombre = trim($_POST['nombre_pais'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/Pais.php';
        $ok = (new Pais())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function pais_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_pais'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/Pais.php';
        try {
            $ok = (new Pais())->delete($id);
        } catch (Throwable $e) {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen provincias asociadas.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen provincias asociadas.');
    }

    /* =====================  PROVINCIA  ===================== */
    public function provincia(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Provincia.php';
        $model = new Provincia();
        $items = $model->all();
        $paises = $model->allPaises();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/provincia.php';
    }

    public function provincia_store(): void
    {
        $this->csrfCheck();
        $nombre  = trim($_POST['nombre_provincia'] ?? '');
        $id_pais = isset($_POST['id_pais']) && $_POST['id_pais'] !== '' ? (int)$_POST['id_pais'] : null;

        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/Provincia.php';
        $ok = (new Provincia())->create($nombre, $id_pais);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function provincia_update(): void
    {
        $this->csrfCheck();
        $id      = (int)($_POST['id_provincia'] ?? 0);
        $nombre  = trim($_POST['nombre_provincia'] ?? '');
        $id_pais = isset($_POST['id_pais']) && $_POST['id_pais'] !== '' ? (int)$_POST['id_pais'] : null;

        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/Provincia.php';
        $ok = (new Provincia())->update($id, $nombre, $id_pais);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function provincia_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_provincia'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/Provincia.php';
        try {
            $ok = (new Provincia())->delete($id);
        } catch (Throwable $e) {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen localidades asociadas.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen localidades asociadas.');
    }

    /* =====================  LOCALIDAD  ===================== */
    public function localidad(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Localidad.php';
        $model = new Localidad();
        $items = $model->all();
        $provincias = $model->allProvincias();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/localidad.php';
    }

    public function localidad_store(): void
    {
        $this->csrfCheck();
        $nombre  = trim($_POST['nombre_localidad'] ?? '');
        $id_prov = isset($_POST['id_provincia']) && $_POST['id_provincia'] !== '' ? (int)$_POST['id_provincia'] : null;

        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/Localidad.php';
        $ok = (new Localidad())->create($nombre, $id_prov);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function localidad_update(): void
    {
        $this->csrfCheck();
        $id      = (int)($_POST['id_localidad'] ?? 0);
        $nombre  = trim($_POST['nombre_localidad'] ?? '');
        $id_prov = isset($_POST['id_provincia']) && $_POST['id_provincia'] !== '' ? (int)$_POST['id_provincia'] : null;

        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/Localidad.php';
        $ok = (new Localidad())->update($id, $nombre, $id_prov);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function localidad_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_localidad'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/Localidad.php';
        try {
            $ok = (new Localidad())->delete($id);
        } catch (Throwable $e) {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen barrios asociados.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen barrios asociados.');
    }

    /* =====================  BARRIO  ===================== */
    public function barrio(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Barrio.php';
        $model = new Barrio();
        $items = $model->all();
        $localidades = $model->allLocalidades();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/barrio.php';
    }

    public function barrio_store(): void
    {
        $this->csrfCheck();
        $nombre   = trim($_POST['nombre_barrio'] ?? '');
        $id_local = isset($_POST['id_localidad']) && $_POST['id_localidad'] !== '' ? (int)$_POST['id_localidad'] : null;

        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/Barrio.php';
        $ok = (new Barrio())->create($nombre, $id_local);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function barrio_update(): void
    {
        $this->csrfCheck();
        $id       = (int)($_POST['id_barrio'] ?? 0);
        $nombre   = trim($_POST['nombre_barrio'] ?? '');
        $id_local = isset($_POST['id_localidad']) && $_POST['id_localidad'] !== '' ? (int)$_POST['id_localidad'] : null;

        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/Barrio.php';
        $ok = (new Barrio())->update($id, $nombre, $id_local);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function barrio_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_barrio'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/Barrio.php';
        try {
            $ok = (new Barrio())->delete($id);
        } catch (Throwable $e) {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen referencias asociadas.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen referencias asociadas.');
    }

    /* =====================  TIPO_CONTACTO  ===================== */
    public function tipo_contacto(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/TipoContacto.php';
        $model = new TipoContacto();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/tipo_contacto.php';
    }

    public function tipo_contacto_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_tipo_contacto'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/TipoContacto.php';
        $ok = (new TipoContacto())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function tipo_contacto_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_tipo_contacto'] ?? 0);
        $nombre = trim($_POST['nombre_tipo_contacto'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/TipoContacto.php';
        $ok = (new TipoContacto())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function tipo_contacto_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_tipo_contacto'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/TipoContacto.php';
        $ok = (new TipoContacto())->delete($id);
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica dependencias/uso.');
    }

    /* =====================  GENERO  ===================== */
    public function genero(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Genero.php';
        $model = new Genero();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/genero.php';
    }

    public function genero_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_genero'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/Genero.php';
        $ok = (new Genero())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function genero_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_genero'] ?? 0);
        $nombre = trim($_POST['nombre_genero'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/Genero.php';
        $ok = (new Genero())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function genero_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_genero'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/Genero.php';
        $ok = (new Genero())->delete($id);
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica dependencias/uso.');
    }

    /* =====================  CATEGORIA (con imagen)  ===================== */
    public function categoria(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Categoria.php';
        $model = new Categoria();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        // Ruta pública para mostrar imágenes
        $imgBaseUrl = 'assets/images/categorias/';
        require __DIR__ . '/../views/configuracion/categoria.php';
    }

    public function categoria_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_categoria'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');

        // Subir imagen (opcional)
        $newImage = $this->handleImageUpload('imagen_categoria', null); // retorna nombre archivo o null

        require_once __DIR__ . '/../models/Categoria.php';
        $ok = (new Categoria())->create($nombre, $newImage);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function categoria_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_categoria'] ?? 0);
        $nombre = trim($_POST['nombre_categoria'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');

        require_once __DIR__ . '/../models/Categoria.php';
        $model = new Categoria();
        $row = $model->find($id);
        if (!$row) $this->redirectWithAlert('error','No encontrado','La categoría no existe.');

        // Si suben imagen nueva, se reemplaza y se borra la anterior
        $newImage = $this->handleImageUpload('imagen_categoria', $row['imagen_categoria'] ?? null);

        $ok = $model->update($id, $nombre, $newImage); // si $newImage es null, no cambia la imagen
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function categoria_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_categoria'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');

        require_once __DIR__ . '/../models/Categoria.php';
        $model = new Categoria();
        $row = $model->find($id);
        if (!$row) $this->redirectWithAlert('error','No encontrado','La categoría no existe.');

        $ok = $model->delete($id);
        if ($ok) {
            // borrar imagen del disco si existía
            if (!empty($row['imagen_categoria'])) {
                $abs = __DIR__ . '/../assets/images/categorias/' . $row['imagen_categoria'];
                if (is_file($abs)) { @unlink($abs); }
            }
            $this->redirectWithAlert('success','Eliminado','Registro eliminado.');
        } else {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica dependencias/uso.');
        }
    }

        /* =====================  SUB_CATEGORIA  ===================== */
    public function sub_categoria(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/SubCategoria.php';
        $model = new SubCategoria();
        $items = $model->all();                 // subcategorías con nombre de categoría
        $categorias = $model->allCategorias();  // para el <select>
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/sub_categoria.php';
    }

    public function sub_categoria_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_sub_categoria'] ?? '');
        $id_cat = isset($_POST['id_categoria']) && $_POST['id_categoria'] !== '' ? (int)$_POST['id_categoria'] : null;

        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/SubCategoria.php';
        $ok = (new SubCategoria())->create($nombre, $id_cat);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function sub_categoria_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_sub_categoria'] ?? 0);
        $nombre = trim($_POST['nombre_sub_categoria'] ?? '');
        $id_cat = isset($_POST['id_categoria']) && $_POST['id_categoria'] !== '' ? (int)$_POST['id_categoria'] : null;

        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/SubCategoria.php';
        $ok = (new SubCategoria())->update($id, $nombre, $id_cat);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function sub_categoria_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_sub_categoria'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/SubCategoria.php';
        try {
            $ok = (new SubCategoria())->delete($id);
        } catch (Throwable $e) {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen productos asociados.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen productos asociados.');
    }

        /* =====================  MARCA  ===================== */
    public function marca(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/Marca.php';
        $model = new Marca();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/marca.php';
    }

    public function marca_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_marca'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/Marca.php';
        $ok = (new Marca())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function marca_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_marca'] ?? 0);
        $nombre = trim($_POST['nombre_marca'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/Marca.php';
        $ok = (new Marca())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function marca_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_marca'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/Marca.php';
        try {
            $ok = (new Marca())->delete($id);
        } catch (Throwable $e) {
            // Si hay productos que referencian la marca, puede fallar por FK
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen productos asociados.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen productos asociados.');
    }

        /* =====================  UNIDAD_MEDIDA  ===================== */
    public function unidad_medida(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/UnidadMedida.php';
        $model = new UnidadMedida();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/unidad_medida.php';
    }

    public function unidad_medida_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_unidad'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/UnidadMedida.php';
        $ok = (new UnidadMedida())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function unidad_medida_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_unidad_medida'] ?? 0);
        $nombre = trim($_POST['nombre_unidad'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/UnidadMedida.php';
        $ok = (new UnidadMedida())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function unidad_medida_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_unidad_medida'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/UnidadMedida.php';
        try {
            $ok = (new UnidadMedida())->delete($id);
        } catch (Throwable $e) {
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen productos asociados.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen productos asociados.');
    }

        /* =====================  METODO_PAGO  ===================== */
    public function metodo_pago(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/MetodoPago.php';
        $model = new MetodoPago();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/metodo_pago.php';
    }

    public function metodo_pago_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_metodo_pago'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/MetodoPago.php';
        $ok = (new MetodoPago())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function metodo_pago_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_metodo_pago'] ?? 0);
        $nombre = trim($_POST['nombre_metodo_pago'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/MetodoPago.php';
        $ok = (new MetodoPago())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function metodo_pago_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_metodo_pago'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/MetodoPago.php';
        try {
            $ok = (new MetodoPago())->delete($id);
        } catch (Throwable $e) {
            // Si alguna tabla (p.ej. ventas/pedidos) referencia el método, puede fallar por FK
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen registros asociados.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen registros asociados.');
    }

        /* =====================  TIPO_NOTA  ===================== */
    public function tipo_nota(): void
    {
        $this->ensureSession();
        require_once __DIR__ . '/../models/TipoNota.php';
        $model = new TipoNota();
        $items = $model->all();
        $csrf_token = $_SESSION['csrf_token'];
        require __DIR__ . '/../views/configuracion/tipo_nota.php';
    }

    public function tipo_nota_store(): void
    {
        $this->csrfCheck();
        $nombre = trim($_POST['nombre_tipo_nota'] ?? '');
        if ($nombre === '') $this->redirectWithAlert('error','Dato requerido','El nombre no puede estar vacío.');
        require_once __DIR__ . '/../models/TipoNota.php';
        $ok = (new TipoNota())->create($nombre);
        $ok ? $this->redirectWithAlert('success','Guardado','Se agregó correctamente.')
            : $this->redirectWithAlert('error','No se pudo guardar','Intenta nuevamente.');
    }

    public function tipo_nota_update(): void
    {
        $this->csrfCheck();
        $id     = (int)($_POST['id_tipo_nota'] ?? 0);
        $nombre = trim($_POST['nombre_tipo_nota'] ?? '');
        if ($id <= 0 || $nombre === '') $this->redirectWithAlert('error','Datos inválidos','Verifica los campos.');
        require_once __DIR__ . '/../models/TipoNota.php';
        $ok = (new TipoNota())->update($id, $nombre);
        $ok ? $this->redirectWithAlert('success','Actualizado','Se guardaron los cambios.')
            : $this->redirectWithAlert('error','No se pudo actualizar','Intenta nuevamente.');
    }

    public function tipo_nota_delete(): void
    {
        $this->csrfCheck();
        $id = (int)($_POST['id_tipo_nota'] ?? 0);
        if ($id <= 0) $this->redirectWithAlert('error','ID inválido','No se pudo eliminar.');
        require_once __DIR__ . '/../models/TipoNota.php';
        try {
            $ok = (new TipoNota())->delete($id);
        } catch (Throwable $e) {
            // Si otra tabla referencia el tipo de nota, podría fallar por FK
            $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen registros asociados.');
        }
        $ok ? $this->redirectWithAlert('success','Eliminado','Registro eliminado.')
            : $this->redirectWithAlert('error','No se pudo eliminar','Verifica si existen registros asociados.');
    }





}

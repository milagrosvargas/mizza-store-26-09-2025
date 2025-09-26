<?php
// mizzastore/index.php
declare(strict_types=1);
session_start();

function require_controller(string $name): void {
  static $loaded = [];
  if (isset($loaded[$name])) return;
  $path = __DIR__ . '/controllers/' . $name . '.php';
  if (!is_file($path)) { http_response_code(404); echo "Controlador no encontrado: {$name}"; exit; }
  require_once $path;
  $loaded[$name] = true;
}
function call_action(object $ctrl, string $action): void {
  if (!method_exists($ctrl, $action)) { http_response_code(404); echo "Acción no encontrada: " . get_class($ctrl) . "->{$action}"; exit; }
  $ctrl->{$action}(); exit;
}

$controller = strtolower($_GET['controller'] ?? 'home');
$action     = strtolower($_GET['action'] ?? 'landing');

// sesión robusta
$SESSION_USER = $_SESSION['usuario'] ?? $_SESSION['user'] ?? $_SESSION['auth'] ?? null;
$isAuth  = !empty($SESSION_USER);
$perfil  = null;
if ($isAuth) {
  $perfil = $SESSION_USER['relacion_perfil'] ?? $SESSION_USER['id_perfil'] ?? $SESSION_USER['perfil'] ?? $SESSION_USER['rol'] ?? null;
  if (is_string($perfil) && ctype_digit($perfil)) $perfil = (int)$perfil;
}
$isAdmin  = ($perfil === 1 || $perfil === 'admin' || $perfil === 'ADMIN');
$isClient = ($perfil === 2 || $perfil === 'cliente');

$PUBLIC = [
  'home'      => ['landing','index'],
  'login'     => ['index','autenticar','registrar'],
  'productos' => ['catalogo','detalle','buscar'],
  'blog'      => ['index','detalle'],
];

$PRIVATE = [
  'home'       => ['dashboard','index'],
  'sesion'     => ['logout','index'],
  'productos'  => ['index','store','update','delete','upload','crud_producto'],
  'config'     => ['tipo_documento','tipo_documento_store','tipo_documento_edit','tipo_documento_delete',
                   'estado_logico','estado_logico_store','estado_logico_edit','estado_logico_delete',
                   'pais','pais_store','pais_edit','pais_delete',
                   'provincia','provincia_store','provincia_edit','provincia_delete',
                   'localidad','localidad_store','localidad_edit','localidad_delete',
                   'barrio','barrio_store','barrio_edit','barrio_delete',
                   'tipo_contacto','tipo_contacto_store','tipo_contacto_edit','tipo_contacto_delete',
                   'genero','genero_store','genero_edit','genero_delete',
                   'categoria','categoria_store','categoria_edit','categoria_delete',
                   'sub_categoria','sub_categoria_store','sub_categoria_edit','sub_categoria_delete',
                   'marca','marca_store','marca_edit','marca_delete',
                   'unidad_medida','unidad_medida_store','unidad_medida_edit','unidad_medida_delete',
                   'metodo_pago','metodo_pago_store','metodo_pago_edit','metodo_pago_delete',
                   'tipo_nota','tipo_nota_store','tipo_nota_edit','tipo_nota_delete'],
  'pedidos'    => ['index','detalle','mis_pedidos'],
  'clientes'   => ['index','detalle'],
];

// sin sesión → solo públicas
if (!$isAuth) {
  if (isset($PUBLIC[$controller]) && in_array($action, $PUBLIC[$controller], true)) {
    switch ($controller) {
      case 'home':      require_controller('HomeController');      call_action(new HomeController(), $action === 'index' ? 'landing' : $action); break;
      case 'login':     require_controller('LoginController');     call_action(new LoginController(), $action); break;
      case 'productos': require_controller('ProductosController'); call_action(new ProductosController(), $action); break;
      case 'blog':      require_controller('BlogController');      call_action(new BlogController(), $action); break;
    }
  }
  // default público
  require_controller('HomeController'); call_action(new HomeController(), 'landing');
}

// con sesión → privadas
if (!isset($PRIVATE[$controller]) || !in_array($action, $PRIVATE[$controller], true)) {
  http_response_code(403); echo 'Ruta privada no permitida.'; exit;
}

switch ($controller) {
  case 'home':
    require_controller('HomeController'); call_action(new HomeController(), $action === 'index' ? 'dashboard' : $action);
    break;

  case 'sesion':
    require_controller('SesionController'); call_action(new SesionController(), $action);
    break;

  case 'productos':
    if (!$isAdmin && in_array($action, ['index','store','update','delete','upload','crud_producto'], true)) {
      http_response_code(403); exit('Solo administrador.');
    }
    require_controller('ProductosController'); call_action(new ProductosController(), $action);
    break;

  case 'config':
    if (!$isAdmin) { http_response_code(403); exit('Solo administrador.'); }
    require_controller('ConfigController'); call_action(new ConfigController(), $action);
    break;

  case 'pedidos':
    // admin o cliente
    if (!($isAdmin || $isClient)) { http_response_code(403); exit('Solo clientes/administrador.'); }
    require_controller('PedidosController'); call_action(new PedidosController(), $action);
    break;

  case 'clientes':
    if (!$isAdmin) { http_response_code(403); exit('Solo administrador.'); }
    require_controller('ClientesController'); call_action(new ClientesController(), $action);
    break;

  default:
    http_response_code(404); echo 'Controlador desconocido.'; exit;
}

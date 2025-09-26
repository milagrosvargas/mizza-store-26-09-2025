CREATE DATABASE mizzastore;
USE mizzastore;

CREATE TABLE estado_logico(
id_estado_logico INT PRIMARY KEY AUTO_INCREMENT,
nombre_estado VARCHAR(50)
);
INSERT INTO estado_logico (nombre_estado) VALUES ('Activo'), ('Inactivo'), ('Pagado'), ('Pendiente'), ('En proceso'), ('Completado'), 
('Facturado'), ('Anulada'), ('En espera de revisión'), ('Pendiente de pago'), ('En cobro'), ('Condonada'), ('Atrasada'), 
('Devuelto'), ('Revisado'), ('Rechazado'), ('Aceptado'), ('En espera de reposición'), ('Descatalogado'), ('Suspendido'), 
('Procesando'), ('Enviado'), ('Entregado'), ('Cancelado'), ('Disponible'), ('No disponible'), ('Agotado'), ('En oferta'),
('En evaluación'), ('Verificado'), ('No verificado'), ('Bloqueado'), ('Vencida'), ('Pagada parcialmente'), ('Exenta'), 
('Aplazada'), ('Próxima a vencer'), ('Pendiente de revisión'), ('En litigio'), ('Abierta'), ('Cerrada');

CREATE TABLE pais (
id_pais INT PRIMARY KEY AUTO_INCREMENT,
nombre_pais VARCHAR(50)
);
INSERT INTO pais (nombre_pais) VALUES ('Argentina');

CREATE TABLE provincia (
id_provincia INT PRIMARY KEY auto_increment,
nombre_provincia VARCHAR(50),
id_pais INT,
FOREIGN KEY (id_pais) REFERENCES pais(id_pais)
);
INSERT INTO provincia (nombre_provincia, id_pais) VALUES ('Formosa', 1);

CREATE TABLE localidad (
id_localidad INT PRIMARY KEY AUTO_INCREMENT,
nombre_localidad VARCHAR(50),
id_provincia INT,
FOREIGN KEY (id_provincia) REFERENCES provincia(id_provincia)
);
INSERT INTO localidad (nombre_localidad, id_provincia) VALUES ('Formosa', 1), ('Pirané', 1), ('Pozo del Tigre', 1), ('Laishí', 1), 
('San Martín II', 1), ('Villa Dos Trece', 1), ('Villafañe', 1), ('Ramón Lista', 1), ('Río Muerto', 1), ('Pilcomayo', 1), ('Gral Belgrano', 1),
('Pilagás', 1), ('Matacos', 1), ('Bermejo', 1), ('Las Lomitas', 1), ('Guemes', 1);

CREATE TABLE barrio (
id_barrio INT PRIMARY KEY AUTO_INCREMENT,
nombre_barrio VARCHAR(50),
id_localidad INT,
FOREIGN KEY (id_localidad) REFERENCES localidad(id_localidad)
);
INSERT INTO barrio (nombre_barrio, id_localidad) VALUES ('Barrio La Pilar', 1), ('Barrio 2 de Abril', 1), ('Barrio 7 de Mayo', 1), ('Barrio Antenor Gauna', 1),('Barrio Bernardino Rivadavia', 1),
('Barrio Centenario', 1), ('Barrio Coluccio', 1), ('Barrio Curé Cuá', 1), ('Barrio Divino Niño Jesús', 1), ('Barrio El Amanecer', 1),
('Barrio El Palmar', 1), ('Barrio El Pucú', 1), ('Barrio Eva Perón', 1), ('Barrio Guadalupe', 1), ('Barrio Independencia', 1),
('Barrio Irigoyen', 1), ('Barrio Juan Domingo Perón', 1), ('Barrio Juan Manuel de Rosas', 1), ('Barrio La Colonia', 1), ('Barrio La Lomita', 1),
('Barrio La Nueva Formosa', 1), ('Barrio La Paz', 1), ('Barrio Laguna Siam', 1), ('Barrio Las Orquídeas', 1), ('Barrio Lote 4', 1),
('Barrio Lote 111', 1), ('Barrio Lote 67', 1), ('Barrio Lote Rural 3 Bis', 1), ('Barrio Los Inmigrantes', 1), ('Barrio Los Naranjos', 1),
('Barrio Los Pinos', 1), ('Barrio Mariano Moreno', 1), ('Barrio Medalla Milagrosa', 1), ('Barrio Nanqom', 1), ('Barrio Nuestra Señora de Luján', 1), ('Barrio Parque Urbano', 1),
('Barrio República Argentina', 1),('Barrio Ricardo Balbín', 1), ('Barrio San Agustín', 1), ('Barrio San Antonio', 1),
('Barrio San Carlos', 1), ('Barrio San Cayetano', 1), ('Barrio San Fernando', 1), ('Barrio San Francisco de Asís', 1),
('Barrio San José Obrero', 1), ('Barrio San Juan Bautista', 1), ('Barrio San Lorenzo', 1), ('Barrio San Miguel', 1), ('Barrio San Pedro', 1),
('Barrio San Roque', 1), ('Barrio Santa Rosa', 1), ('Barrio Sagrado Corazón', 1), ('Barrio Sagrado Corazón de María', 1), ('Barrio Simón Bolívar', 1),
('Barrio Timbó', 1), ('Barrio Urunday', 1), ('Barrio Veinticinco de Mayo', 1), ('Barrio Venezuela', 1), ('Barrio Vial', 1),
('Barrio Villa Hermosa', 1), ('Barrio Villa Lourdes', 1), ('Barrio Villa Mabel', 1), ('Barrio Villa del Carmen', 1);

CREATE TABLE tipo_documento (
id_tipo_documento INT PRIMARY KEY AUTO_INCREMENT,
nombre_tipo_documento VARCHAR(50)
);
INSERT INTO tipo_documento (nombre_tipo_documento) VALUES ('DNI'), ('CDI'), ('CUIT'), ('CUIL'), ('DNIe'), ('LC');

CREATE TABLE detalle_documento (
id_detalle_documento INT PRIMARY KEY AUTO_INCREMENT,
id_tipo_documento INT,
descripcion_documento VARCHAR(100),
FOREIGN KEY (id_tipo_documento) REFERENCES tipo_documento(id_tipo_documento)
);

CREATE TABLE tipo_contacto (
id_tipo_contacto INT PRIMARY KEY AUTO_INCREMENT,
nombre_tipo_contacto VARCHAR(50)
);
INSERT INTO tipo_contacto (nombre_tipo_contacto) VALUES ('Correo electrónico'), ('Número de teléfono');

CREATE TABLE detalle_contacto (
id_detalle_contacto INT PRIMARY KEY AUTO_INCREMENT,
descripcion_contacto VARCHAR(100),
id_tipo_contacto INT,
FOREIGN KEY (id_tipo_contacto) REFERENCES tipo_contacto(id_tipo_contacto)
);

CREATE TABLE genero (
id_genero INT PRIMARY KEY AUTO_INCREMENT,
nombre_genero VARCHAR(50) NOT NULL UNIQUE
);
INSERT INTO genero (nombre_genero) VALUES ('Masculino'), ('Femenino'), ('No binario'), ('Prefiero no decirlo');

-- Tabla de Categorías de Productos
CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    imagen_categoria varchar(50),
    alta_categoria timestamp NOT NULL DEFAULT current_timestamp(),
    actualizacion_categoria datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    id_estado_logico INT,
    FOREIGN KEY (id_estado_logico) REFERENCES estado_logico(id_estado_logico)
); -- skincare, makeup, brochas/aplicadores (son las únicas 3 categorías)

CREATE TABLE sub_categoria (
  id_sub_categoria int primary key auto_increment NOT NULL,
  nombre_sub_categoria varchar(50) NOT NULL,
  cant_sub_categoria int(10) NOT NULL,
  id_estado_logico INT,
  id_categoria INT,
  FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
  FOREIGN KEY (id_estado_logico) REFERENCES estado_logico(id_estado_logico)
); -- mascarillas, labiales, cremas faciales, eee paletas etc

CREATE TABLE marca (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(100)
);
INSERT INTO marca (nombre_marca) VALUES ('MAC'), ('Maybelline'), ('NARS'), ('Fenty Beauty'), ('Urban Decay'), ('L’Oréal'),
('Dior'), ('The Ordinary'), ('La Roche-Posay'), ('CeraVe'), ('Neutrogena'), ('Clinique'), ('Estée Lauder');

CREATE TABLE unidad_medida (
id_unidad_medida INT AUTO_INCREMENT PRIMARY KEY,
nombre_unidad_medida VARCHAR(100)
);
INSERT INTO unidad_medida (nombre_unidad_medida) VALUES ('ml'), ('oz'), ('gr'), ('lt'), ('unidades'), ('pieza'),
('pack'), ('kg'), ('sobre');

CREATE TABLE metodo_pago (
id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
nombre_metodo_pago VARCHAR(50)
);
INSERT INTO metodo_pago (nombre_metodo_pago) VALUES ('Efectivo'), ('Tarjeta débito'), ('Tarjeta crédito'), ('Transferencia');

CREATE TABLE tipo_nota (
id_tipo_nota INT AUTO_INCREMENT PRIMARY KEY,
nombre_tipo_nota VARCHAR(50)
);
INSERT INTO tipo_nota (nombre_tipo_nota) VALUES ('Nota de crédito'), ('Nota de débito');

-- ¡! FINAL DE SECCIÓN DE TABLAS MAESTRAS --

-- Banner (para la landing page)
CREATE TABLE banner (
  id_banner int(11) NOT NULL,
  titulo_banner text NOT NULL,
  imagen_banner varchar(50) NOT NULL,
  estado_banner binary(1) DEFAULT NULL
);

CREATE TABLE domicilio (
id_domicilio INT PRIMARY KEY AUTO_INCREMENT,
calle_direccion VARCHAR (100),
numero_direccion VARCHAR(10),
piso_direccion VARCHAR(10),
info_extra_direccion VARCHAR(100),
id_barrio INT,
FOREIGN KEY (id_barrio) REFERENCES barrio(id_barrio)
);

-- Tabla de Productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(255),
    descripcion_producto TEXT,
    precio_producto DECIMAL(10,2),
    stock_producto INT,
    id_categoria INT,
    id_sub_categoria INT,
    id_marca INT,
    id_unidad_medida INT,
    id_estado_logico INT,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
    FOREIGN KEY (id_sub_categoria) REFERENCES sub_categoria(id_sub_categoria),
    FOREIGN KEY (id_marca) REFERENCES marca(id_marca),
    FOREIGN KEY (id_unidad_medida) REFERENCES unidad_medida(id_unidad_medida),
    FOREIGN KEY (id_estado_logico) REFERENCES estado_logico(id_estado_logico)
);

CREATE TABLE persona (
    id_persona INT PRIMARY KEY AUTO_INCREMENT,
    nombre_persona VARCHAR(50),
    apellido_persona VARCHAR(50),
    fecha_nac_persona DATE,
    id_genero INT,
    id_domicilio INT,
    id_detalle_documento INT,
    id_detalle_contacto INT,
    FOREIGN KEY (id_genero) REFERENCES genero(id_genero),
    FOREIGN KEY (id_domicilio) REFERENCES domicilio(id_domicilio),
    FOREIGN KEY (id_detalle_documento) REFERENCES detalle_documento(id_detalle_documento),
    FOREIGN KEY (id_detalle_contacto) REFERENCES detalle_contacto(id_detalle_contacto)
);

CREATE TABLE perfil (
    id_perfil INT PRIMARY KEY AUTO_INCREMENT,
    descripcion_perfil VARCHAR(50),
    activo_perfil TINYINT(1) DEFAULT 1
);
INSERT INTO perfil (descripcion_perfil) VALUES ('Administrador'), ('Empleado'), ('Repartidor'), ('Cliente');

CREATE TABLE modulo (
    id_modulo INT PRIMARY KEY AUTO_INCREMENT,
    descripcion_modulo VARCHAR(100),
    activo_modulo TINYINT(1) DEFAULT 1
);
INSERT INTO modulo (descripcion_modulo) VALUES ('Catálogo'), ('Usuarios'), ('Clientes'), ('Ventas'), ('Inventario'), 
('Productos'), ('Pedidos'), ('Configuración'), ('Blog');

-- Relación entre módulos y perfiles
CREATE TABLE modulo_perfil (
    relacion_modulo INT,
    relacion_perfil INT,
    PRIMARY KEY (relacion_modulo, relacion_perfil),
    FOREIGN KEY (relacion_modulo) REFERENCES modulo(id_modulo) ON DELETE CASCADE,
    FOREIGN KEY (relacion_perfil) REFERENCES perfil(id_perfil) ON DELETE CASCADE
);

-- ADMINISTRADOR (acceso a todo)
INSERT INTO modulo_perfil (relacion_modulo, relacion_perfil) VALUES
(1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1), (9, 1), (10, 1);

-- EMPLEADO
INSERT INTO modulo_perfil (relacion_modulo, relacion_perfil) VALUES
(1, 2), (3, 2), (4, 2), (5, 2), (6, 2), (7, 2), (9, 2);

-- REPARTIDOR (solo pedidos)
INSERT INTO modulo_perfil (relacion_modulo, relacion_perfil) VALUES
(7, 3);

-- CLIENTE
INSERT INTO modulo_perfil (relacion_modulo, relacion_perfil) VALUES
(1, 4), (7, 4),  (9, 4);

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(50),
    password_usuario VARCHAR(255),
    fecha_registro_usuario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	password_temporal VARCHAR(255) DEFAULT NULL, 
    expiracion_password_temporal DATETIME DEFAULT NULL,
    estado_usuario TINYINT(1) DEFAULT 1,
    relacion_persona INT,
    relacion_perfil INT,
    FOREIGN KEY (relacion_persona) REFERENCES persona(id_persona),
    FOREIGN KEY (relacion_perfil) REFERENCES perfil(id_perfil)
);

-- Tabla para administrar sesiones
CREATE TABLE sesion (
    id_sesion INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 0,
    fecha_ultimo_login DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- Tabla para administrar clientes
CREATE TABLE cliente (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,
	estado_cliente TINYINT DEFAULT 1,
    relacion_persona INT NOT NULL,
    FOREIGN KEY (relacion_persona) REFERENCES persona(id_persona)
);

-- Tabla para administrar empleados
CREATE TABLE empleado (
    id_empleado INT PRIMARY KEY AUTO_INCREMENT,
    relacion_persona INT NOT NULL,
    fecha_alta_empleado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_empleado TINYINT DEFAULT 1,
    FOREIGN KEY (relacion_persona) REFERENCES persona(id_persona)
);

-- Tabla de Pedidos
CREATE TABLE pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_estado_logico INT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto_total DECIMAL(10,2),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_estado_logico) REFERENCES estado_logico(id_estado_logico)
);

-- Tabla de Detalle de Pedidos
CREATE TABLE detalle_pedido (
    id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidad_producto INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

-- Tabla de Transacciones de Pago
CREATE TABLE pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_metodo_pago INT,
    estado_pago ENUM('pendiente', 'completado', 'fallido') NOT NULL,
    monto_pago DECIMAL(10,2),
   FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido) ON DELETE CASCADE
);

-- Tabla de Envíos
CREATE TABLE envio (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_domicilio INT,
    estado ENUM('pendiente', 'en camino', 'entregado') NOT NULL,
    fecha_envio TIMESTAMP NULL,
    fecha_entrega TIMESTAMP NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_domicilio) REFERENCES domicilio(id_domicilio)
);

-- Tabla de Wishlist
CREATE TABLE wishlist (
    id_wishlist INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_producto INT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- Tabla de Reseñas de Productos
CREATE TABLE review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_producto INT,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_review TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

CREATE TABLE administrador (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nombre_admin VARCHAR(100),
    correo_admin VARCHAR(100)
);

-- Tabla de Blog y Posts
CREATE TABLE blog_post (
    id_post INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT,
    titulo VARCHAR(255),
    contenido TEXT,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES administrador(id_admin) ON DELETE CASCADE
);

-- Tabla de Comentarios en Blog
CREATE TABLE comentarios_blog (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_post INT,
    id_cliente INT,
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_post) REFERENCES blog_post(id_post) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE
);

-- Tabla de Notificaciones y Comunicaciones
CREATE TABLE notificaciones (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    mensaje TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_estado_logico INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_estado_logico) REFERENCES estado_logico(id_estado_logico)
);

-- Tabla de Reportes de Ventas
CREATE TABLE reporte_venta (
    id_reporte INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT,
    fecha_generacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_ventas DECIMAL(10,2),
    total_productos_vendidos INT,
    FOREIGN KEY (id_admin) REFERENCES administrador(id_admin)
);

ALTER TABLE productos
  ADD COLUMN imagen_producto VARCHAR(255) NULL AFTER descripcion_producto;

ALTER TABLE productos
  ADD COLUMN codigo_barras VARCHAR(64) NULL AFTER descripcion_producto;

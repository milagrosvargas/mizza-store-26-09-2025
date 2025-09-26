-- 1. Insertar un detalle de documento (DNI)
INSERT INTO detalle_documento (id_tipo_documento, descripcion_documento)
VALUES (1, '44343341'); -- DNI

-- 2. Insertar un detalle de contacto (Correo electrónico)
INSERT INTO detalle_contacto (descripcion_contacto, id_tipo_contacto)
VALUES ('milovargasb@gmail.com', 1); -- Correo electrónico
-- 2.1. Insertar un detalle de contacto (Número de teléfono)
INSERT INTO detalle_contacto (descripcion_contacto, id_tipo_contacto) 
VALUES ('3704224812', 2);

-- 3. Insertar domicilio 
INSERT INTO domicilio (calle_direccion, numero_direccion, piso_direccion, info_extra_direccion, id_barrio)
VALUES ('Nueva Formosa', '5', '1', 'Portón negro', 1); 

-- 4. Insertar persona
INSERT INTO persona (
    nombre_persona,
    apellido_persona,
    fecha_nac_persona,
    id_genero,
    id_domicilio,
    id_detalle_documento,
    id_detalle_contacto
)
VALUES (
    'Milagros Belén',
    'Vargas',
    '2002-10-02',
    2, 1, 1, 1
);

-- 5. Insertar usuario (relacionado a persona y perfil Administrador)
INSERT INTO usuarios (
    nombre_usuario,
    password_usuario,
    estado_usuario,
    relacion_perfil,
    relacion_persona
)
VALUES (
    'administrador', 
    ('$2y$10$xP3MP6W8Mb1TdbeJ7bNiJev/BiXu3HQK8ava9NQSC1tOUakcK2p6a'), 
    1, -- Activo
    1, -- Administrador
    LAST_INSERT_ID()
);

USE TFG;

--- Tabla para información general de la comunidad, congiguración, etc
CREATE TABLE info (
    clave VARCHAR(100) PRIMARY KEY,
    valor VARCHAR(100) NOT NULL
);

--- Usuario de la aplicación
CREATE TABLE usuario(
    id INT PRIMARY KEY AUTO_INCREMENT,
    password_hash VARCHAR(60),
    nif VARCHAR(12),
    telefono VARCHAR(12),
    email VARCHAR(100),
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    direccion TEXT,
    INDEX (nif),
    INDEX(nombre),
    INDEX(apellidos)
);

-- Mensaje de un usuario al administrador
CREATE TABLE mensaje (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    leido DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE RESTRICT
);

CREATE TABLE bloque(
    id INT PRIMARY KEY AUTO_INCREMENT,
    portal VARCHAR(10) NOT NULL,
    presidente_id INT,
    FOREIGN KEY (presidente_id) REFERENCES usuario(id) ON DELETE RESTRICT
);

-- Vivienda de la comunidad
CREATE TABLE vivienda(
    id INT PRIMARY KEY AUTO_INCREMENT,
    bloque_id INT NOT NULL,
    planta INT NOT NULL,
    puerta VARCHAR(6) NOT NULL,
    importe_comunidad DECIMAL(10, 2) NOT NULL,
    numero_habitaciones INT UNSIGNED NOT NULL,
    superficie_interior DECIMAL(10, 2) NOT NULL,
    superficie_exterior DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (bloque_id) REFERENCES bloque(id) ON DELETE RESTRICT
);

-- Relaciona quien es propietario de qué vivienda. Si fecha_venta es NULL, es una propiedad actual
CREATE TABLE propiedad(
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    vivienda_id INT NOT NULL,
    fecha_compra DATE NOT NULL,
    fecha_venta DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE RESTRICT,
    FOREIGN KEY (vivienda_id) REFERENCES vivienda(id) ON DELETE RESTRICT
);

-- Periodo de alquiler de una vivienda
CREATE TABLE alquiler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    arrendador_id INT NOT NULL,
    arrendatario_id INT NOT NULL,
    vivienda_id INT NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE,
    FOREIGN KEY (arrendador_id) REFERENCES usuario(id) ON DELETE RESTRICT,
    FOREIGN KEY(arrendatario_id) REFERENCES usuario(id) ON DELETE RESTRICT,
    FOREIGN KEY (vivienda_id) REFERENCES vivienda(id) ON DELETE RESTRICT
);

-- Recibo de comunidad
CREATE TABLE recibo_comunidad(
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha_emision DATE NOT NULL,
    fecha_limite DATE NOT NULL,
    fecha_pagado DATE,
    fecha_inicio_periodo DATE NOT NULL,
    fecha_fin_periodo DATE NOT NULL,
    usuario_id INT NOT NULL,
    vivienda_id INT NOT NULL,
    importe DECIMAL(10, 2) NOT NULL,
    especial BOOLEAN DEFAULT FALSE,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE RESTRICT,
    FOREIGN KEY (vivienda_id) REFERENCES vivienda(id) ON DELETE RESTRICT
);

CREATE TABLE incidencia(
    id INT PRIMARY KEY AUTO_INCREMENT,
    vivienda_id INT,
    bloque_id INT,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_resolucion DATE,
    FOREIGN KEY (vivienda_id) REFERENCES vivienda(id) ON DELETE RESTRICT,
    FOREIGN KEY (bloque_id) REFERENCES bloque(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE RESTRICT
);

-- *** INSERCIÓN DE VALORES INICIALES ***
--- Usuario presidente
INSERT INTO
    usuario(
        password_hash,
        nif,
        telefono,
        email,
        nombre,
        apellidos,
        direccion
    )
values
    (
        "$2y$10$kT.Yi/O4lkqLXXmLEqSJi.a4mYGHfPNIADxi2UB1BPLbUkykE766q",
        "00000001A",
        "555000001",
        "administrador@tfg.es",
        "Clara",
        "Rodríguez Martínez",
        NULL
    );

-- Usuario propietario
INSERT INTO
    usuario(
        password_hash,
        nif,
        telefono,
        email,
        nombre,
        apellidos,
        direccion
    )
values
    (
        "$2y$10$ElgvI2dgMv73IwWSfQY2N.9dwxXIR4MFRjcxDjvved6UgDZxGnuuC",
        "00000002B",
        "555000002",
        "propietario@tfg.es",
        "Diego",
        "Sánchez López",
        NULL
    );

-- Rol de presidente
INSERT INTO
    info(clave, valor)
VALUES
    ('admin user', '1');

-- Bloques
INSERT INTO
    bloque(portal, presidente_id)
VALUES
    ('1', 1),
    ('2', 1),
    ('3', 1),
    ('4', 1);

-- Viviendas
INSERT INTO
    vivienda(
        bloque_id,
        planta,
        puerta,
        importe_comunidad,
        numero_habitaciones,
        superficie_interior,
        superficie_exterior
    )
VALUES
    (1, 1, "1", 50, 1, 45, 5),
    (1, 2, "1", 50, 1, 45, 5),
    (2, 1, "1", 50, 1, 45, 5),
    (2, 2, "1", 50, 1, 45, 5);

-- Propiedades
INSERT INTO
    propiedad(usuario_id, vivienda_id, fecha_compra)
VALUES
    (1, 1, '2003-01-01');

-- Propiedades
INSERT INTO
    propiedad(usuario_id, vivienda_id, fecha_compra)
VALUES
    (2, 2, '2003-01-01');
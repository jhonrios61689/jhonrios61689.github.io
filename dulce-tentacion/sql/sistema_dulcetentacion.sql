-- Esquema y Datos de sistema_dulcetentacion
CREATE DATABASE IF NOT EXISTS sistema_dulcetentacion;
USE sistema_dulcetentacion;

DROP TABLE IF EXISTS `administradores`;
CREATE TABLE `administradores` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `administradores` (`id_admin`, `nombre`, `apellido`, `email`, `cargo`, `contrasena`) VALUES
('1', 'Angela', 'Andrea', 'admin1@local.com', 'Gerente General', 'pass123'),
('2', 'Yessica', 'Gómez', 'admin2@local.com', 'Jefe de Operaciones', 'pass456');

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `token_recuperacion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `telefono`, `email`, `direccion`, `contrasena`, `token_recuperacion`) VALUES
('1', 'Brahiam', 'Bedoya', '3001112233', 'Brahiam.b@email.com', 'Avenida 7 #8-9', 'pass_c1', NULL),
('2', 'Emanuel', 'Morales', '3002223344', 'Emanuel.m@email.com', 'Carrera 4 #5-6', 'pass_c2', NULL),
('3', 'Juan', 'Cardona', '3003334455', 'Juan.c@email.com', 'Avenida 7 #8-9', 'pass_c3', NULL),
('4', 'Robinson', 'Monsalve', '3004445566', 'Robins.m@email.com', 'Calle 10 #11-12', 'pass_c4', NULL),
('5', 'Jhon', 'Gómez', '3005556677', 'Jhon.g@email.com', 'Transversal 13 #14-15', 'pass_c5', NULL),
('6', 'Oscar', 'Hurtado', '3006667788', 'Oscar.h@email.com', 'Diagonal 16 #17-18', 'pass_c6', NULL),
('7', 'Mariana', 'Monsalve', '3007778899', 'Mariana.m@email.com', 'Calle 19 #20-21', 'pass_c7', NULL),
('8', 'Felipe', 'Henao', '3008889900', 'Felipe.h@email.com', 'Carrera 22 #23-24', 'pass_c8', NULL),
('9', 'Yhojan', 'Salazar', '3009990011', 'Yhojan.s@email.com', 'Avenida 25 #26-27', 'pass_c9', NULL),
('10', 'Daniel', 'Valencia', '3000001122', 'Daniel.v@email.com', 'Calle 28 #29-30', 'pass_c10', NULL);

DROP TABLE IF EXISTS `detalles_venta`;
CREATE TABLE `detalles_venta` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_venta` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `id_venta` (`id_venta`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE,
  CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `detalles_venta` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
('1', '1', '1', '1', '12500.00'),
('2', '1', '2', '1', '8000.00'),
('3', '2', '13', '1', '5500.00');

DROP TABLE IF EXISTS `empleados`;
CREATE TABLE `empleados` (
  `id_empleado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`id_empleado`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `empleados` (`id_empleado`, `nombre`, `apellido`, `email`, `contrasena`) VALUES
('1', 'Juan', 'Villamil', 'empleado1@local.com', 'emp_pass_1'),
('2', 'Sofía', 'Restrepo', 'empleado2@local.com', 'emp_pass_2');

DROP TABLE IF EXISTS `movimientos_stock`;
CREATE TABLE `movimientos_stock` (
  `id_movimiento` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `delta` int NOT NULL,
  `stock_antes` int NOT NULL,
  `stock_despues` int NOT NULL,
  `motivo` varchar(300) NOT NULL,
  `responsable` varchar(120) DEFAULT 'Juan Villamil',
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_movimiento`),
  KEY `id_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `productos` (`id_producto`, `nombre`, `precio`, `stock`) VALUES
('1', 'Cheesecake en vaso', '12500.00', '50'),
('2', 'Cappuccino', '8000.00', '100'),
('3', 'Carlota de frutos rojos', '14000.00', '30'),
('4', 'Tartaleta de fresa', '11500.00', '25'),
('5', 'Mousse de fresa', '9500.00', '30'),
('6', 'Cupcake Red Velvet', '6500.00', '40'),
('7', 'Parfait de fresa', '13000.00', '20'),
('8', 'Tiramisú en vaso', '14500.00', '15'),
('9', 'Panna cotta de fresa', '12000.00', '20'),
('10', 'Cupcake de frambuesa', '6500.00', '40'),
('11', 'Tres leches con fresa', '10500.00', '25'),
('12', 'Cupcake de merengue', '6000.00', '45'),
('13', 'Cupcake de vainilla', '5500.00', '49'),
('14', 'Latte de vainilla', '8500.00', '100'),
('15', 'Mocaccino artesanal', '9500.00', '100'),
('16', 'Latte caramelo', '9000.00', '100'),
('17', 'Café americano', '5000.00', '100'),
('18', 'Café bombón', '7500.00', '100'),
('19', 'Cupcake de fresa', '6500.00', '40'),
('20', 'Postre en vaso', '11000.00', '30');

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_empleado` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id_venta`),
  KEY `id_empleado` (`id_empleado`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `ventas` (`id_venta`, `fecha`, `id_empleado`, `id_cliente`, `total`) VALUES
('1', '2026-10-29 17:29:26', '1', '2', '20500.00'),
('2', '2026-10-29 19:30:24', '1', '1', '5500.00');


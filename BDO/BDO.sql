/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `trabajo_daw` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci */;
USE `trabajo_daw`;

CREATE TABLE IF NOT EXISTS `accesos` (
  `id_accesos` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_hora_acceso` date DEFAULT NULL,
  `fecha_hora_salida` date DEFAULT NULL,
  PRIMARY KEY (`id_accesos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;


CREATE TABLE IF NOT EXISTS `albaranes` (
  `cod_albaran` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `generado_de_pedido` int(11) DEFAULT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `estado` enum('No facturado','Facturado') DEFAULT 'No facturado',
  PRIMARY KEY (`cod_albaran`),
  KEY `FK_albaranes_clientes` (`cod_cliente`),
  KEY `FK_albaranes_pedidos` (`generado_de_pedido`),
  CONSTRAINT `FK_albaranes_clientes` FOREIGN KEY (`cod_cliente`) REFERENCES `clientes` (`cod_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_albaranes_pedidos` FOREIGN KEY (`generado_de_pedido`) REFERENCES `pedidos` (`cod_pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `albaranes` (`cod_albaran`, `cod_cliente`, `fecha`, `generado_de_pedido`, `concepto`, `estado`) VALUES
	(63, 59, '2023-05-23', 196, 'Primer albarán', 'Facturado'),
	(64, 59, '2023-05-23', 196, 'Segundo albarán', 'Facturado');

CREATE TABLE IF NOT EXISTS `articulos` (
  `cod_articulo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `descuento` int(11) NOT NULL,
  `iva` int(11) NOT NULL,
  `disponibilidad` enum('Disponible','No disponible') DEFAULT 'Disponible',
  PRIMARY KEY (`cod_articulo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `articulos` (`cod_articulo`, `nombre`, `descripcion`, `precio`, `descuento`, `iva`, `disponibilidad`) VALUES
	(31, 'Iphone XS 64gb', 'Teléfono movil de la marca iPhone con capacidad de', 450.00, 0, 21, 'Disponible'),
	(32, 'Iphone XR 64gb', 'Teléfono movil de la marca iPhone, modelo XR con c', 650.00, 0, 21, 'Disponible'),
	(33, 'Iphone  14 128gb', 'Teléfono movil de la marca iPhone, modelo 14 con c', 1050.00, 0, 21, 'Disponible');

CREATE TABLE IF NOT EXISTS `clientes` (
  `cod_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `cif_dni` varchar(9) DEFAULT NULL,
  `razon_social` varchar(50) DEFAULT NULL,
  `domicilio_social` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `contraseña` varchar(50) DEFAULT NULL,
  `disponibilidad` enum('Disponible','No disponible') NOT NULL DEFAULT 'Disponible',
  PRIMARY KEY (`cod_cliente`),
  UNIQUE KEY `cif_dni` (`cif_dni`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `clientes` (`cod_cliente`, `cif_dni`, `razon_social`, `domicilio_social`, `ciudad`, `email`, `telefono`, `nick`, `contraseña`, `disponibilidad`) VALUES
	(59, '37325248C', 'Razón social ejemplo', 'Domicilio social ejemplo nº2', 'Elche', 'dgarciacasam@gmail.com', '+3463363363', 'Daniel García Casamayor', 'dani123', 'Disponible'),
	(60, '78779609D', 'Razón social ej', 'qwegqw', 'Elche', 'densegorilla938@gmail.com', '+3412312312', 'Daniel García Casam', 'dani123', 'Disponible');

CREATE TABLE IF NOT EXISTS `facturas` (
  `cod_factura` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `descuento_factura` int(11) DEFAULT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cod_factura`),
  KEY `FK_facturas_clientes` (`cod_cliente`),
  CONSTRAINT `FK_facturas_clientes` FOREIGN KEY (`cod_cliente`) REFERENCES `clientes` (`cod_cliente`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `facturas` (`cod_factura`, `cod_cliente`, `fecha`, `descuento_factura`, `concepto`) VALUES
	(93, 59, '2023-05-23', 0, ''),
	(94, 59, '2023-05-23', 0, '');

CREATE TABLE IF NOT EXISTS `lineas_albaran` (
  `num_linea_albaran` int(11) NOT NULL AUTO_INCREMENT,
  `precio` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descuento` int(11) DEFAULT NULL,
  `iva` int(11) DEFAULT NULL,
  `cod_albaran` int(11) NOT NULL,
  `cod_articulo` int(11) DEFAULT NULL,
  `cod_usu_gestion` int(11) DEFAULT NULL,
  `num_linea_pedido` int(11) NOT NULL,
  `cod_pedido` int(11) NOT NULL,
  PRIMARY KEY (`num_linea_albaran`,`cod_albaran`),
  KEY `FK_lineas_albaran_articulos` (`cod_articulo`),
  KEY `FK_lineas_albaran_albaranes` (`cod_albaran`),
  KEY `FK_lineas_albaran_usuarios_gestion` (`cod_usu_gestion`),
  KEY `FK_lineas_albaran_lineas_pedidos` (`num_linea_pedido`,`cod_pedido`),
  CONSTRAINT `FK_lineas_albaran_albaranes` FOREIGN KEY (`cod_albaran`) REFERENCES `albaranes` (`cod_albaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_lineas_albaran_articulos` FOREIGN KEY (`cod_articulo`) REFERENCES `articulos` (`cod_articulo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_lineas_albaran_lineas_pedidos` FOREIGN KEY (`num_linea_pedido`, `cod_pedido`) REFERENCES `lineas_pedidos` (`num_linea_pedido`, `cod_pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_lineas_albaran_usuarios_gestion` FOREIGN KEY (`cod_usu_gestion`) REFERENCES `usuarios_gestion` (`cod_usuario_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `lineas_albaran` (`num_linea_albaran`, `precio`, `cantidad`, `descuento`, `iva`, `cod_albaran`, `cod_articulo`, `cod_usu_gestion`, `num_linea_pedido`, `cod_pedido`) VALUES
	(127, 450, 20, 0, 21, 63, 31, 1, 222, 196),
	(128, 1050, 15, 0, 21, 63, 33, 1, 223, 196),
	(129, 450, 5, 0, 21, 64, 31, 1, 222, 196),
	(130, 1050, 5, 0, 21, 64, 33, 1, 223, 196);

CREATE TABLE IF NOT EXISTS `lineas_facturas` (
  `num_linea_factura` int(11) NOT NULL AUTO_INCREMENT,
  `precio` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descuento` int(11) DEFAULT NULL,
  `iva` int(11) DEFAULT NULL,
  `cod_factura` int(11) NOT NULL,
  `cod_articulo` int(11) NOT NULL,
  `cod_usu_gestion` int(11) DEFAULT NULL,
  `num_linea_albaran` int(11) NOT NULL,
  `cod_albaran` int(11) NOT NULL,
  PRIMARY KEY (`num_linea_factura`,`cod_factura`),
  UNIQUE KEY `cod_albaran_num_linea_albaran` (`cod_albaran`,`num_linea_albaran`),
  KEY `FK_lineas_facturas_articulos` (`cod_articulo`),
  KEY `FK_lineas_facturas_facturas` (`cod_factura`),
  KEY `FK_lineas_facturas_usuarios_gestion` (`cod_usu_gestion`),
  KEY `FK_lineas_facturas_lineas_albaran` (`num_linea_albaran`,`cod_albaran`),
  CONSTRAINT `FK_lineas_facturas_articulos` FOREIGN KEY (`cod_articulo`) REFERENCES `articulos` (`cod_articulo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_lineas_facturas_facturas` FOREIGN KEY (`cod_factura`) REFERENCES `facturas` (`cod_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_lineas_facturas_lineas_albaran` FOREIGN KEY (`num_linea_albaran`, `cod_albaran`) REFERENCES `lineas_albaran` (`num_linea_albaran`, `cod_albaran`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_lineas_facturas_usuarios_gestion` FOREIGN KEY (`cod_usu_gestion`) REFERENCES `usuarios_gestion` (`cod_usuario_gestion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `lineas_facturas` (`num_linea_factura`, `precio`, `cantidad`, `descuento`, `iva`, `cod_factura`, `cod_articulo`, `cod_usu_gestion`, `num_linea_albaran`, `cod_albaran`) VALUES
	(133, 450, 5, 0, 21, 93, 31, 1, 129, 64),
	(134, 1050, 5, 0, 21, 93, 33, 1, 130, 64),
	(135, 450, 20, 0, 21, 94, 31, 1, 127, 63),
	(136, 1050, 15, 0, 21, 94, 33, 1, 128, 63);

CREATE TABLE IF NOT EXISTS `lineas_pedidos` (
  `num_linea_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `cod_pedido` int(11) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidadEnAlbaran` int(11) DEFAULT 0,
  `cod_articulo` int(11) NOT NULL,
  `cod_usu_gestion` int(11) DEFAULT 1,
  `estado` enum('Sin Albaran','Parcialmente en Albaran','Totalmente en Albaran') DEFAULT 'Sin Albaran',
  PRIMARY KEY (`num_linea_pedido`,`cod_pedido`),
  KEY `FK_lineas_pedidos_articulos` (`cod_articulo`),
  KEY `FK_lineas_pedidos_pedidos` (`cod_pedido`),
  KEY `FK_lineas_pedidos_usuarios_gestion` (`cod_usu_gestion`),
  CONSTRAINT `FK_lineas_pedidos_articulos` FOREIGN KEY (`cod_articulo`) REFERENCES `articulos` (`cod_articulo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_lineas_pedidos_pedidos` FOREIGN KEY (`cod_pedido`) REFERENCES `pedidos` (`cod_pedido`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_lineas_pedidos_usuarios_gestion` FOREIGN KEY (`cod_usu_gestion`) REFERENCES `usuarios_gestion` (`cod_usuario_gestion`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `lineas_pedidos` (`num_linea_pedido`, `cod_pedido`, `precio`, `cantidad`, `cantidadEnAlbaran`, `cod_articulo`, `cod_usu_gestion`, `estado`) VALUES
	(222, 196, 450.00, 25, 25, 31, 1, 'Totalmente en Albaran'),
	(223, 196, 1050.00, 20, 20, 33, 1, 'Totalmente en Albaran'),
	(225, 197, 650.00, 20, 0, 32, 1, 'Sin Albaran'),
	(226, 197, 1050.00, 150, 0, 33, 1, 'Sin Albaran');

CREATE TABLE IF NOT EXISTS `pedidos` (
  `cod_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Sin Albaran','Parcialmente en Albaran','Totalmente en Albaran') DEFAULT 'Sin Albaran',
  PRIMARY KEY (`cod_pedido`),
  KEY `FK_pedidos_clientes` (`cod_cliente`),
  CONSTRAINT `FK_pedidos_clientes` FOREIGN KEY (`cod_cliente`) REFERENCES `clientes` (`cod_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `pedidos` (`cod_pedido`, `cod_cliente`, `fecha`, `estado`) VALUES
	(196, 59, '2023-05-03', 'Totalmente en Albaran'),
	(197, 60, '2023-05-23', 'Sin Albaran');

CREATE TABLE IF NOT EXISTS `solicitudes` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `cif_dni` varchar(9) NOT NULL,
  `razon_social` varchar(50) DEFAULT NULL,
  `domicilio_social` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `contraseña` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;


CREATE TABLE IF NOT EXISTS `usuarios_gestion` (
  `cod_usuario_gestion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `contraseña` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cod_usuario_gestion`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `usuarios_gestion` (`cod_usuario_gestion`, `nombre`, `nick`, `contraseña`) VALUES
	(1, 'Daniel', 'admin', 'admin');

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER after_delete_lineas_albaran
AFTER DELETE ON lineas_albaran
FOR EACH ROW
BEGIN
  -- Actualizar cantidadEnAlbaran en lineas_pedido
  UPDATE lineas_pedidos
  SET cantidadEnAlbaran = COALESCE(
    (
      SELECT SUM(cantidad)
      FROM lineas_albaran
      WHERE lineas_albaran.num_linea_pedido = OLD.num_linea_pedido
    ),
    0  -- Valor predeterminado si la suma es NULL
  )
  WHERE lineas_pedidos.num_linea_pedido = OLD.num_linea_pedido;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_delete_lineas_facturas` AFTER DELETE ON `lineas_facturas` FOR EACH ROW BEGIN
  -- Actualizar el estado en líneas_albaran al crear factura
  UPDATE albaranes
  SET estado = "No facturado"
  WHERE albaranes.cod_albaran = OLD.cod_albaran;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER after_insert_lineas_albaran
AFTER INSERT ON lineas_albaran
FOR EACH ROW
BEGIN
  -- Actualizar cantidadEnAlbaran en lineas_pedido
  UPDATE lineas_pedidos
  SET cantidadEnAlbaran = (
    SELECT SUM(cantidad)
    FROM lineas_albaran
    WHERE lineas_albaran.num_linea_pedido = NEW.num_linea_pedido
  )
  WHERE lineas_pedidos.num_linea_pedido = NEW.num_linea_pedido;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_insert_lineas_facturas` AFTER INSERT ON `lineas_facturas` FOR EACH ROW BEGIN
  -- Actualizar el estado en líneas_albaran al crear factura
  UPDATE albaranes
  SET estado = "Facturado"
  WHERE albaranes.cod_albaran = NEW.cod_albaran;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER after_update_lineas_albaran
AFTER UPDATE ON lineas_albaran
FOR EACH ROW
BEGIN
  -- Actualizar cantidadEnAlbaran en lineas_pedido
  UPDATE lineas_pedidos
  SET cantidadEnAlbaran = (
    SELECT SUM(cantidad)
    FROM lineas_albaran
    WHERE lineas_albaran.num_linea_pedido = NEW.num_linea_pedido
  )
  WHERE lineas_pedidos.num_linea_pedido = NEW.num_linea_pedido;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_update_lineas_pedido` AFTER UPDATE ON `lineas_pedidos` FOR EACH ROW BEGIN
    DECLARE total_lineas INT;
    DECLARE contador_total INT DEFAULT 0;
    DECLARE contador_sin_albaran INT DEFAULT 0;

    -- Obtener el total de líneas de pedido para el pedido actual
    SELECT COUNT(*) INTO total_lineas
    FROM lineas_pedidos
    WHERE cod_pedido = NEW.cod_pedido;

    -- Contar las líneas de albarán con estado "Totalmente en Albarán"
    SELECT COUNT(*) INTO contador_total
    FROM lineas_pedidos
    WHERE cod_pedido = NEW.cod_pedido AND estado = 'Totalmente en Albaran';

    -- Contar las líneas de albarán con estado "Sin Albarán"
    SELECT COUNT(*) INTO contador_sin_albaran
    FROM lineas_pedidos
    WHERE cod_pedido = NEW.cod_pedido AND estado = 'Sin Albaran';

    -- Actualizar el estado del pedido según las condiciones
    IF contador_total = total_lineas THEN
        UPDATE pedidos
        SET estado = 'Totalmente en Albaran'
        WHERE cod_pedido = NEW.cod_pedido;
    ELSEIF contador_sin_albaran = total_lineas THEN
        UPDATE pedidos
        SET estado = 'Sin Albaran'
        WHERE cod_pedido = NEW.cod_pedido;
    ELSE
        UPDATE pedidos
        SET estado = 'Parcialmente en Albaran'
        WHERE cod_pedido = NEW.cod_pedido;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `before_update_lineas_pedidos` BEFORE UPDATE ON `lineas_pedidos` FOR EACH ROW BEGIN
  -- Verificar si el campo cantidadEnAlbaran o cantidad fue modificado
  IF NEW.cantidadEnAlbaran != OLD.cantidadEnAlbaran OR NEW.cantidad != OLD.cantidad THEN
    -- Determinar el valor del campo estado
    IF NEW.cantidadEnAlbaran = NEW.cantidad THEN
      SET NEW.estado = 'Totalmente en Albaran';
    ELSEIF NEW.cantidadEnAlbaran = 0 THEN
      SET NEW.estado = 'Sin Albaran';
    ELSE
      SET NEW.estado = 'Parcialmente en Albaran';
    END IF;
  END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER eliminar_factura AFTER DELETE ON lineas_facturas
FOR EACH ROW
BEGIN
    DECLARE total_lineas INT;
    
    SELECT COUNT(*) INTO total_lineas
    FROM lineas_facturas
    WHERE cod_factura = OLD.cod_factura;
    
    IF total_lineas = 0 THEN
        DELETE FROM facturas
        WHERE cod_factura = OLD.cod_factura;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER eliminar_pedido AFTER DELETE ON lineas_pedidos
FOR EACH ROW
BEGIN
    DECLARE total_lineas INT;
    
    SELECT COUNT(*) INTO total_lineas
    FROM lineas_pedidos
    WHERE cod_pedido = OLD.cod_pedido;
    
    IF total_lineas = 0 THEN
        DELETE FROM pedidos
        WHERE cod_pedido = OLD.cod_pedido;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

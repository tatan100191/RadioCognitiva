-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-07-2016 a las 06:57:54
-- Versión del servidor: 5.6.11
-- Versión de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `radio_cognitiva`
--
CREATE DATABASE IF NOT EXISTS `radio_cognitiva` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `radio_cognitiva`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlace`
--

CREATE TABLE IF NOT EXISTS `enlace` (
  `tipoEnlace` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `cordenadaX` int(11) NOT NULL,
  `cordenadaY` int(11) NOT NULL,
  `tiempo` int(11) NOT NULL,
  `canal` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `distanciaAntena` int(11) NOT NULL,
  `potencia` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pargenerales`
--

CREATE TABLE IF NOT EXISTS `pargenerales` (
  `codparametro` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `descparametro` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `valor` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`codparametro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pargenerales`
--

INSERT INTO `pargenerales` (`codparametro`, `descparametro`, `valor`) VALUES
('anchobanda', 'Es el ancho de banda', '10'),
('beta', 'Umbral de interferencia', '6'),
('numcanales', 'Numero de canales', '8'),
('tamanollam', 'Tamaño llamada', '8');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

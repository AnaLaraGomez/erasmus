-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 03-12-2023 a las 11:04:24
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `erasmus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellidos` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `baremacion`
--

CREATE TABLE `baremacion` (
  `convocatoria_id` int(11) NOT NULL,
  `candidato_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `nota` int(2) NOT NULL,
  `url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `candidato`
--

CREATE TABLE `candidato` (
  `id` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `curso` varchar(5) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `domicilio` varchar(100) NOT NULL,
  `tutor_nombre` varchar(25) DEFAULT NULL,
  `tutor_apellidos` varchar(50) DEFAULT NULL,
  `tutor_dni` varchar(9) DEFAULT NULL,
  `tutor_domicilio` varchar(100) DEFAULT NULL,
  `tutor_telefono` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convocatoria`
--

CREATE TABLE `convocatoria` (
  `id` int(11) NOT NULL,
  `movilidades` int(3) NOT NULL,
  `larga_duracion` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_inicio_solicitudes` datetime NOT NULL,
  `fecha_fin_solicitudes` datetime(1) NOT NULL,
  `fecha_inicio_pruebas` datetime NOT NULL,
  `fecha_fin_pruebas` datetime NOT NULL,
  `fecha_lista_provisional` datetime NOT NULL,
  `fecha_lista_definitiva` datetime NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `descripcion` text NOT NULL DEFAULT '',
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `convocatoria`
--

INSERT INTO `convocatoria` (`id`, `movilidades`, `larga_duracion`, `fecha_inicio_solicitudes`, `fecha_fin_solicitudes`, `fecha_inicio_pruebas`, `fecha_fin_pruebas`, `fecha_lista_provisional`, `fecha_lista_definitiva`, `proyecto_id`, `descripcion`, `nombre`) VALUES
(16, 20, 0, '2023-12-02 21:24:00', '2023-12-03 21:24:00.0', '2023-12-09 21:24:00', '2023-12-10 21:24:00', '2023-12-16 21:24:00', '2023-12-17 21:25:00', 2, '', 'Convocatoria 2023/24 - Fondos Europeos para la internacionalización');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convocatoria_baremo`
--

CREATE TABLE `convocatoria_baremo` (
  `convocatoria_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `puntuacion_max` int(2) NOT NULL,
  `requisito` tinyint(1) DEFAULT NULL,
  `min_requisito` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `convocatoria_baremo`
--

INSERT INTO `convocatoria_baremo` (`convocatoria_id`, `item_id`, `puntuacion_max`, `requisito`, `min_requisito`) VALUES
(16, 1, 2, 0, 1),
(16, 2, 2, 1, 1),
(16, 3, 2, 1, 1),
(16, 4, 2, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convocatoria_baremo_idioma`
--

CREATE TABLE `convocatoria_baremo_idioma` (
  `convocatoria_id` int(11) NOT NULL,
  `idioma_id` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `convocatoria_baremo_idioma`
--

INSERT INTO `convocatoria_baremo_idioma` (`convocatoria_id`, `idioma_id`, `puntuacion`) VALUES
(16, 1, 1),
(16, 2, 1),
(16, 3, 2),
(16, 4, 3),
(16, 5, 4),
(16, 6, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinatario`
--

CREATE TABLE `destinatario` (
  `id` int(11) NOT NULL,
  `codigo_grupo` varchar(25) NOT NULL,
  `nombre` varchar(1200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `destinatario`
--

INSERT INTO `destinatario` (`id`, `codigo_grupo`, `nombre`) VALUES
(9, '1A', 'Gestión Administrativa'),
(10, '2A', 'Gestión Administrativa'),
(11, '1A', 'Instalaciones Eléctricas y Automáticas'),
(12, '2A', 'Instalaciones Eléctricas y Automáticas'),
(13, '1A', 'Instalaciones de Telecomunicaciones'),
(14, '2A', 'Instalaciones de Telecomunicaciones'),
(15, '1A', 'Mantenimiento Electromecánico'),
(16, '2A', 'Mantenimiento Electromecánico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinatario_convocatoria`
--

CREATE TABLE `destinatario_convocatoria` (
  `convocatoria_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `destinatario_convocatoria`
--

INSERT INTO `destinatario_convocatoria` (`convocatoria_id`, `destinatario_id`) VALUES
(16, 10),
(16, 12),
(16, 14),
(16, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id` int(11) NOT NULL,
  `nivel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `idiomas`
--

INSERT INTO `idiomas` (`id`, `nivel`) VALUES
(1, 'A1'),
(2, 'A2'),
(3, 'B1'),
(4, 'B2'),
(5, 'C1'),
(6, 'C2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_baremable`
--

CREATE TABLE `item_baremable` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sube_alumno` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `item_baremable`
--

INSERT INTO `item_baremable` (`id`, `nombre`, `sube_alumno`) VALUES
(1, 'Certificado nota media', 1),
(2, 'Informe de Idoneidad', 0),
(3, 'Entrevista', 0),
(4, 'Certificado Idiomas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE `proyecto` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto`
--

INSERT INTO `proyecto` (`id`, `codigo`, `nombre`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 'ASD-2345', 'Mujeres Ingenieras por el mundo', '2023-12-02 12:45:27', '2024-12-31 12:45:27'),
(2, 'ASD-2346', 'Hombres por el Mundo', '2023-12-02 20:03:30', '2024-12-24 20:03:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `password` varchar(8) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `dni`, `password`, `admin`) VALUES
(1, '77368549E', '123', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `candidato`
--
ALTER TABLE `candidato`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `convocatoria`
--
ALTER TABLE `convocatoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `destinatario`
--
ALTER TABLE `destinatario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `item_baremable`
--
ALTER TABLE `item_baremable`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `candidato`
--
ALTER TABLE `candidato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `convocatoria`
--
ALTER TABLE `convocatoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `destinatario`
--
ALTER TABLE `destinatario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `item_baremable`
--
ALTER TABLE `item_baremable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

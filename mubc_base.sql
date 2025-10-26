-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-10-2025 a las 15:32:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mubc_base`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `email`, `fecha_registro`) VALUES
(1, 'Obed', '$2y$10$UwiD7mE9AB/KVKPlOl9RK.vsqgoHUPv4Smp/s1eVjWtB8Q4roeLku', 'chavezobed35@gmail.com', '2025-10-25 01:35:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id_registro` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `mesa` varchar(255) NOT NULL,
  `rf_por` varchar(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id_registro`, `nombre`, `apellido`, `cedula`, `telefono`, `lugar`, `mesa`, `rf_por`, `fecha_registro`) VALUES
(1, 'Obed Abner', 'Chavez Castillo', '402-1284322-7', '829-898-0712', 'Santiago de los caballeros, la reforma', 'mesa #15', 'Malaquias Belen', '2025-10-23 15:21:21'),
(2, 'Edinson', 'Jimenez Chavez', '402-1284322-8', '809-563-2680', 'Santiago de los caballeros, infotep', 'mesa #1', 'Obed Chavez', '2025-10-23 22:16:39'),
(3, 'Julio', 'Dominguez', '154-4515415-5', '154-545-1415', 'Santiago de los caballeros, infotep', 'mesa #1', 'Obed Chavez', '2025-10-26 13:03:53'),
(4, 'Edinson', 'Rodriguez ', '402-1284322-6', '829-898-0717', 'Santiago de los caballeros, infotep', 'mesa #1', 'Malaquias Belen', '2025-10-26 13:04:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id_registro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2020 a las 10:28:20
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `poblacion` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_nac` date NOT NULL,
  `foto` varchar(100) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id`, `nombre`, `apellidos`, `poblacion`, `provincia`, `email`, `fecha_nac`, `foto`, `id_usuario`) VALUES
(1, 'Pablo', 'Huertas', 'Alcazar', 'Ciudad Real', 'holajajaj@gmail.com', '1999-01-01', '', 15),
(2, 'Mel', 'Rincon', 'Alcazar', 'Ciudad Real', 'pahucastle@gmail.com', '1999-01-23', '', 15),
(3, 'Maribel', 'Rincon', 'Alcazar', 'Ciudad Real', 'maribel@gmail.com', '1961-01-23', '', 15),
(4, 'Padre', 'Rincon', 'Alcazar', 'Ciudad Real', 'padre@gmail.com', '1965-01-23', '', 15),
(5, 'hola', 'huertas castillo', 'Quero', 'provincia', 'hola@hola.com', '0200-02-02', '', 15),
(6, 'hola', 'huertas castillo', 'Quero', 'provincia', 'hola@hola.com', '0200-02-02', '', 15),
(7, 'hola', 'huertas castillo', 'Quero', 'provincia', 'hola@hola.com', '0200-02-02', '', 15),
(8, 'Pablo', 'Huertas', 'Alcázar de San Juan', 'No selection', 'pahucastle@gmail.com', '1994-03-23', '', 15),
(9, 'Pablo', 'Huertas', 'Alcázar de San Juan', 'No selection', 'pahucastle@gmail.com', '1995-01-23', '', 15),
(56, 'Marta', 'huertas', 'Toledo', 'Toledo', 'martahuertasgmail.com', '1997-02-26', '', 15),
(66, 'Pablo', 'Huertas', 'Alcázar de San Juan', 'Ciudad Real', 'pahucastle@gmail.com', '1995-02-23', '74bc27fa78af4e2fa3048cdd4e66a858.jpeg', 19),
(77, 'Melody', 'Rincón', 'Alcázar de San Juan', 'Ciudad Real', 'melodyrincon@hotmail.com', '1995-04-26', '7aacc36ee85cd67fe12b8d3ceeca0e07.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos`
--

CREATE TABLE `telefonos` (
  `id` int(11) NOT NULL,
  `numero` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `id_contacto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `telefonos`
--

INSERT INTO `telefonos` (`id`, `numero`, `tipo`, `id_contacto`) VALUES
(2, '654654654', 'Trabajo', 9),
(3, '654432231', 'Personal', 9),
(4, '98778653', 'Casa', 9),
(17, '654543432', 'Movil', 56),
(18, '987123234', 'casa', 56),
(80, '608465499', 'Movil', 77);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uid` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `fecha_registro`, `uid`) VALUES
(1, 'pahucastle@gmail.com', '$2y$10$ZFU69RqyUT8ZbarTrXzSvezNhNiF/ele92.GwEEU9Fd5gATDyhnZC', '2020-10-19 08:21:46', '1602673410'),
(6, 'administracion@artipistilos.com', '$2y$10$YQ.Go4tXTe6V6/R7B7fYU.45dJhiBbopClHqc.4RJbRdbgSp5qrze', '2020-10-14 11:27:24', '4c0336b3393abed906d372e47f6ed784b87bfdfb'),
(9, 'ventas@artipistilos.com', '$2y$10$yChCAQrnPp1qRQjTmOpPUOvyJjl2kFoxzNVt5ut4Am93PWNL94Ypu', '2020-10-14 11:39:59', '52f2ae711cb0d48761f8e28b4aadf84d28cc0f5f'),
(13, 'hucastle@gmail.com', '$2y$10$CeLiq8GV0vEW4RyZ8INHbOzqtJy3ATyDZY.BCev5xjqVGcIo5aUJy', '2020-10-14 12:43:27', '15545984e05d66b558996e3f25faceda48993ced'),
(14, 'uhcastle@gmail.com', '$2y$10$eiLdbPVZFAgMvbZ/ar6Ive0aVYevjA9FYCPsZudrHfgL0uWiCFz82', '2020-10-14 12:47:17', '80c64a86e565fa47e212a72ebdee64f8d59b1054'),
(15, 'pale@gmail.com', '$2y$10$0Fai6SDfjquO7HSrDXJQo./ZcELUA0FFiKl.1QGHLJh3jco3fXQbS', '2020-10-14 14:03:39', '26397786134ae934c9384b0597a36b6cf2348310'),
(16, 'amelia@gmail.com', '$2y$10$FabRf6y4dZWU7/H04RE2xObFkjnHePkvWxfm.3RzTM.AVkZLioBRy', '2020-10-20 08:22:43', '3ff11bc7eb1e5aa2ad57c030f59a6fbeb3e57166'),
(17, 'aaaa@gmail.com', '$2y$10$j25lVzmfAk4SPQRszNC5se0x2zaqoi6AejJu5IiQ/Z2I0YfzUkKpi', '2020-10-20 08:30:38', '82cd88ee1a6ba181a704ade5b74305a852c8dba1'),
(19, 'sdasdasdasdasdasd', '$2y$10$7rzlPA4qpeY/hdWhA19sCOK30FiAXJ4eShcv3ccG4B98x8665QAti', '2020-10-20 08:44:47', 'af81be62f5f6693721b3b6a24050af45998145fc');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contactos_ibfk_1` (`id_usuario`);

--
-- Indices de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `telefonos_ibfk_1` (`id_contacto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `contactos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `telefonos`
--
ALTER TABLE `telefonos`
  ADD CONSTRAINT `telefonos_ibfk_1` FOREIGN KEY (`id_contacto`) REFERENCES `contactos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

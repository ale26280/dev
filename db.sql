-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-08-2014 a las 17:50:05
-- Versión del servidor: 5.5.36
-- Versión de PHP: 5.4.25

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `kstudio0_oxxyt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `video` varchar(255) NOT NULL,
  `metadatos` text NOT NULL,
  `analytics` text NOT NULL,
  `telefono` varchar(32) NOT NULL,
  `contacto` varchar(168) NOT NULL,
  `contacto_copia` varchar(168) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `url_facebook` varchar(255) NOT NULL,
  `url_twitter` varchar(255) NOT NULL,
  `url_googleplus` varchar(255) NOT NULL,
  `url_linkedin` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `titulo`, `descripcion`, `video`, `metadatos`, `analytics`, `telefono`, `contacto`, `contacto_copia`, `latitud`, `longitud`, `url_facebook`, `url_twitter`, `url_googleplus`, `url_linkedin`, `created_at`, `updated_at`) VALUES
(1, 'GDI :: Generación de Ideas', 'GDI Generacion de ideas', 'yeC3AisTs2Y', '', '', '(+54) 11-5199-8315', 'nfo@generaciondeideas.com.ar', '', -34.59733220, -58.37284460, 'http://www.facebook.com/pages/GDI-Generaci%C3%B3n-de-Ideas/134468673244511?ref=hl', 'https://twitter.com/GDIcontenidos', '', 'http://www.linkedin.com/company/generacion-de-ideas', '2014-07-11 00:00:00', '2014-07-30 15:55:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE IF NOT EXISTS `mensaje` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emisor_id` int(9) unsigned NOT NULL,
  `receptor_id` int(9) unsigned NOT NULL,
  `mobile_id` int(9) unsigned NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lon` decimal(11,8) NOT NULL,
  `texto` text NOT NULL,
  `fecha` datetime NOT NULL,
  `visto` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `newsletter`
--

INSERT INTO `newsletter` (`id`, `nombre`, `correo`, `created_at`, `updated_at`) VALUES
(2, 'Eduardito', 'eduugr@gmail.com', '2014-08-07 20:59:48', '2014-08-07 20:59:48'),
(3, 'asdasd', 'eduugr@gmail.com', '2014-08-07 21:08:11', '2014-08-07 21:08:11'),
(4, 'TEST', 'test@test.com', '2014-08-11 17:32:00', '2014-08-11 17:32:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE IF NOT EXISTS `perfiles` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id`, `admin`, `titulo`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 1, 'Administrador', 'Tiene acceso total al sitio y todos sus sub-sitios, así también al área de traducción de contenidos.', '2014-01-21 00:00:00', '2014-01-21 00:00:00'),
(2, 0, 'Standard', 'Usuarios standard del sistema', '2014-05-01 00:00:00', '2014-05-01 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `tag`, `created_at`, `updated_at`) VALUES
(2, 'Art', '2014-07-18 12:30:12', '2014-07-18 12:30:12'),
(3, 'Photography', '2014-07-18 12:30:24', '2014-07-18 12:30:24'),
(4, 'Video', '2014-07-18 12:30:32', '2014-07-18 12:30:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temporales`
--

CREATE TABLE IF NOT EXISTS `temporales` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `modulo` varchar(64) NOT NULL,
  `tipo` varchar(16) NOT NULL,
  `fuente` varchar(255) NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `data_a` varchar(255) NOT NULL,
  `data_b` varchar(255) NOT NULL,
  `orden` int(3) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `perfil_id` int(5) unsigned NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime NOT NULL,
  `last_ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) DEFAULT NULL,
  `recovery` tinyint(1) unsigned NOT NULL,
  `recovery_date` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perfil_id` (`perfil_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `perfil_id`, `username`, `password`, `nombre`, `apellido`, `email`, `last_login`, `last_ip`, `active`, `recovery`, `recovery_date`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@koodstudio.com.ar', '$2y$10$9bQgkOTfzXzYivOn6aOL/.m570XKhjhQkGiqRVpFYx0XGPR/a9VqK', 'Administrador', 'Kood Studio', 'admin@koodstudio.com.ar', '0000-00-00 00:00:00', '127.0.0.1', 1, 0, NULL, 'O3YRrUDLt8fKzLN1FSCIlQHWu8oCqvizzYtxhwhBNMcqpBxCfyaLxRu4voIU', '2014-01-21 00:00:00', '2014-08-11 15:56:50'),
(3, 2, 'eduardo@gmail.com', '$2y$10$JfZSHph7SwA9iVr3gUwssONwSMcg8YMLz9xsrDUVUSRylTsH9o7hO', '', '', '', '0000-00-00 00:00:00', '127.0.0.1', 1, 0, '2014-08-14 00:00:00', '$2y$10$iYCWIAb3Gf6QCQm8Rkp8Q.V2yR77WmKXPUm4HzbPgzKQKWgwWxCUK', '2014-08-13 23:59:06', '2014-08-14 18:53:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_ficha`
--

CREATE TABLE IF NOT EXISTS `usuario_ficha` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) unsigned NOT NULL,
  `email` varchar(128) NOT NULL,
  `fecha_nacimiento` datetime NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `apellido` varchar(128) NOT NULL,
  `last_mobile_id` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuario_ficha`
--

INSERT INTO `usuario_ficha` (`id`, `usuario_id`, `email`, `fecha_nacimiento`, `fecha_registro`, `nombre`, `apellido`, `last_mobile_id`, `created_at`, `updated_at`) VALUES
(1, 3, 'eduardo@gmail.com', '1982-07-03 00:00:00', '2014-08-13 11:59:06', 'Eduardo', 'Garcia', 'xxx2', '2014-08-13 23:59:06', '2014-08-13 23:59:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_foto`
--

CREATE TABLE IF NOT EXISTS `usuario_foto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(9) unsigned NOT NULL,
  `fuente` varchar(255) NOT NULL,
  `orden` int(2) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `usuario_foto`
--

INSERT INTO `usuario_foto` (`id`, `usuario_id`, `fuente`, `orden`, `created_at`, `updated_at`) VALUES
(1, 3, 'sarasa.jpg', 0, '2014-08-14 00:00:00', '2014-08-14 00:00:00'),
(2, 3, '0ee2dbc1acb995541f0917adceedd413.jpg', 0, '2014-08-14 20:41:40', '2014-08-14 20:41:40'),
(3, 3, '5696081f46b13c960086f1fa7bfabe68.jpg', 0, '2014-08-14 20:42:50', '2014-08-14 20:42:50');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_ficha`
--
ALTER TABLE `usuario_ficha`
  ADD CONSTRAINT `usuario_ficha_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_foto`
--
ALTER TABLE `usuario_foto`
  ADD CONSTRAINT `usuario_foto_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

<?php

/**
 * Punto de entrada principal de la aplicación Clean Architecture
 * 
 * Este archivo pertenece a la capa de Frameworks and Drivers.
 * Es el único punto de entrada público de la aplicación.
 */

// Configurar zona horaria
date_default_timezone_set('America/Mexico_City');

// Configurar reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar autoloader
require_once __DIR__ . '/../src/FrameworksAndDrivers/Config/Autoloader.php';

use CleanArchitecture\FrameworksAndDrivers\Config\Autoloader;
use CleanArchitecture\FrameworksAndDrivers\Config\Container;
use CleanArchitecture\FrameworksAndDrivers\Web\CorsHandler;
use CleanArchitecture\FrameworksAndDrivers\Web\ErrorHandler;
use CleanArchitecture\InterfaceAdapters\Controllers\TaskController;

// Registrar autoloader
$autoloader = new Autoloader(__DIR__ . '/..');
$autoloader->register();

// Registrar manejador de errores
ErrorHandler::register();

// Manejar CORS
CorsHandler::handle();

// Configurar contenedor de dependencias
$container = new Container();

// Obtener controlador principal
$controller = $container->get(TaskController::class);

// Manejar la petición
$controller->handleRequest();


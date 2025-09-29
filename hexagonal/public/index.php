<?php

// Punto de entrada principal de la aplicación
require_once __DIR__ . '/../src/Config/Autoloader.php';

use App\Config\Autoloader;
use App\Config\Container;
use App\Infrastructure\Adapter\Web\UserController;

// Configurar autoloader
$autoloader = new Autoloader(__DIR__ . '/..');
$autoloader->register();

// Configurar contenedor de dependencias
$container = new Container();

// Manejar CORS para desarrollo
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Enrutar todas las peticiones al controlador de usuarios
$controller = $container->get(UserController::class);
$controller->handleRequest();

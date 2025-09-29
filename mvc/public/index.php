<?php

// Punto de entrada principal de la aplicación MVC
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar autoloader y configuración
require_once __DIR__ . '/../config/autoload.php';

try {
    // Crear instancia del router
    $router = new Router();
    
    // Cargar rutas
    require_once __DIR__ . '/../config/routes.php';
    
    // Despachar la petición
    $router->dispatch();
    
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo "<h1>Error del Servidor</h1>";
    echo "<p>Ha ocurrido un error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><a href='/LEARN/PATRONES/mvc/public/'>Volver al inicio</a></p>";
    
    // En desarrollo, mostrar más detalles del error
    if (true) { // Cambiar a false en producción
        echo "<hr>";
        echo "<h3>Detalles del Error:</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}

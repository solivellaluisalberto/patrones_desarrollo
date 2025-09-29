<?php

namespace CleanArchitecture\FrameworksAndDrivers\Web;

/**
 * CorsHandler - Manejador de CORS para la API
 * 
 * Esta clase maneja las cabeceras CORS necesarias para que la API
 * pueda ser consumida desde diferentes dominios.
 */
class CorsHandler
{
    public static function handle(): void
    {
        // Permitir cualquier origen (en producción, especificar dominios específicos)
        header('Access-Control-Allow-Origin: *');
        
        // Métodos HTTP permitidos
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        
        // Cabeceras permitidas
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        // Permitir credenciales
        header('Access-Control-Allow-Credentials: true');
        
        // Tiempo de cache para preflight requests
        header('Access-Control-Max-Age: 86400');

        // Manejar preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}

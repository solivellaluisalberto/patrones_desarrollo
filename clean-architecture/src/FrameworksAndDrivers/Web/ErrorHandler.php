<?php

namespace CleanArchitecture\FrameworksAndDrivers\Web;

/**
 * ErrorHandler - Manejador global de errores
 * 
 * Esta clase maneja errores no capturados y los formatea
 * apropiadamente para la API.
 */
class ErrorHandler
{
    public static function register(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): void
    {
        if (!(error_reporting() & $severity)) {
            return;
        }

        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException(\Throwable $exception): void
    {
        $isDevelopment = self::isDevelopmentMode();

        $response = [
            'success' => false,
            'error' => 'Error interno del servidor'
        ];

        if ($isDevelopment) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }

        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleException(new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }

    private static function isDevelopmentMode(): bool
    {
        return $_ENV['APP_ENV'] ?? 'development' === 'development';
    }
}

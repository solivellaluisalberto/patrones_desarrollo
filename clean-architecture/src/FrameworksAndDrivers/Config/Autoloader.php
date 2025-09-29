<?php

namespace CleanArchitecture\FrameworksAndDrivers\Config;

/**
 * Autoloader - Cargador automático de clases
 * 
 * Esta clase implementa el estándar PSR-4 para la carga automática de clases.
 * Pertenece a la capa de Frameworks and Drivers.
 */
class Autoloader
{
    private string $baseDir;
    private string $namespace;

    public function __construct(string $baseDir, string $namespace = 'CleanArchitecture\\')
    {
        $this->baseDir = rtrim($baseDir, '/') . '/';
        $this->namespace = rtrim($namespace, '\\') . '\\';
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadClass(string $className): void
    {
        // Verificar si la clase pertenece a nuestro namespace
        if (strpos($className, $this->namespace) !== 0) {
            return;
        }

        // Remover el namespace base
        $relativeClass = substr($className, strlen($this->namespace));

        // Convertir namespace a ruta de archivo
        $file = $this->baseDir . 'src/' . str_replace('\\', '/', $relativeClass) . '.php';

        // Cargar el archivo si existe
        if (file_exists($file)) {
            require_once $file;
        }
    }
}


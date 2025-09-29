<?php

namespace App\Config;

class Autoloader
{
    private string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    private function loadClass(string $className): void
    {
        // Solo procesar clases del namespace App
        if (strpos($className, 'App\\') !== 0) {
            return;
        }
        
        // Remover el namespace base 'App\'
        $className = substr($className, 4);
        
        // Convertir namespace a ruta de archivo
        $file = $this->baseDir . '/src/' . str_replace('\\', '/', $className) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

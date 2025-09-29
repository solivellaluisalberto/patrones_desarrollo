<?php

class Router
{
    private $routes = [];
    private $currentRoute = null;

    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
        return $this;
    }

    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
        return $this;
    }

    public function put($path, $callback)
    {
        $this->addRoute('PUT', $path, $callback);
        return $this;
    }

    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
        return $this;
    }

    private function addRoute($method, $path, $callback)
    {
        // Convertir parámetros de ruta a expresiones regulares
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'callback' => $callback,
            'parameters' => $this->extractParameters($path)
        ];
    }

    private function extractParameters($path)
    {
        preg_match_all('/\{([^}]+)\}/', $path, $matches);
        return $matches[1];
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Manejar método HTTP override para PUT/DELETE
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover el prefijo de la aplicación
        $basePath = '/LEARN/PATRONES/mvc/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remover la coincidencia completa
                
                $this->currentRoute = $route;
                return $this->executeCallback($route['callback'], $matches);
            }
        }

        // Ruta no encontrada
        $this->handleNotFound();
    }

    private function executeCallback($callback, $parameters = [])
    {
        if (is_string($callback)) {
            // Formato: 'ControllerName@method'
            list($controllerName, $method) = explode('@', $callback);
            
            $controllerClass = $controllerName . 'Controller';
            
            if (!class_exists($controllerClass)) {
                throw new Exception("Controlador no encontrado: {$controllerClass}");
            }

            $controller = new $controllerClass();
            
            if (!method_exists($controller, $method)) {
                throw new Exception("Método no encontrado: {$controllerClass}::{$method}");
            }

            return call_user_func_array([$controller, $method], $parameters);
        }

        if (is_callable($callback)) {
            return call_user_func_array($callback, $parameters);
        }

        throw new Exception("Callback no válido");
    }

    private function handleNotFound()
    {
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>La ruta solicitada no existe.</p>";
        echo "<a href='/LEARN/PATRONES/mvc/public/'>Volver al inicio</a>";
        exit;
    }

    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    // Método para generar URLs
    public function url($path, $parameters = [])
    {
        $url = '/LEARN/PATRONES/mvc/public' . $path;
        
        // Reemplazar parámetros en la URL
        foreach ($parameters as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }
        
        return $url;
    }
}

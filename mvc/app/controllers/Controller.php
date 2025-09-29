<?php

abstract class Controller
{
    protected function view($viewName, $data = [])
    {
        // Extraer variables para que estén disponibles en la vista
        extract($data);
        
        // Capturar el contenido de la vista
        ob_start();
        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $viewName) . '.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("Vista no encontrada: {$viewName}");
        }
        
        $content = ob_get_clean();
        
        // Incluir el layout
        $title = $data['title'] ?? 'Aplicación MVC';
        include __DIR__ . '/../views/layout.php';
    }

    protected function redirect($url, $message = null, $type = 'success')
    {
        if ($message) {
            $_SESSION[$type] = $message;
        }
        
        header("Location: {$url}");
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function getInput()
    {
        $input = [];
        
        // Obtener datos POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = array_merge($input, $_POST);
        }
        
        // Obtener datos PUT/PATCH desde php://input
        if (in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
            $putData = [];
            parse_str(file_get_contents('php://input'), $putData);
            $input = array_merge($input, $putData);
        }
        
        return $input;
    }

    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $ruleArray = explode('|', $rule);
            
            foreach ($ruleArray as $singleRule) {
                if ($singleRule === 'required' && empty($data[$field])) {
                    $errors[$field] = "El campo {$field} es requerido";
                    break;
                }

                if (strpos($singleRule, 'min:') === 0) {
                    $min = (int)substr($singleRule, 4);
                    if (isset($data[$field]) && strlen($data[$field]) < $min) {
                        $errors[$field] = "El campo {$field} debe tener al menos {$min} caracteres";
                        break;
                    }
                }

                if (strpos($singleRule, 'max:') === 0) {
                    $max = (int)substr($singleRule, 4);
                    if (isset($data[$field]) && strlen($data[$field]) > $max) {
                        $errors[$field] = "El campo {$field} no puede tener más de {$max} caracteres";
                        break;
                    }
                }

                if ($singleRule === 'numeric' && isset($data[$field]) && !is_numeric($data[$field])) {
                    $errors[$field] = "El campo {$field} debe ser numérico";
                    break;
                }
            }
        }

        return $errors;
    }

    protected function old($key = null, $default = null)
    {
        if (!isset($_SESSION['old_input'])) {
            return $default;
        }

        if ($key === null) {
            return $_SESSION['old_input'];
        }

        return $_SESSION['old_input'][$key] ?? $default;
    }

    protected function withInput($input)
    {
        $_SESSION['old_input'] = $input;
        return $this;
    }

    protected function clearOldInput()
    {
        unset($_SESSION['old_input']);
    }
}

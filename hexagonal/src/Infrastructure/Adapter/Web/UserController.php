<?php

namespace App\Infrastructure\Adapter\Web;

use App\Domain\Port\UserServiceInterface;

class UserController
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($path, '/'));

        header('Content-Type: application/json');

        try {
            switch ($method) {
                case 'GET':
                    if (isset($pathParts[1]) && is_numeric($pathParts[1])) {
                        $this->getUser((int)$pathParts[1]);
                    } else {
                        $this->getAllUsers();
                    }
                    break;

                case 'POST':
                    $this->createUser();
                    break;

                case 'PUT':
                    if (isset($pathParts[1]) && is_numeric($pathParts[1])) {
                        $this->updateUser((int)$pathParts[1]);
                    } else {
                        $this->sendError('ID de usuario requerido para actualizar', 400);
                    }
                    break;

                case 'DELETE':
                    if (isset($pathParts[1]) && is_numeric($pathParts[1])) {
                        $this->deleteUser((int)$pathParts[1]);
                    } else {
                        $this->sendError('ID de usuario requerido para eliminar', 400);
                    }
                    break;

                default:
                    $this->sendError('Método no permitido', 405);
            }
        } catch (\InvalidArgumentException $e) {
            $this->sendError($e->getMessage(), 400);
        } catch (\Exception $e) {
            $this->sendError('Error interno del servidor', 500);
        }
    }

    private function createUser(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name']) || !isset($input['email'])) {
            $this->sendError('Nombre y email son requeridos', 400);
            return;
        }

        $user = $this->userService->createUser($input['name'], $input['email']);
        $this->sendResponse($user->toArray(), 201);
    }

    private function getUser(int $id): void
    {
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            $this->sendError('Usuario no encontrado', 404);
            return;
        }

        $this->sendResponse($user->toArray());
    }

    private function getAllUsers(): void
    {
        $users = $this->userService->getAllUsers();
        $usersArray = array_map(fn($user) => $user->toArray(), $users);
        $this->sendResponse($usersArray);
    }

    private function updateUser(int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name']) || !isset($input['email'])) {
            $this->sendError('Nombre y email son requeridos', 400);
            return;
        }

        $user = $this->userService->updateUser($id, $input['name'], $input['email']);
        
        if (!$user) {
            $this->sendError('Usuario no encontrado', 404);
            return;
        }

        $this->sendResponse($user->toArray());
    }

    private function deleteUser(int $id): void
    {
        $deleted = $this->userService->deleteUser($id);
        
        if (!$deleted) {
            $this->sendError('Usuario no encontrado', 404);
            return;
        }

        $this->sendResponse(['message' => 'Usuario eliminado correctamente']);
    }

    private function sendResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function sendError(string $message, int $statusCode = 400): void
    {
        http_response_code($statusCode);
        echo json_encode(['error' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

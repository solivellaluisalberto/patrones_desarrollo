<?php

namespace CleanArchitecture\InterfaceAdapters\Controllers;

use CleanArchitecture\UseCases\CreateTask\CreateTaskUseCase;
use CleanArchitecture\UseCases\CreateTask\CreateTaskInputData;
use CleanArchitecture\UseCases\GetTask\GetTaskUseCase;
use CleanArchitecture\UseCases\ListTasks\ListTasksUseCase;
use CleanArchitecture\UseCases\UpdateTask\UpdateTaskUseCase;
use CleanArchitecture\UseCases\UpdateTask\UpdateTaskInputData;
use CleanArchitecture\UseCases\DeleteTask\DeleteTaskUseCase;
use CleanArchitecture\InterfaceAdapters\Presenters\JsonPresenter;

/**
 * TaskController - Controlador para manejar peticiones HTTP relacionadas con tareas
 * 
 * Esta clase pertenece a la capa de Interface Adapters.
 * Su responsabilidad es:
 * 1. Recibir peticiones HTTP
 * 2. Convertir datos HTTP a DTOs de entrada
 * 3. Invocar los casos de uso apropiados
 * 4. Usar presenters para formatear la respuesta
 */
class TaskController
{
    public function __construct(
        private CreateTaskUseCase $createTaskUseCase,
        private GetTaskUseCase $getTaskUseCase,
        private ListTasksUseCase $listTasksUseCase,
        private UpdateTaskUseCase $updateTaskUseCase,
        private DeleteTaskUseCase $deleteTaskUseCase,
        private JsonPresenter $presenter
    ) {}

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $this->getPath();

        try {
            match(true) {
                $method === 'GET' && $path === '/tasks' => $this->listTasks(),
                $method === 'GET' && $path === '/tasks/statistics' => $this->getStatistics(),
                $method === 'GET' && $path === '/tasks/overdue' => $this->getOverdueTasks(),
                $method === 'GET' && preg_match('/^\/tasks\/(\d+)$/', $path, $matches) => $this->getTask((int)$matches[1]),
                $method === 'POST' && $path === '/tasks' => $this->createTask(),
                $method === 'PUT' && preg_match('/^\/tasks\/(\d+)$/', $path, $matches) => $this->updateTask((int)$matches[1]),
                $method === 'DELETE' && preg_match('/^\/tasks\/(\d+)$/', $path, $matches) => $this->deleteTask((int)$matches[1]),
                default => $this->presenter->presentError('Endpoint no encontrado', 404)
            };
        } catch (\InvalidArgumentException $e) {
            $this->presenter->presentError($e->getMessage(), 400);
        } catch (\DomainException $e) {
            $this->presenter->presentError($e->getMessage(), 422);
        } catch (\Exception $e) {
            $this->presenter->presentError('Error interno del servidor', 500);
        }
    }

    private function listTasks(): void
    {
        $status = $_GET['status'] ?? null;
        $tasks = $this->listTasksUseCase->execute($status);
        $this->presenter->presentTasks($tasks);
    }

    private function getStatistics(): void
    {
        $statistics = $this->listTasksUseCase->getTaskStatistics();
        $this->presenter->presentData($statistics);
    }

    private function getOverdueTasks(): void
    {
        $tasks = $this->listTasksUseCase->getOverdueTasks();
        $this->presenter->presentTasks($tasks);
    }

    private function getTask(int $id): void
    {
        $task = $this->getTaskUseCase->execute($id);
        
        if (!$task) {
            $this->presenter->presentError('Tarea no encontrada', 404);
            return;
        }

        $this->presenter->presentTask($task);
    }

    private function createTask(): void
    {
        $data = $this->getJsonInput();
        
        $inputData = new CreateTaskInputData(
            title: $data['title'] ?? '',
            description: $data['description'] ?? '',
            priority: $data['priority'] ?? 'medium',
            dueDate: $data['due_date'] ?? null
        );

        $task = $this->createTaskUseCase->execute($inputData);
        $this->presenter->presentTask($task, 201);
    }

    private function updateTask(int $id): void
    {
        $data = $this->getJsonInput();
        
        $inputData = new UpdateTaskInputData(
            id: $id,
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            status: $data['status'] ?? null,
            priority: $data['priority'] ?? null,
            dueDate: $data['due_date'] ?? null
        );

        $task = $this->updateTaskUseCase->execute($inputData);
        
        if (!$task) {
            $this->presenter->presentError('Tarea no encontrada', 404);
            return;
        }

        $this->presenter->presentTask($task);
    }

    private function deleteTask(int $id): void
    {
        $deleted = $this->deleteTaskUseCase->execute($id);
        
        if (!$deleted) {
            $this->presenter->presentError('Tarea no encontrada', 404);
            return;
        }

        $this->presenter->presentSuccess('Tarea eliminada exitosamente');
    }

    private function getPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($path, '/') ?: '/';
    }

    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('JSON inválido');
        }
        
        return $data ?? [];
    }
}


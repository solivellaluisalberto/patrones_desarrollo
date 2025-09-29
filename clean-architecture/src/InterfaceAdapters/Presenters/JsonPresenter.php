<?php

namespace CleanArchitecture\InterfaceAdapters\Presenters;

use CleanArchitecture\UseCases\CreateTask\CreateTaskOutputData;

/**
 * JsonPresenter - Presenter para formatear respuestas en JSON
 * 
 * Los presenters son responsables de formatear los datos de salida
 * de los casos de uso en el formato apropiado para la interfaz específica.
 * En este caso, formatea las respuestas como JSON para una API REST.
 */
class JsonPresenter
{
    public function presentTask(CreateTaskOutputData $task, int $statusCode = 200): void
    {
        $this->sendJsonResponse([
            'success' => true,
            'data' => $task->toArray()
        ], $statusCode);
    }

    public function presentTasks(array $tasks, int $statusCode = 200): void
    {
        $this->sendJsonResponse([
            'success' => true,
            'data' => array_map(fn($task) => $task->toArray(), $tasks),
            'count' => count($tasks)
        ], $statusCode);
    }

    public function presentData(array $data, int $statusCode = 200): void
    {
        $this->sendJsonResponse([
            'success' => true,
            'data' => $data
        ], $statusCode);
    }

    public function presentSuccess(string $message, int $statusCode = 200): void
    {
        $this->sendJsonResponse([
            'success' => true,
            'message' => $message
        ], $statusCode);
    }

    public function presentError(string $message, int $statusCode = 400): void
    {
        $this->sendJsonResponse([
            'success' => false,
            'error' => $message
        ], $statusCode);
    }

    private function sendJsonResponse(array $data, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}


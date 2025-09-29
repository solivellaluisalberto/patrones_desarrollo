<?php

namespace CleanArchitecture\UseCases\ListTasks;

use CleanArchitecture\UseCases\TaskRepositoryInterface;
use CleanArchitecture\UseCases\CreateTask\CreateTaskOutputData;

/**
 * ListTasksUseCase - Caso de uso para listar todas las tareas
 */
class ListTasksUseCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(?string $status = null): array
    {
        if ($status) {
            $this->validateStatus($status);
            $tasks = $this->taskRepository->findByStatus($status);
        } else {
            $tasks = $this->taskRepository->findAll();
        }

        return array_map(
            fn($task) => CreateTaskOutputData::fromTask($task),
            $tasks
        );
    }

    public function getOverdueTasks(): array
    {
        $tasks = $this->taskRepository->findOverdueTasks();
        
        return array_map(
            fn($task) => CreateTaskOutputData::fromTask($task),
            $tasks
        );
    }

    public function getTaskStatistics(): array
    {
        return [
            'total' => count($this->taskRepository->findAll()),
            'pending' => $this->taskRepository->countByStatus('pending'),
            'in_progress' => $this->taskRepository->countByStatus('in_progress'),
            'completed' => $this->taskRepository->countByStatus('completed'),
            'cancelled' => $this->taskRepository->countByStatus('cancelled'),
            'overdue' => count($this->taskRepository->findOverdueTasks())
        ];
    }

    private function validateStatus(string $status): void
    {
        $validStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Estado inválido: $status");
        }
    }
}

<?php

namespace CleanArchitecture\UseCases\GetTask;

use CleanArchitecture\UseCases\TaskRepositoryInterface;
use CleanArchitecture\UseCases\CreateTask\CreateTaskOutputData;

/**
 * GetTaskUseCase - Caso de uso para obtener una tarea por ID
 */
class GetTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(int $taskId): ?CreateTaskOutputData
    {
        if ($taskId <= 0) {
            throw new \InvalidArgumentException("El ID de la tarea debe ser mayor a 0");
        }

        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            return null;
        }

        return CreateTaskOutputData::fromTask($task);
    }
}

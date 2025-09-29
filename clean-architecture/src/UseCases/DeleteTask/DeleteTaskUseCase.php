<?php

namespace CleanArchitecture\UseCases\DeleteTask;

use CleanArchitecture\UseCases\TaskRepositoryInterface;

/**
 * DeleteTaskUseCase - Caso de uso para eliminar una tarea
 */
class DeleteTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(int $taskId): bool
    {
        if ($taskId <= 0) {
            throw new \InvalidArgumentException("El ID de la tarea debe ser mayor a 0");
        }

        // Verificar que la tarea existe
        $task = $this->taskRepository->findById($taskId);
        if (!$task) {
            return false;
        }

        // Regla de negocio: No se pueden eliminar tareas completadas
        // (esto podría ser configurable según los requerimientos)
        if ($task->getStatus()->value === 'completed') {
            throw new \DomainException("No se pueden eliminar tareas completadas");
        }

        return $this->taskRepository->delete($taskId);
    }
}


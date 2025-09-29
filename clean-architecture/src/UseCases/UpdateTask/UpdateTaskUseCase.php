<?php

namespace CleanArchitecture\UseCases\UpdateTask;

use CleanArchitecture\Entities\TaskStatus;
use CleanArchitecture\Entities\TaskPriority;
use CleanArchitecture\UseCases\TaskRepositoryInterface;
use CleanArchitecture\UseCases\CreateTask\CreateTaskOutputData;

/**
 * UpdateTaskUseCase - Caso de uso para actualizar una tarea existente
 */
class UpdateTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(UpdateTaskInputData $inputData): ?CreateTaskOutputData
    {
        // Validar que la tarea existe
        $task = $this->taskRepository->findById($inputData->id);
        if (!$task) {
            return null;
        }

        // Aplicar actualizaciones según los campos proporcionados
        if ($inputData->hasTitle()) {
            $task->updateTitle($inputData->title);
        }

        if ($inputData->hasDescription()) {
            $task->updateDescription($inputData->description);
        }

        if ($inputData->hasStatus()) {
            $this->updateTaskStatus($task, $inputData->status);
        }

        if ($inputData->hasPriority()) {
            $priority = $this->convertPriority($inputData->priority);
            $task->changePriority($priority);
        }

        if ($inputData->hasDueDate()) {
            $dueDate = $inputData->dueDate ? new \DateTime($inputData->dueDate) : null;
            $task->setDueDate($dueDate);
        }

        // Guardar cambios
        $updatedTask = $this->taskRepository->update($task);

        return CreateTaskOutputData::fromTask($updatedTask);
    }

    private function updateTaskStatus($task, string $newStatus): void
    {
        $statusEnum = $this->convertStatus($newStatus);
        
        // Verificar si la transición es válida
        if (!$task->getStatus()->canTransitionTo($statusEnum)) {
            throw new \DomainException(
                "No se puede cambiar de '{$task->getStatus()->getDisplayName()}' a '{$statusEnum->getDisplayName()}'"
            );
        }

        // Aplicar el cambio de estado usando los métodos de la entidad
        match($statusEnum) {
            TaskStatus::IN_PROGRESS => $task->markAsInProgress(),
            TaskStatus::COMPLETED => $task->markAsCompleted(),
            default => throw new \InvalidArgumentException("Transición de estado no implementada")
        };
    }

    private function convertStatus(string $status): TaskStatus
    {
        return match($status) {
            'pending' => TaskStatus::PENDING,
            'in_progress' => TaskStatus::IN_PROGRESS,
            'completed' => TaskStatus::COMPLETED,
            'cancelled' => TaskStatus::CANCELLED,
            default => throw new \InvalidArgumentException("Estado inválido: $status")
        };
    }

    private function convertPriority(string $priority): TaskPriority
    {
        return match($priority) {
            'low' => TaskPriority::LOW,
            'medium' => TaskPriority::MEDIUM,
            'high' => TaskPriority::HIGH,
            'urgent' => TaskPriority::URGENT,
            default => throw new \InvalidArgumentException("Prioridad inválida: $priority")
        };
    }
}

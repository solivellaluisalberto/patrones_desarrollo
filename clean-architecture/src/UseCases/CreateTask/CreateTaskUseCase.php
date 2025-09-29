<?php

namespace CleanArchitecture\UseCases\CreateTask;

use CleanArchitecture\Entities\Task;
use CleanArchitecture\Entities\TaskPriority;
use CleanArchitecture\UseCases\TaskRepositoryInterface;

/**
 * CreateTaskUseCase - Caso de uso para crear una nueva tarea
 * 
 * Los casos de uso contienen la lógica de aplicación específica.
 * Orquestan el flujo de datos hacia y desde las entidades,
 * y dirigen a esas entidades a usar su lógica de negocio empresarial
 * para lograr los objetivos del caso de uso.
 */
class CreateTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(CreateTaskInputData $inputData): CreateTaskOutputData
    {
        // Validar datos de entrada a nivel de aplicación
        $this->validateInput($inputData);

        // Convertir prioridad string a enum
        $priority = $this->convertPriority($inputData->priority);

        // Convertir fecha si existe
        $dueDate = $inputData->dueDate ? new \DateTime($inputData->dueDate) : null;

        // Obtener siguiente ID
        $nextId = $this->taskRepository->getNextId();

        // Crear entidad Task (aquí se aplican las reglas de negocio)
        $task = new Task(
            id: $nextId,
            title: $inputData->title,
            description: $inputData->description,
            priority: $priority,
            dueDate: $dueDate
        );

        // Persistir la tarea
        $savedTask = $this->taskRepository->save($task);

        // Retornar datos de salida
        return CreateTaskOutputData::fromTask($savedTask);
    }

    private function validateInput(CreateTaskInputData $inputData): void
    {
        if (empty(trim($inputData->title))) {
            throw new \InvalidArgumentException("El título es requerido");
        }

        if (empty(trim($inputData->description))) {
            throw new \InvalidArgumentException("La descripción es requerida");
        }

        if ($inputData->dueDate && !$this->isValidDate($inputData->dueDate)) {
            throw new \InvalidArgumentException("Formato de fecha inválido");
        }

        if (!in_array($inputData->priority, ['low', 'medium', 'high', 'urgent'])) {
            throw new \InvalidArgumentException("Prioridad inválida");
        }
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

    private function isValidDate(string $date): bool
    {
        try {
            new \DateTime($date);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}


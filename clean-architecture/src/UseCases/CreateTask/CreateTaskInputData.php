<?php

namespace CleanArchitecture\UseCases\CreateTask;

/**
 * CreateTaskInputData - DTO para los datos de entrada del caso de uso
 * 
 * Los DTOs (Data Transfer Objects) transportan datos entre capas
 * sin contener lógica de negocio.
 */
class CreateTaskInputData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $priority = 'medium',
        public readonly ?string $dueDate = null
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->dueDate
        ];
    }
}

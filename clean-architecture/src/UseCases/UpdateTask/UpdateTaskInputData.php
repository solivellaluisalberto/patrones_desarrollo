<?php

namespace CleanArchitecture\UseCases\UpdateTask;

/**
 * UpdateTaskInputData - DTO para actualizar una tarea
 */
class UpdateTaskInputData
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $status = null,
        public readonly ?string $priority = null,
        public readonly ?string $dueDate = null
    ) {}

    public function hasTitle(): bool
    {
        return $this->title !== null;
    }

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    public function hasStatus(): bool
    {
        return $this->status !== null;
    }

    public function hasPriority(): bool
    {
        return $this->priority !== null;
    }

    public function hasDueDate(): bool
    {
        return $this->dueDate !== null;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->dueDate
        ], fn($value) => $value !== null);
    }
}

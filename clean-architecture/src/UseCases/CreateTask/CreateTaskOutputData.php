<?php

namespace CleanArchitecture\UseCases\CreateTask;

use CleanArchitecture\Entities\Task;

/**
 * CreateTaskOutputData - DTO para los datos de salida del caso de uso
 */
class CreateTaskOutputData
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly string $priority,
        public readonly string $createdAt,
        public readonly ?string $dueDate = null
    ) {}

    public static function fromTask(Task $task): self
    {
        return new self(
            id: $task->getId(),
            title: $task->getTitle(),
            description: $task->getDescription(),
            status: $task->getStatus()->value,
            priority: $task->getPriority()->value,
            createdAt: $task->getCreatedAt()->format('Y-m-d H:i:s'),
            dueDate: $task->getDueDate()?->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'created_at' => $this->createdAt,
            'due_date' => $this->dueDate
        ];
    }
}

<?php

namespace CleanArchitecture\Entities;

/**
 * Entidad Task - Capa más interna de Clean Architecture
 * 
 * Las entidades encapsulan las reglas de negocio más generales y de alto nivel.
 * Son los objetos menos propensos a cambiar cuando algo externo cambia.
 * No dependen de nada externo.
 */
class Task
{
    private int $id;
    private string $title;
    private string $description;
    private TaskStatus $status;
    private TaskPriority $priority;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;
    private ?\DateTime $dueDate;

    public function __construct(
        int $id,
        string $title,
        string $description,
        TaskStatus $status = TaskStatus::PENDING,
        TaskPriority $priority = TaskPriority::MEDIUM,
        ?\DateTime $dueDate = null
    ) {
        $this->validateTitle($title);
        $this->validateDescription($description);
        
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->priority = $priority;
        $this->dueDate = $dueDate;
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
    }

    // Reglas de negocio de la entidad
    private function validateTitle(string $title): void
    {
        if (empty(trim($title))) {
            throw new \InvalidArgumentException("El título no puede estar vacío");
        }
        
        if (strlen($title) < 3) {
            throw new \InvalidArgumentException("El título debe tener al menos 3 caracteres");
        }
        
        if (strlen($title) > 100) {
            throw new \InvalidArgumentException("El título no puede exceder 100 caracteres");
        }
    }

    private function validateDescription(string $description): void
    {
        if (empty(trim($description))) {
            throw new \InvalidArgumentException("La descripción no puede estar vacía");
        }
        
        if (strlen($description) < 10) {
            throw new \InvalidArgumentException("La descripción debe tener al menos 10 caracteres");
        }
        
        if (strlen($description) > 1000) {
            throw new \InvalidArgumentException("La descripción no puede exceder 1000 caracteres");
        }
    }

    // Métodos de negocio
    public function markAsCompleted(): void
    {
        if ($this->status === TaskStatus::COMPLETED) {
            throw new \DomainException("La tarea ya está completada");
        }
        
        $this->status = TaskStatus::COMPLETED;
        $this->updatedAt = new \DateTime();
    }

    public function markAsInProgress(): void
    {
        if ($this->status === TaskStatus::COMPLETED) {
            throw new \DomainException("No se puede cambiar el estado de una tarea completada");
        }
        
        $this->status = TaskStatus::IN_PROGRESS;
        $this->updatedAt = new \DateTime();
    }

    public function updateTitle(string $newTitle): void
    {
        $this->validateTitle($newTitle);
        $this->title = $newTitle;
        $this->updatedAt = new \DateTime();
    }

    public function updateDescription(string $newDescription): void
    {
        $this->validateDescription($newDescription);
        $this->description = $newDescription;
        $this->updatedAt = new \DateTime();
    }

    public function changePriority(TaskPriority $newPriority): void
    {
        $this->priority = $newPriority;
        $this->updatedAt = new \DateTime();
    }

    public function setDueDate(?\DateTime $dueDate): void
    {
        if ($dueDate && $dueDate < new \DateTime()) {
            throw new \InvalidArgumentException("La fecha de vencimiento no puede ser en el pasado");
        }
        
        $this->dueDate = $dueDate;
        $this->updatedAt = new \DateTime();
    }

    public function isOverdue(): bool
    {
        if (!$this->dueDate || $this->status === TaskStatus::COMPLETED) {
            return false;
        }
        
        return $this->dueDate < new \DateTime();
    }

    public function getDaysUntilDue(): ?int
    {
        if (!$this->dueDate) {
            return null;
        }
        
        $now = new \DateTime();
        $interval = $now->diff($this->dueDate);
        
        return $interval->invert ? -$interval->days : $interval->days;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function getPriority(): TaskPriority
    {
        return $this->priority;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'due_date' => $this->dueDate?->format('Y-m-d H:i:s'),
            'is_overdue' => $this->isOverdue(),
            'days_until_due' => $this->getDaysUntilDue()
        ];
    }
}


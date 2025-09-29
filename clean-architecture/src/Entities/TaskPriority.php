<?php

namespace CleanArchitecture\Entities;

/**
 * Enum TaskPriority - Value Object para la prioridad de las tareas
 */
enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function getDisplayName(): string
    {
        return match($this) {
            self::LOW => 'Baja',
            self::MEDIUM => 'Media',
            self::HIGH => 'Alta',
            self::URGENT => 'Urgente'
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::LOW => 'success',
            self::MEDIUM => 'info',
            self::HIGH => 'warning',
            self::URGENT => 'danger'
        };
    }

    public function getNumericValue(): int
    {
        return match($this) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
            self::URGENT => 4
        };
    }

    public static function fromNumericValue(int $value): self
    {
        return match($value) {
            1 => self::LOW,
            2 => self::MEDIUM,
            3 => self::HIGH,
            4 => self::URGENT,
            default => throw new \InvalidArgumentException("Valor de prioridad inválido: $value")
        };
    }

    public static function getAllPriorities(): array
    {
        return [
            self::LOW,
            self::MEDIUM,
            self::HIGH,
            self::URGENT
        ];
    }
}

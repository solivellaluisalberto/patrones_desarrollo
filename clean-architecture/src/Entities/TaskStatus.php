<?php

namespace CleanArchitecture\Entities;

/**
 * Enum TaskStatus - Value Object para el estado de las tareas
 * 
 * Los Value Objects son objetos inmutables que representan conceptos
 * del dominio y encapsulan validaciones y comportamientos específicos.
 */
enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getDisplayName(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::IN_PROGRESS => 'En Progreso',
            self::COMPLETED => 'Completada',
            self::CANCELLED => 'Cancelada'
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::IN_PROGRESS => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'secondary'
        };
    }

    public function canTransitionTo(TaskStatus $newStatus): bool
    {
        return match($this) {
            self::PENDING => in_array($newStatus, [self::IN_PROGRESS, self::CANCELLED]),
            self::IN_PROGRESS => in_array($newStatus, [self::COMPLETED, self::CANCELLED, self::PENDING]),
            self::COMPLETED => false, // Las tareas completadas no pueden cambiar
            self::CANCELLED => in_array($newStatus, [self::PENDING]) // Solo se puede reactivar
        };
    }

    public static function getAllStatuses(): array
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::CANCELLED
        ];
    }
}

<?php

namespace CleanArchitecture\UseCases;

use CleanArchitecture\Entities\Task;

/**
 * Interface TaskRepositoryInterface - Contrato para el repositorio de tareas
 * 
 * Esta interfaz pertenece a la capa de Casos de Uso y define el contrato
 * que debe cumplir cualquier implementación de repositorio.
 * La implementación concreta estará en la capa de Interface Adapters.
 */
interface TaskRepositoryInterface
{
    public function save(Task $task): Task;
    public function findById(int $id): ?Task;
    public function findAll(): array;
    public function update(Task $task): Task;
    public function delete(int $id): bool;
    public function findByStatus(string $status): array;
    public function findOverdueTasks(): array;
    public function countByStatus(string $status): int;
    public function getNextId(): int;
}

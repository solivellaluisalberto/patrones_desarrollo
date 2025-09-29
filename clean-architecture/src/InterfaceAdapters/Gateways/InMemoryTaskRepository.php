<?php

namespace CleanArchitecture\InterfaceAdapters\Gateways;

use CleanArchitecture\Entities\Task;
use CleanArchitecture\Entities\TaskStatus;
use CleanArchitecture\UseCases\TaskRepositoryInterface;

/**
 * InMemoryTaskRepository - Implementación en memoria del repositorio de tareas
 * 
 * Esta clase pertenece a la capa de Interface Adapters y implementa
 * la interfaz definida en la capa de Use Cases.
 * Es un adaptador que permite que los casos de uso funcionen
 * sin depender de una base de datos específica.
 */
class InMemoryTaskRepository implements TaskRepositoryInterface
{
    private array $tasks = [];
    private int $nextId = 1;

    public function __construct()
    {
        // Datos de ejemplo para demostración
        $this->seedData();
    }

    public function save(Task $task): Task
    {
        $this->tasks[$task->getId()] = $task;
        return $task;
    }

    public function findById(int $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->tasks);
    }

    public function update(Task $task): Task
    {
        if (!isset($this->tasks[$task->getId()])) {
            throw new \InvalidArgumentException("Tarea no encontrada para actualizar");
        }

        $this->tasks[$task->getId()] = $task;
        return $task;
    }

    public function delete(int $id): bool
    {
        if (!isset($this->tasks[$id])) {
            return false;
        }

        unset($this->tasks[$id]);
        return true;
    }

    public function findByStatus(string $status): array
    {
        return array_filter(
            $this->tasks,
            fn(Task $task) => $task->getStatus()->value === $status
        );
    }

    public function findOverdueTasks(): array
    {
        return array_filter(
            $this->tasks,
            fn(Task $task) => $task->isOverdue()
        );
    }

    public function countByStatus(string $status): int
    {
        return count($this->findByStatus($status));
    }

    public function getNextId(): int
    {
        return $this->nextId++;
    }

    /**
     * Poblar con datos de ejemplo
     */
    private function seedData(): void
    {
        // Tarea 1: Pendiente
        $task1 = new Task(
            id: $this->getNextId(),
            title: "Implementar autenticación de usuarios",
            description: "Desarrollar el sistema de login y registro para la aplicación web. Incluir validaciones de seguridad y manejo de sesiones.",
            priority: \CleanArchitecture\Entities\TaskPriority::HIGH,
            dueDate: new \DateTime('+5 days')
        );
        $this->save($task1);

        // Tarea 2: En progreso
        $task2 = new Task(
            id: $this->getNextId(),
            title: "Diseñar base de datos",
            description: "Crear el esquema de la base de datos con todas las tablas necesarias para el sistema de gestión de tareas.",
            priority: \CleanArchitecture\Entities\TaskPriority::MEDIUM,
            dueDate: new \DateTime('+3 days')
        );
        $task2->markAsInProgress();
        $this->save($task2);

        // Tarea 3: Completada
        $task3 = new Task(
            id: $this->getNextId(),
            title: "Configurar entorno de desarrollo",
            description: "Instalar y configurar todas las herramientas necesarias para el desarrollo: PHP, Composer, servidor web, etc.",
            priority: \CleanArchitecture\Entities\TaskPriority::LOW,
            dueDate: new \DateTime('-2 days')
        );
        $task3->markAsCompleted();
        $this->save($task3);

        // Tarea 4: Vencida
        $task4 = new Task(
            id: $this->getNextId(),
            title: "Revisar documentación técnica",
            description: "Leer y analizar toda la documentación técnica del proyecto para entender los requerimientos.",
            priority: \CleanArchitecture\Entities\TaskPriority::URGENT,
            dueDate: new \DateTime('-1 day')
        );
        $this->save($task4);

        // Tarea 5: Sin fecha límite
        $task5 = new Task(
            id: $this->getNextId(),
            title: "Optimizar rendimiento",
            description: "Analizar y mejorar el rendimiento general de la aplicación, identificando cuellos de botella.",
            priority: \CleanArchitecture\Entities\TaskPriority::LOW
        );
        $this->save($task5);
    }
}


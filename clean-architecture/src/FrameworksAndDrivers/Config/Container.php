<?php

namespace CleanArchitecture\FrameworksAndDrivers\Config;

use CleanArchitecture\UseCases\TaskRepositoryInterface;
use CleanArchitecture\InterfaceAdapters\Gateways\InMemoryTaskRepository;
use CleanArchitecture\UseCases\CreateTask\CreateTaskUseCase;
use CleanArchitecture\UseCases\GetTask\GetTaskUseCase;
use CleanArchitecture\UseCases\ListTasks\ListTasksUseCase;
use CleanArchitecture\UseCases\UpdateTask\UpdateTaskUseCase;
use CleanArchitecture\UseCases\DeleteTask\DeleteTaskUseCase;
use CleanArchitecture\InterfaceAdapters\Controllers\TaskController;
use CleanArchitecture\InterfaceAdapters\Presenters\JsonPresenter;

/**
 * Container - Contenedor de Inyección de Dependencias
 * 
 * Esta clase pertenece a la capa de Frameworks and Drivers.
 * Es responsable de configurar y proporcionar todas las dependencias
 * necesarias para que la aplicación funcione.
 * 
 * Implementa el patrón Dependency Injection Container.
 */
class Container
{
    private array $services = [];
    private array $singletons = [];

    public function __construct()
    {
        $this->registerServices();
    }

    public function get(string $className)
    {
        // Si es un singleton y ya existe, devolverlo
        if (isset($this->singletons[$className])) {
            return $this->singletons[$className];
        }

        // Si tiene una factory registrada, usarla
        if (isset($this->services[$className])) {
            $factory = $this->services[$className];
            $instance = $factory($this);
            
            // Si es singleton, guardarlo
            if ($this->isSingleton($className)) {
                $this->singletons[$className] = $instance;
            }
            
            return $instance;
        }

        // Intentar crear automáticamente usando reflexión
        return $this->createAutomatically($className);
    }

    private function registerServices(): void
    {
        // Repositorios (Singletons)
        $this->services[TaskRepositoryInterface::class] = function (Container $container) {
            return new InMemoryTaskRepository();
        };

        // Casos de Uso
        $this->services[CreateTaskUseCase::class] = function (Container $container) {
            return new CreateTaskUseCase(
                $container->get(TaskRepositoryInterface::class)
            );
        };

        $this->services[GetTaskUseCase::class] = function (Container $container) {
            return new GetTaskUseCase(
                $container->get(TaskRepositoryInterface::class)
            );
        };

        $this->services[ListTasksUseCase::class] = function (Container $container) {
            return new ListTasksUseCase(
                $container->get(TaskRepositoryInterface::class)
            );
        };

        $this->services[UpdateTaskUseCase::class] = function (Container $container) {
            return new UpdateTaskUseCase(
                $container->get(TaskRepositoryInterface::class)
            );
        };

        $this->services[DeleteTaskUseCase::class] = function (Container $container) {
            return new DeleteTaskUseCase(
                $container->get(TaskRepositoryInterface::class)
            );
        };

        // Presenters
        $this->services[JsonPresenter::class] = function (Container $container) {
            return new JsonPresenter();
        };

        // Controladores
        $this->services[TaskController::class] = function (Container $container) {
            return new TaskController(
                $container->get(CreateTaskUseCase::class),
                $container->get(GetTaskUseCase::class),
                $container->get(ListTasksUseCase::class),
                $container->get(UpdateTaskUseCase::class),
                $container->get(DeleteTaskUseCase::class),
                $container->get(JsonPresenter::class)
            );
        };
    }

    private function isSingleton(string $className): bool
    {
        $singletons = [
            TaskRepositoryInterface::class,
            InMemoryTaskRepository::class,
        ];

        return in_array($className, $singletons);
    }

    private function createAutomatically(string $className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Clase no encontrada: $className");
        }

        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $className();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if (!$type || $type->isBuiltin()) {
                throw new \InvalidArgumentException(
                    "No se puede resolver automáticamente la dependencia para: {$parameter->getName()}"
                );
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    public function register(string $className, callable $factory, bool $singleton = false): void
    {
        $this->services[$className] = $factory;
        
        if ($singleton) {
            // Marcar como singleton agregándolo a la lista
            $this->singletons[$className] = null;
        }
    }
}

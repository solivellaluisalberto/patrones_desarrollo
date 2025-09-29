<?php

namespace App\Config;

use App\Application\UseCase\UserService;
use App\Domain\Port\UserRepositoryInterface;
use App\Domain\Port\UserServiceInterface;
use App\Infrastructure\Adapter\Repository\InMemoryUserRepository;
use App\Infrastructure\Adapter\Web\UserController;

class Container
{
    private array $services = [];

    public function __construct()
    {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        // Registrar repositorio
        $this->services[UserRepositoryInterface::class] = function () {
            return new InMemoryUserRepository();
        };

        // Registrar servicio de usuario
        $this->services[UserServiceInterface::class] = function () {
            return new UserService($this->get(UserRepositoryInterface::class));
        };

        // Registrar controlador
        $this->services[UserController::class] = function () {
            return new UserController($this->get(UserServiceInterface::class));
        };
    }

    public function get(string $serviceId)
    {
        if (!isset($this->services[$serviceId])) {
            throw new \InvalidArgumentException("Servicio no encontrado: {$serviceId}");
        }

        if (is_callable($this->services[$serviceId])) {
            $this->services[$serviceId] = $this->services[$serviceId]();
        }

        return $this->services[$serviceId];
    }

    public function set(string $serviceId, $service): void
    {
        $this->services[$serviceId] = $service;
    }
}

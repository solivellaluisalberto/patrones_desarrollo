<?php

namespace App\Application\UseCase;

use App\Domain\Entity\User;
use App\Domain\Port\UserRepositoryInterface;
use App\Domain\Port\UserServiceInterface;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(string $name, string $email): User
    {
        // Validar que el email no exista
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            throw new \InvalidArgumentException("El email ya está en uso");
        }

        // Validar datos básicos
        if (empty($name) || empty($email)) {
            throw new \InvalidArgumentException("El nombre y email son requeridos");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("El email no es válido");
        }

        // Generar ID (en una implementación real, esto lo haría la base de datos)
        $id = time() + rand(1, 1000);
        
        $user = new User($id, $name, $email);
        return $this->userRepository->save($user);
    }

    public function getUserById(int $id): ?User
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("El ID debe ser mayor a 0");
        }

        return $this->userRepository->findById($id);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function updateUser(int $id, string $name, string $email): ?User
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("El ID debe ser mayor a 0");
        }

        if (empty($name) || empty($email)) {
            throw new \InvalidArgumentException("El nombre y email son requeridos");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("El email no es válido");
        }

        $user = $this->userRepository->findById($id);
        if (!$user) {
            return null;
        }

        // Verificar que el email no esté en uso por otro usuario
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser && $existingUser->getId() !== $id) {
            throw new \InvalidArgumentException("El email ya está en uso por otro usuario");
        }

        $user->updateName($name);
        $user->updateEmail($email);

        return $this->userRepository->update($user);
    }

    public function deleteUser(int $id): bool
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("El ID debe ser mayor a 0");
        }

        $user = $this->userRepository->findById($id);
        if (!$user) {
            return false;
        }

        return $this->userRepository->delete($id);
    }
}

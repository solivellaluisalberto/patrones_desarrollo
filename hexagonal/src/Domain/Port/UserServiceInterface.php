<?php

namespace App\Domain\Port;

use App\Domain\Entity\User;

interface UserServiceInterface
{
    public function createUser(string $name, string $email): User;
    public function getUserById(int $id): ?User;
    public function getAllUsers(): array;
    public function updateUser(int $id, string $name, string $email): ?User;
    public function deleteUser(int $id): bool;
}

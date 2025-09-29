<?php

namespace App\Domain\Port;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;
    public function findById(int $id): ?User;
    public function findAll(): array;
    public function update(User $user): User;
    public function delete(int $id): bool;
    public function findByEmail(string $email): ?User;
}

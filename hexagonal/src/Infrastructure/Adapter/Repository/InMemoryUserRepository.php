<?php

namespace App\Infrastructure\Adapter\Repository;

use App\Domain\Entity\User;
use App\Domain\Port\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    private array $users = [];
    private int $nextId = 1;

    public function save(User $user): User
    {
        $id = $user->getId() ?: $this->nextId++;
        $userData = $user->toArray();
        $userData['id'] = $id;
        
        $savedUser = new User(
            $userData['id'],
            $userData['name'],
            $userData['email'],
            new \DateTime($userData['created_at']),
            $userData['updated_at'] ? new \DateTime($userData['updated_at']) : null
        );
        
        $this->users[$id] = $savedUser;
        return $savedUser;
    }

    public function findById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->users);
    }

    public function update(User $user): User
    {
        $id = $user->getId();
        if (!isset($this->users[$id])) {
            throw new \InvalidArgumentException("Usuario no encontrado");
        }
        
        $this->users[$id] = $user;
        return $user;
    }

    public function delete(int $id): bool
    {
        if (!isset($this->users[$id])) {
            return false;
        }
        
        unset($this->users[$id]);
        return true;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }
}

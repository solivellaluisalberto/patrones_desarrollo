<?php

/**
 * Script de prueba para la API de usuarios
 * Ejecutar desde línea de comandos: php test_api.php
 */

require_once __DIR__ . '/src/Config/Autoloader.php';

use App\Config\Autoloader;
use App\Config\Container;
use App\Domain\Port\UserServiceInterface;

// Configurar autoloader
$autoloader = new Autoloader(__DIR__);
$autoloader->register();

// Configurar contenedor
$container = new Container();
$userService = $container->get(UserServiceInterface::class);

echo "=== Prueba de la Aplicación con Arquitectura Hexagonal ===\n\n";

try {
    // Crear usuarios
    echo "1. Creando usuarios...\n";
    $user1 = $userService->createUser("Juan Pérez", "juan@example.com");
    $user2 = $userService->createUser("María García", "maria@example.com");
    $user3 = $userService->createUser("Carlos López", "carlos@example.com");
    
    echo "✓ Usuario creado: {$user1->getName()} ({$user1->getEmail()})\n";
    echo "✓ Usuario creado: {$user2->getName()} ({$user2->getEmail()})\n";
    echo "✓ Usuario creado: {$user3->getName()} ({$user3->getEmail()})\n\n";

    // Listar todos los usuarios
    echo "2. Listando todos los usuarios...\n";
    $users = $userService->getAllUsers();
    foreach ($users as $user) {
        echo "- ID: {$user->getId()}, Nombre: {$user->getName()}, Email: {$user->getEmail()}\n";
    }
    echo "\n";

    // Obtener usuario por ID
    echo "3. Obteniendo usuario por ID...\n";
    $foundUser = $userService->getUserById($user1->getId());
    if ($foundUser) {
        echo "✓ Usuario encontrado: {$foundUser->getName()} ({$foundUser->getEmail()})\n";
    }
    echo "\n";

    // Actualizar usuario
    echo "4. Actualizando usuario...\n";
    $updatedUser = $userService->updateUser($user2->getId(), "María José García", "mariajose@example.com");
    if ($updatedUser) {
        echo "✓ Usuario actualizado: {$updatedUser->getName()} ({$updatedUser->getEmail()})\n";
    }
    echo "\n";

    // Intentar crear usuario con email duplicado
    echo "5. Probando validación de email duplicado...\n";
    try {
        $userService->createUser("Otro Juan", "juan@example.com");
    } catch (InvalidArgumentException $e) {
        echo "✓ Validación correcta: {$e->getMessage()}\n";
    }
    echo "\n";

    // Eliminar usuario
    echo "6. Eliminando usuario...\n";
    $deleted = $userService->deleteUser($user3->getId());
    if ($deleted) {
        echo "✓ Usuario eliminado correctamente\n";
    }
    echo "\n";

    // Listar usuarios después de eliminar
    echo "7. Listando usuarios después de eliminar...\n";
    $users = $userService->getAllUsers();
    foreach ($users as $user) {
        echo "- ID: {$user->getId()}, Nombre: {$user->getName()}, Email: {$user->getEmail()}\n";
    }
    echo "\n";

    echo "=== Todas las pruebas completadas exitosamente ===\n";

} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}

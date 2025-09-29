<?php

/**
 * Script de prueba para la API de Clean Architecture
 * 
 * Este script demuestra cómo usar la API REST implementada
 * con Clean Architecture.
 */

$baseUrl = 'http://localhost/LEARN/PATRONES/clean-architecture/public';

echo "🧪 Probando API de Clean Architecture - Gestión de Tareas\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Función helper para hacer peticiones HTTP
function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

// 1. Listar todas las tareas
echo "1️⃣  Listando todas las tareas:\n";
$response = makeRequest("$baseUrl/tasks");
echo "   Status: {$response['code']}\n";
echo "   Tareas encontradas: " . ($response['body']['count'] ?? 0) . "\n\n";

// 2. Obtener estadísticas
echo "2️⃣  Obteniendo estadísticas:\n";
$response = makeRequest("$baseUrl/tasks/statistics");
echo "   Status: {$response['code']}\n";
if ($response['body']['success']) {
    $stats = $response['body']['data'];
    echo "   📊 Total: {$stats['total']}\n";
    echo "   ⏳ Pendientes: {$stats['pending']}\n";
    echo "   🔄 En progreso: {$stats['in_progress']}\n";
    echo "   ✅ Completadas: {$stats['completed']}\n";
    echo "   ❌ Canceladas: {$stats['cancelled']}\n";
    echo "   ⚠️  Vencidas: {$stats['overdue']}\n\n";
}

// 3. Crear nueva tarea
echo "3️⃣  Creando nueva tarea:\n";
$newTask = [
    'title' => 'Implementar tests unitarios',
    'description' => 'Crear tests unitarios para todos los casos de uso de la aplicación Clean Architecture',
    'priority' => 'high',
    'due_date' => date('Y-m-d H:i:s', strtotime('+7 days'))
];

$response = makeRequest("$baseUrl/tasks", 'POST', $newTask);
echo "   Status: {$response['code']}\n";
if ($response['body']['success']) {
    $createdTask = $response['body']['data'];
    echo "   ✅ Tarea creada con ID: {$createdTask['id']}\n";
    echo "   📝 Título: {$createdTask['title']}\n\n";
    $newTaskId = $createdTask['id'];
} else {
    echo "   ❌ Error: " . ($response['body']['error'] ?? 'Error desconocido') . "\n\n";
    $newTaskId = null;
}

// 4. Obtener tarea específica
if ($newTaskId) {
    echo "4️⃣  Obteniendo tarea específica (ID: $newTaskId):\n";
    $response = makeRequest("$baseUrl/tasks/$newTaskId");
    echo "   Status: {$response['code']}\n";
    if ($response['body']['success']) {
        $task = $response['body']['data'];
        echo "   📝 Título: {$task['title']}\n";
        echo "   📋 Estado: {$task['status']}\n";
        echo "   🎯 Prioridad: {$task['priority']}\n\n";
    }
}

// 5. Actualizar tarea
if ($newTaskId) {
    echo "5️⃣  Actualizando tarea (marcando como en progreso):\n";
    $updateData = [
        'status' => 'in_progress',
        'description' => 'Crear tests unitarios para todos los casos de uso de la aplicación Clean Architecture. ACTUALIZADO: Comenzando con los tests de entidades.'
    ];
    
    $response = makeRequest("$baseUrl/tasks/$newTaskId", 'PUT', $updateData);
    echo "   Status: {$response['code']}\n";
    if ($response['body']['success']) {
        echo "   ✅ Tarea actualizada exitosamente\n";
        echo "   📋 Nuevo estado: {$response['body']['data']['status']}\n\n";
    } else {
        echo "   ❌ Error: " . ($response['body']['error'] ?? 'Error desconocido') . "\n\n";
    }
}

// 6. Listar tareas vencidas
echo "6️⃣  Listando tareas vencidas:\n";
$response = makeRequest("$baseUrl/tasks/overdue");
echo "   Status: {$response['code']}\n";
echo "   Tareas vencidas: " . ($response['body']['count'] ?? 0) . "\n";
if ($response['body']['success'] && $response['body']['count'] > 0) {
    foreach ($response['body']['data'] as $task) {
        echo "   ⚠️  {$task['title']} (Vence: {$task['due_date']})\n";
    }
}
echo "\n";

// 7. Listar tareas por estado
echo "7️⃣  Listando tareas pendientes:\n";
$response = makeRequest("$baseUrl/tasks?status=pending");
echo "   Status: {$response['code']}\n";
echo "   Tareas pendientes: " . ($response['body']['count'] ?? 0) . "\n\n";

// 8. Intentar eliminar tarea (esto debería fallar si está completada)
if ($newTaskId) {
    echo "8️⃣  Intentando eliminar tarea:\n";
    $response = makeRequest("$baseUrl/tasks/$newTaskId", 'DELETE');
    echo "   Status: {$response['code']}\n";
    if ($response['body']['success']) {
        echo "   ✅ Tarea eliminada exitosamente\n\n";
    } else {
        echo "   ❌ Error: " . ($response['body']['error'] ?? 'Error desconocido') . "\n\n";
    }
}

// 9. Estadísticas finales
echo "9️⃣  Estadísticas finales:\n";
$response = makeRequest("$baseUrl/tasks/statistics");
if ($response['body']['success']) {
    $stats = $response['body']['data'];
    echo "   📊 Total final: {$stats['total']}\n";
    echo "   ⏳ Pendientes: {$stats['pending']}\n";
    echo "   🔄 En progreso: {$stats['in_progress']}\n";
    echo "   ✅ Completadas: {$stats['completed']}\n";
}

echo "\n🎉 Pruebas completadas!\n";
echo "💡 Tip: Puedes usar herramientas como Postman o curl para probar la API manualmente.\n";


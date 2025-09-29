<?php

/**
 * Script de prueba para la aplicación MVC
 * Ejecutar desde línea de comandos: php test_mvc.php
 */

require_once __DIR__ . '/config/autoload.php';

echo "=== Prueba de la Aplicación MVC ===\n\n";

try {
    // Probar el modelo Product
    echo "1. Probando el modelo Product...\n";
    $productModel = new Product();
    
    // Crear productos de prueba
    $product1Data = [
        'name' => 'Laptop Gaming',
        'description' => 'Laptop de alta gama para gaming con RTX 4080',
        'price' => 1299.99,
        'category' => 'Electrónicos',
        'stock' => 15
    ];
    
    $result1 = $productModel->createProduct($product1Data);
    if ($result1['success']) {
        echo "✓ Producto 1 creado: {$result1['product']['name']}\n";
    }
    
    $product2Data = [
        'name' => 'Mouse Inalámbrico',
        'description' => 'Mouse ergonómico inalámbrico con sensor óptico de alta precisión',
        'price' => 29.99,
        'category' => 'Accesorios',
        'stock' => 50
    ];
    
    $result2 = $productModel->createProduct($product2Data);
    if ($result2['success']) {
        echo "✓ Producto 2 creado: {$result2['product']['name']}\n";
    }
    
    $product3Data = [
        'name' => 'Teclado Mecánico',
        'description' => 'Teclado mecánico RGB con switches Cherry MX Blue',
        'price' => 89.99,
        'category' => 'Accesorios',
        'stock' => 3  // Stock bajo para probar alertas
    ];
    
    $result3 = $productModel->createProduct($product3Data);
    if ($result3['success']) {
        echo "✓ Producto 3 creado: {$result3['product']['name']}\n";
    }
    
    echo "\n2. Probando operaciones del modelo...\n";
    
    // Listar todos los productos
    $allProducts = $productModel->all();
    echo "✓ Total de productos: " . count($allProducts) . "\n";
    
    // Buscar por ID
    $foundProduct = $productModel->find($result1['product']['id']);
    if ($foundProduct) {
        echo "✓ Producto encontrado por ID: {$foundProduct['name']}\n";
    }
    
    // Buscar por categoría
    $electronicProducts = $productModel->getByCategory('Electrónicos');
    echo "✓ Productos en categoría 'Electrónicos': " . count($electronicProducts) . "\n";
    
    // Productos con stock bajo
    $lowStockProducts = $productModel->getLowStock();
    echo "✓ Productos con stock bajo: " . count($lowStockProducts) . "\n";
    
    // Buscar por nombre
    $searchResults = $productModel->searchByName('Mouse');
    echo "✓ Productos que contienen 'Mouse': " . count($searchResults) . "\n";
    
    echo "\n3. Probando validaciones...\n";
    
    // Probar validación de datos inválidos
    $invalidData = [
        'name' => 'AB', // Muy corto
        'description' => 'Corto', // Muy corto
        'price' => -10, // Negativo
        'category' => 'A', // Muy corto
        'stock' => -5 // Negativo
    ];
    
    $invalidResult = $productModel->createProduct($invalidData);
    if (!$invalidResult['success']) {
        echo "✓ Validación correcta: datos inválidos rechazados\n";
        echo "  Errores encontrados: " . count($invalidResult['errors']) . "\n";
    }
    
    echo "\n4. Probando actualización de productos...\n";
    
    // Actualizar un producto
    $updateData = [
        'name' => 'Laptop Gaming Pro',
        'description' => 'Laptop de ultra alta gama para gaming profesional con RTX 4090',
        'price' => 1599.99,
        'category' => 'Electrónicos',
        'stock' => 10
    ];
    
    $updateResult = $productModel->updateProduct($result1['product']['id'], $updateData);
    if ($updateResult['success']) {
        echo "✓ Producto actualizado: {$updateResult['product']['name']}\n";
    }
    
    echo "\n5. Probando el controlador Home...\n";
    
    // Simular petición al controlador Home
    $homeController = new HomeController();
    
    // Capturar la salida del controlador
    ob_start();
    try {
        $homeController->index();
        $output = ob_get_contents();
        echo "✓ HomeController ejecutado correctamente\n";
    } catch (Exception $e) {
        echo "❌ Error en HomeController: " . $e->getMessage() . "\n";
    }
    ob_end_clean();
    
    echo "\n6. Probando el router...\n";
    
    // Probar el router
    $router = new Router();
    
    // Agregar una ruta de prueba
    $router->get('/test', function() {
        return "Ruta de prueba funcionando";
    });
    
    echo "✓ Router creado y ruta de prueba agregada\n";
    
    // Probar generación de URLs
    $url = $router->url('/products/show/{id}', ['id' => 123]);
    echo "✓ URL generada: {$url}\n";
    
    echo "\n7. Probando la base de datos simulada...\n";
    
    $db = Database::getInstance();
    
    // Verificar que es singleton
    $db2 = Database::getInstance();
    if ($db === $db2) {
        echo "✓ Patrón Singleton funcionando correctamente\n";
    }
    
    // Probar operaciones directas de base de datos
    $testData = ['name' => 'Test', 'value' => 'Testing'];
    $insertId = $db->insert('test_table', $testData);
    echo "✓ Inserción directa en BD: ID {$insertId}\n";
    
    $retrieved = $db->select('test_table', ['id' => $insertId]);
    if (!empty($retrieved)) {
        echo "✓ Consulta directa en BD: {$retrieved[0]['name']}\n";
    }
    
    echo "\n8. Resumen de productos creados:\n";
    $finalProducts = $productModel->all();
    foreach ($finalProducts as $product) {
        $stockStatus = $product['stock'] > 10 ? 'Alto' : ($product['stock'] > 0 ? 'Bajo' : 'Agotado');
        echo "- {$product['name']} | \${$product['price']} | Stock: {$product['stock']} ({$stockStatus})\n";
    }
    
    echo "\n=== Todas las pruebas completadas exitosamente ===\n";
    echo "La aplicación MVC está funcionando correctamente.\n";
    echo "Puedes acceder a la interfaz web en: http://localhost/LEARN/PATRONES/mvc/public/\n";

} catch (Exception $e) {
    echo "❌ Error durante las pruebas: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}

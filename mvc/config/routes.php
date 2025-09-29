<?php

// Definir todas las rutas de la aplicación

// Ruta de inicio
$router->get('/', 'Home@index');

// Rutas de productos
$router->get('/products', 'Product@index');
$router->get('/products/create', 'Product@create');
$router->post('/products/store', 'Product@store');
$router->get('/products/show/{id}', 'Product@show');
$router->get('/products/edit/{id}', 'Product@edit');
$router->put('/products/update/{id}', 'Product@update');
$router->delete('/products/delete/{id}', 'Product@delete');

// Ruta API para productos (opcional)
$router->get('/api/products', 'Product@api');

// Rutas adicionales que podrías agregar:
// $router->get('/products/category/{category}', 'Product@byCategory');
// $router->get('/products/search', 'Product@search');
// $router->get('/products/low-stock', 'Product@lowStock');

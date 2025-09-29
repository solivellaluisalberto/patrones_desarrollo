<?php

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'description', 'price', 'category', 'stock'];

    public function getValidationRules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:500',
            'price' => 'required|numeric',
            'category' => 'required|min:3|max:50',
            'stock' => 'required|numeric'
        ];
    }

    public function createProduct($data)
    {
        $errors = $this->validate($data, $this->getValidationRules());
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Validaciones adicionales específicas del producto
        if ($data['price'] < 0) {
            return ['success' => false, 'errors' => ['price' => 'El precio no puede ser negativo']];
        }

        if ($data['stock'] < 0) {
            return ['success' => false, 'errors' => ['stock' => 'El stock no puede ser negativo']];
        }

        $product = $this->create($data);
        return ['success' => true, 'product' => $product];
    }

    public function updateProduct($id, $data)
    {
        $errors = $this->validate($data, $this->getValidationRules());
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Validaciones adicionales específicas del producto
        if ($data['price'] < 0) {
            return ['success' => false, 'errors' => ['price' => 'El precio no puede ser negativo']];
        }

        if ($data['stock'] < 0) {
            return ['success' => false, 'errors' => ['stock' => 'El stock no puede ser negativo']];
        }

        $product = $this->update($id, $data);
        
        if (!$product) {
            return ['success' => false, 'errors' => ['general' => 'Producto no encontrado']];
        }

        return ['success' => true, 'product' => $product];
    }

    public function getByCategory($category)
    {
        return $this->where(['category' => $category]);
    }

    public function getLowStock($threshold = 10)
    {
        $products = $this->all();
        return array_filter($products, function($product) use ($threshold) {
            return $product['stock'] <= $threshold;
        });
    }

    public function searchByName($name)
    {
        $products = $this->all();
        return array_filter($products, function($product) use ($name) {
            return stripos($product['name'], $name) !== false;
        });
    }

    public function getFormattedPrice($product)
    {
        return '$' . number_format($product['price'], 2);
    }

    public function isInStock($product)
    {
        return $product['stock'] > 0;
    }
}

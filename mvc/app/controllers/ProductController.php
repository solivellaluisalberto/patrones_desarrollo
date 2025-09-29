<?php

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        $products = $this->productModel->all();
        
        $this->view('products.index', [
            'title' => 'Lista de Productos - MVC',
            'products' => $products
        ]);
    }

    public function show($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->redirect('/LEARN/PATRONES/mvc/public/products', 'Producto no encontrado', 'error');
        }

        $this->view('products.show', [
            'title' => 'Producto: ' . $product['name'],
            'product' => $product
        ]);
    }

    public function create()
    {
        $this->clearOldInput();
        
        $this->view('products.create', [
            'title' => 'Crear Producto - MVC',
            'errors' => [],
            'old' => []
        ]);
    }

    public function store()
    {
        $input = $this->getInput();
        
        // Validar datos
        $rules = [
            'name' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:500',
            'price' => 'required|numeric',
            'category' => 'required|min:3|max:50',
            'stock' => 'required|numeric'
        ];

        $errors = $this->validate($input, $rules);

        // Validaciones adicionales
        if (empty($errors)) {
            if ($input['price'] < 0) {
                $errors['price'] = 'El precio no puede ser negativo';
            }
            if ($input['stock'] < 0) {
                $errors['stock'] = 'El stock no puede ser negativo';
            }
        }

        if (!empty($errors)) {
            $this->withInput($input);
            $this->view('products.create', [
                'title' => 'Crear Producto - MVC',
                'errors' => $errors,
                'old' => $input
            ]);
            return;
        }

        // Crear producto
        $result = $this->productModel->createProduct($input);
        
        if ($result['success']) {
            $this->clearOldInput();
            $this->redirect(
                '/LEARN/PATRONES/mvc/public/products', 
                'Producto creado exitosamente'
            );
        } else {
            $this->withInput($input);
            $this->view('products.create', [
                'title' => 'Crear Producto - MVC',
                'errors' => $result['errors'],
                'old' => $input
            ]);
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->redirect('/LEARN/PATRONES/mvc/public/products', 'Producto no encontrado', 'error');
        }

        $this->clearOldInput();

        $this->view('products.edit', [
            'title' => 'Editar Producto - MVC',
            'product' => $product,
            'errors' => [],
            'old' => []
        ]);
    }

    public function update($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->redirect('/LEARN/PATRONES/mvc/public/products', 'Producto no encontrado', 'error');
        }

        $input = $this->getInput();
        
        // Validar datos
        $rules = [
            'name' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:500',
            'price' => 'required|numeric',
            'category' => 'required|min:3|max:50',
            'stock' => 'required|numeric'
        ];

        $errors = $this->validate($input, $rules);

        // Validaciones adicionales
        if (empty($errors)) {
            if ($input['price'] < 0) {
                $errors['price'] = 'El precio no puede ser negativo';
            }
            if ($input['stock'] < 0) {
                $errors['stock'] = 'El stock no puede ser negativo';
            }
        }

        if (!empty($errors)) {
            $this->withInput($input);
            $this->view('products.edit', [
                'title' => 'Editar Producto - MVC',
                'product' => $product,
                'errors' => $errors,
                'old' => $input
            ]);
            return;
        }

        // Actualizar producto
        $result = $this->productModel->updateProduct($id, $input);
        
        if ($result['success']) {
            $this->clearOldInput();
            $this->redirect(
                '/LEARN/PATRONES/mvc/public/products/show/' . $id, 
                'Producto actualizado exitosamente'
            );
        } else {
            $this->withInput($input);
            $this->view('products.edit', [
                'title' => 'Editar Producto - MVC',
                'product' => $product,
                'errors' => $result['errors'],
                'old' => $input
            ]);
        }
    }

    public function delete($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->redirect('/LEARN/PATRONES/mvc/public/products', 'Producto no encontrado', 'error');
        }

        $deleted = $this->productModel->delete($id);
        
        if ($deleted) {
            $this->redirect(
                '/LEARN/PATRONES/mvc/public/products', 
                'Producto eliminado exitosamente'
            );
        } else {
            $this->redirect(
                '/LEARN/PATRONES/mvc/public/products', 
                'Error al eliminar el producto', 
                'error'
            );
        }
    }

    // Método para API JSON (opcional)
    public function api()
    {
        $products = $this->productModel->all();
        $this->json($products);
    }
}

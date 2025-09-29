<?php

class HomeController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        // Obtener estadísticas para la página de inicio
        $products = $this->productModel->all();
        
        $totalProducts = count($products);
        $inStockProducts = count(array_filter($products, function($product) {
            return $product['stock'] > 0;
        }));
        $lowStockProducts = count($this->productModel->getLowStock());
        
        // Obtener categorías únicas
        $categories = array_unique(array_column($products, 'category'));
        $totalCategories = count($categories);

        $this->view('home.index', [
            'title' => 'Inicio - Aplicación MVC',
            'totalProducts' => $totalProducts,
            'inStockProducts' => $inStockProducts,
            'lowStockProducts' => $lowStockProducts,
            'totalCategories' => $totalCategories
        ]);
    }
}

<div class="row">
    <div class="col-12">
        <div class="jumbotron bg-primary text-white p-5 rounded mb-4">
            <h1 class="display-4">
                <i class="fas fa-store"></i> Bienvenido a la Tienda MVC
            </h1>
            <p class="lead">
                Esta es una aplicación de ejemplo que demuestra el patrón Modelo-Vista-Controlador (MVC) en PHP.
            </p>
            <hr class="my-4">
            <p>
                Gestiona productos de manera sencilla con operaciones CRUD completas.
            </p>
            <a class="btn btn-light btn-lg" href="/LEARN/PATRONES/mvc/public/products" role="button">
                <i class="fas fa-box"></i> Ver Productos
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-database fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Modelo</h5>
                <p class="card-text">
                    Maneja la lógica de datos y las operaciones de base de datos. 
                    Incluye validaciones y reglas de negocio.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-eye fa-3x text-success mb-3"></i>
                <h5 class="card-title">Vista</h5>
                <p class="card-text">
                    Presenta la información al usuario de manera clara y atractiva.
                    Separada completamente de la lógica de negocio.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Controlador</h5>
                <p class="card-text">
                    Actúa como intermediario entre el Modelo y la Vista.
                    Maneja las peticiones del usuario y coordina las respuestas.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h3><i class="fas fa-chart-bar"></i> Estadísticas Rápidas</h3>
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-box fa-2x mb-2"></i>
                        <h4><?= $totalProducts ?? 0 ?></h4>
                        <p>Total Productos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h4><?= $inStockProducts ?? 0 ?></h4>
                        <p>En Stock</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h4><?= $lowStockProducts ?? 0 ?></h4>
                        <p>Stock Bajo</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-tags fa-2x mb-2"></i>
                        <h4><?= $totalCategories ?? 0 ?></h4>
                        <p>Categorías</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

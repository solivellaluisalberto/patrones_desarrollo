<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-eye"></i> Detalles del Producto</h4>
                <div class="btn-group">
                    <a href="/LEARN/PATRONES/mvc/public/products/edit/<?= $product['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" 
                            onclick="confirmDelete(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="text-muted mb-3"><?= htmlspecialchars($product['description']) ?></p>
                        
                        <div class="mb-3">
                            <strong>Categoría:</strong>
                            <span class="badge bg-secondary ms-2">
                                <?= htmlspecialchars($product['category']) ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Precio:</strong>
                            <span class="h4 text-success ms-2">
                                $<?= number_format($product['price'], 2) ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Stock:</strong>
                            <span class="badge <?= $product['stock'] > 10 ? 'bg-success' : ($product['stock'] > 0 ? 'bg-warning' : 'bg-danger') ?> ms-2">
                                <?= $product['stock'] ?> unidades
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Estado:</strong>
                            <?php if ($product['stock'] > 0): ?>
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check"></i> Disponible
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger ms-2">
                                    <i class="fas fa-times"></i> Agotado
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Información del Sistema</h6>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <strong>ID:</strong> <?= $product['id'] ?><br>
                                        <strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($product['created_at'])) ?><br>
                                        <strong>Actualizado:</strong> <?= date('d/m/Y H:i', strtotime($product['updated_at'])) ?>
                                    </small>
                                </p>
                            </div>
                        </div>

                        <?php if ($product['stock'] <= 10 && $product['stock'] > 0): ?>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Stock Bajo:</strong> Quedan pocas unidades disponibles.
                            </div>
                        <?php elseif ($product['stock'] == 0): ?>
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-times-circle"></i>
                                <strong>Sin Stock:</strong> Este producto está agotado.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar"></i> Estadísticas</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                        <h4 class="text-success">$<?= number_format($product['price'], 2) ?></h4>
                        <small class="text-muted">Precio Unitario</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <i class="fas fa-boxes fa-2x text-info"></i>
                        <h4 class="text-info"><?= $product['stock'] ?></h4>
                        <small class="text-muted">Unidades en Stock</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <i class="fas fa-calculator fa-2x text-warning"></i>
                        <h4 class="text-warning">$<?= number_format($product['price'] * $product['stock'], 2) ?></h4>
                        <small class="text-muted">Valor Total en Inventario</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-cogs"></i> Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/LEARN/PATRONES/mvc/public/products/edit/<?= $product['id'] ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Producto
                    </a>
                    <a href="/LEARN/PATRONES/mvc/public/products" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Ver Todos los Productos
                    </a>
                    <a href="/LEARN/PATRONES/mvc/public/products/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el producto <strong id="productName"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('productName').textContent = name;
    document.getElementById('deleteForm').action = '/LEARN/PATRONES/mvc/public/products/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

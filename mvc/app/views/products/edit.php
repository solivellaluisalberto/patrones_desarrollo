<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Editar Producto</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/LEARN/PATRONES/mvc/public/products/update/<?= $product['id'] ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre del Producto *</label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= htmlspecialchars($old['name'] ?? $product['name']) ?>"
                                       placeholder="Ingresa el nombre del producto">
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['name'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoría *</label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['category']) ? 'is-invalid' : '' ?>" 
                                       id="category" 
                                       name="category" 
                                       value="<?= htmlspecialchars($old['category'] ?? $product['category']) ?>"
                                       placeholder="Ej: Electrónicos, Ropa, etc.">
                                <?php if (isset($errors['category'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['category'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción *</label>
                        <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Describe las características del producto"><?= htmlspecialchars($old['description'] ?? $product['description']) ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['description'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Precio *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                           id="price" 
                                           name="price" 
                                           step="0.01"
                                           min="0"
                                           value="<?= htmlspecialchars($old['price'] ?? $product['price']) ?>"
                                           placeholder="0.00">
                                    <?php if (isset($errors['price'])): ?>
                                        <div class="invalid-feedback">
                                            <?= $errors['price'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" 
                                       class="form-control <?= isset($errors['stock']) ? 'is-invalid' : '' ?>" 
                                       id="stock" 
                                       name="stock" 
                                       min="0"
                                       value="<?= htmlspecialchars($old['stock'] ?? $product['stock']) ?>"
                                       placeholder="Cantidad disponible">
                                <?php if (isset($errors['stock'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['stock'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Información del producto:</strong><br>
                        <small>
                            ID: <?= $product['id'] ?> | 
                            Creado: <?= date('d/m/Y H:i', strtotime($product['created_at'])) ?> | 
                            Última actualización: <?= date('d/m/Y H:i', strtotime($product['updated_at'])) ?>
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="/LEARN/PATRONES/mvc/public/products/show/<?= $product['id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <a href="/LEARN/PATRONES/mvc/public/products" class="btn btn-outline-secondary">
                                <i class="fas fa-list"></i> Lista de Productos
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Nota:</strong> Los cambios realizados se aplicarán inmediatamente. Asegúrate de revisar toda la información antes de guardar.
        </div>
    </div>
</div>

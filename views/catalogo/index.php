<?php require_once 'views/layouts/navbar.php'; ?>

<style>
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
}

.search-container {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-color);
}

.search-box {
    display: flex;
    gap: 12px;
}

.search-input {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.book-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
}

.book-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.book-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin-bottom: 15px;
}

.book-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.book-author {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 15px;
}

.book-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin: 15px 0;
    padding: 15px;
    background: var(--bg-secondary);
    border-radius: 10px;
}

.book-detail {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
}

.book-detail-label {
    color: var(--text-secondary);
    font-weight: 600;
}

.book-detail-value {
    color: var(--text-primary);
    font-weight: 500;
}

.availability {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
}

.availability-text {
    font-weight: 600;
    font-size: 0.9rem;
}

.btn-solicitar {
    width: 100%;
    margin-top: 15px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-solicitar:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-solicitar:disabled {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    cursor: not-allowed;
    transform: none;
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #10b981;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #ef4444;
}

.alert-icon {
    font-size: 1.5rem;
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"> Catálogo de Libros</h1>
        <p class="page-subtitle">Explora nuestra colección</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?></div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        </div>
    <?php endif; ?>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="ruta" value="catalogo">
            <div class="search-box">
                <input type="text" name="buscar" class="search-input"
                       placeholder=" Buscar por título, autor, ISBN o categoría..."
                       value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <?php if (isset($_GET['buscar'])): ?>
                    <a href="index.php?ruta=catalogo" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="books-grid">
        <?php if (empty($libros)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-book" style="font-size: 4rem; color: var(--text-secondary); margin-bottom: 20px; display: block;"></i>
                <h3 style="color: var(--text-primary); margin-bottom: 10px;">No se encontraron libros</h3>
                <p style="color: var(--text-secondary);">Intenta con otra búsqueda</p>
            </div>
        <?php else: ?>
            <?php foreach ($libros as $libro): ?>
                <div class="book-card">
                    <div class="book-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="book-title"><?php echo htmlspecialchars($libro['titulo']); ?></div>
                    <div class="book-author">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($libro['autor']); ?>
                    </div>

                    <div class="book-details">
                        <div class="book-detail">
                            <span class="book-detail-label">ISBN:</span>
                            <span class="book-detail-value"><?php echo htmlspecialchars($libro['isbn']); ?></span>
                        </div>
                        <div class="book-detail">
                            <span class="book-detail-label">Categoría:</span>
                            <span class="book-detail-value"><?php echo htmlspecialchars($libro['categoria']); ?></span>
                        </div>
                        <div class="book-detail">
                            <span class="book-detail-label">Año:</span>
                            <span class="book-detail-value"><?php echo $libro['anio_publicacion'] ?? 'N/A'; ?></span>
                        </div>
                    </div>

                    <div class="availability">
                        <?php if ($libro['estado'] === 'disponible' && $libro['cantidad_disponible'] > 0): ?>
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Disponible
                            </span>
                            <span class="availability-text" style="color: var(--primary);">
                                <?php echo $libro['cantidad_disponible']; ?> disponibles
                            </span>
                        <?php else: ?>
                            <span class="badge badge-danger">
                                <i class="fas fa-times"></i> No disponible
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($libro['estado'] === 'disponible' && $libro['cantidad_disponible'] > 0): ?>
                        <form method="POST" action="index.php?ruta=solicitar" style="margin: 0;">
                            <input type="hidden" name="libro_id" value="<?php echo $libro['id']; ?>">
                            <button type="submit" class="btn-solicitar" onclick="return confirm('¿Deseas solicitar este libro en préstamo?')">
                                <i class="fas fa-hand-holding-heart"></i>
                                Solicitar Préstamo
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn-solicitar" disabled>
                            <i class="fas fa-ban"></i>
                            No Disponible
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
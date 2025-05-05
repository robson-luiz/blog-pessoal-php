<?php
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $connection->prepare("SELECT c.*, COUNT(p.id) as post_count 
                          FROM categories c 
                          LEFT JOIN posts p ON c.id = p.category_id 
                          WHERE c.id = ? 
                          GROUP BY c.id");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        header('Location: categories.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Erro ao buscar categoria: " . $e->getMessage();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Visualizar Categoria</h1>
    <a href="categories.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger">
    <?php echo $error; ?>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <p class="mb-1"><strong>Nome da Categoria: </strong><?php echo nl2br(htmlspecialchars($category['name'])); ?></p>
        </div>
        <div class="mb-3">
            <p class="mb-1"><strong>Total de Posts:</strong> <?php echo $category['post_count']; ?></p>
            <p class="mb-1"><strong>Data de Criação:</strong> <?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?> 
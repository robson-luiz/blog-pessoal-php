<?php
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: posts.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $connection->prepare("SELECT p.*, c.name as category_name 
                          FROM posts p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header('Location: posts.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Erro ao buscar post: " . $e->getMessage();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Visualizar Post</h1>
    <a href="posts.php" class="btn btn-secondary">
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
        <?php if (!empty($post['image'])): ?>
            <div class="mb-3 text-center">
                <img src="../uploads/posts/<?php echo htmlspecialchars($post['image']); ?>" alt="Imagem do post" class="img-fluid rounded" style="max-width: 400px; max-height: 300px; object-fit: cover;">
            </div>
        <?php endif; ?>
        <h2 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h2>
        <div class="text-muted mb-3">
            <small>
                <i class="fas fa-folder"></i> <?php echo htmlspecialchars($post['category_name']); ?> |
                <i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
            </small>
        </div>
        <div class="post-content">
            <?php echo $post['content']; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?> 
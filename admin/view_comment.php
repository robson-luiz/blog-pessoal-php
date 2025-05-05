<?php
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: comments.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $connection->prepare("SELECT c.*, p.title as post_title 
                          FROM comments c 
                          LEFT JOIN posts p ON c.post_id = p.id 
                          WHERE c.id = ?");
    $stmt->execute([$id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        header('Location: comments.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Erro ao buscar comentário: " . $e->getMessage();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Visualizar Comentário</h1>
    <a href="comments.php" class="btn btn-secondary">
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
            <h5 class="card-title">Post: <?php echo htmlspecialchars($comment['post_title']); ?></h5>
        </div>
        <div class="mb-3">
            <p class="mb-1"><strong>Autor:</strong> <?php echo htmlspecialchars($comment['author']); ?></p>
            <p class="mb-1"><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></p>
            <p class="mb-1"><strong>Status:</strong> 
                <span class="badge bg-<?php echo $comment['status'] === 'approved' ? 'success' : 'warning'; ?>">
                    <?php echo $comment['status'] === 'approved' ? 'Aprovado' : 'Pendente'; ?>
                </span>
            </p>
        </div>
        <div class="mb-3">
            <h6 class="card-subtitle mb-2">Comentário:</h6>
            <div class="border p-3 rounded bg-light">
                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?> 
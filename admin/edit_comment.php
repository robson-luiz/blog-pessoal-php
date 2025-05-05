<?php
require_once '../config.php';
require_once '../includes/Comment.php';
require_once 'includes/header.php';

// Criar instância da classe Comment
$comment = new Comment($connection);

if (!isset($_GET['id'])) {
    header('Location: comments.php');
    exit;
}

$commentId = (int)$_GET['id'];
$commentData = $comment->getCommentById($commentId);

if (!$commentData) {
    header('Location: comments.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = cleanInput($_POST['author']);
    $content = cleanInput($_POST['content']);
    $status = cleanInput($_POST['status']);
    
    if ($comment->update($commentId, $author, $content, $status)) {
        header('Location: comments.php?approved=1');
        exit;
    } else {
        $error = "Erro ao atualizar o comentário. Tente novamente.";
    }
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Editar Comentário</h1>
        <a href="comments.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="author" class="form-label">Autor</label>
                    <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($commentData['author']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Comentário</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($commentData['content']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="pending" <?php echo $commentData['status'] == 'pending' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="approved" <?php echo $commentData['status'] == 'approved' ? 'selected' : ''; ?>>Aprovado</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
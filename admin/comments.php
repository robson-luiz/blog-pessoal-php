<?php
require_once 'includes/header.php';
require_once '../includes/Comment.php';

// Criar instância da classe Comment
$comment = new Comment($connection);

// Obter todos os comentários
$comments = $comment->getAllComments();

// Verificar se há uma mensagem de sucesso para exibir
if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
        Comentário excluído com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['approved']) && $_GET['approved'] == 1): ?>
    <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
        Comentário aprovado com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<script>
    // Esconde o alerta de sucesso após 4 segundos
    setTimeout(function() {
        var alert = document.getElementById('alert-success');
        if(alert) alert.style.display = 'none';
    }, 4000);
</script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gerenciar Comentários</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Autor</th>
                        <th>Comentário</th>
                        <th>Post</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['author']); ?></td>
                        <td><?php echo substr(htmlspecialchars($comment['content']), 0, 100) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($comment['post_title']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($comment['created_at'])); ?></td>
                        <td>
                            <?php if ($comment['status'] == 'approved'): ?>
                                <span class="badge bg-success">Aprovado</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>                            
                            <a href="delete_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
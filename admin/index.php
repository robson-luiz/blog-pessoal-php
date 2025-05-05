<?php
require_once 'includes/header.php';
require_once '../includes/Post.php';
require_once '../includes/Comment.php';

// Criar instâncias das classes
$post = new Post($connection);
$comment = new Comment($connection);

// Obter estatísticas
$totalPosts = $post->getTotalPosts();
$totalComments = $comment->getTotalComments();
$recentPosts = $post->getRecentPosts(5);
$recentComments = $comment->getRecentComments(5);
?>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0"><?php echo $totalPosts; ?></h3>
                        <div>Total de Posts</div>
                    </div>
                    <i class="fas fa-file-alt fa-2x"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="posts.php">Ver Detalhes</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0"><?php echo $totalComments; ?></h3>
                        <div>Total de Comentários</div>
                    </div>
                    <i class="fas fa-comments fa-2x"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="comments.php">Ver Detalhes</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-file-alt me-1"></i>
                Posts Recentes
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPosts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-comments me-1"></i>
                Comentários Recentes
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Autor</th>
                                <th>Comentário</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentComments as $comment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($comment['author']); ?></td>
                                <td><?php echo substr(htmlspecialchars($comment['content']), 0, 50) . '...'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($comment['created_at'])); ?></td>
                                <td>
                                    <a href="edit_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
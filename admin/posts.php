<?php
require_once 'includes/header.php';

// Paginação
$posts_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Buscar total de posts
$total_stmt = $connection->query("SELECT COUNT(*) as total FROM posts");
$total_posts = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Obter posts da página atual
$stmt = $connection->prepare("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['success'])):
    ?>
    <div class="alert alert-success" id="success-message">
        <?php
        if ($_GET['success'] == 1) {
            echo "Postagem salva com sucesso!";
        } elseif ($_GET['success'] == 2) {
            echo "Postagem editada com sucesso!";
        } elseif ($_GET['success'] == 3) {
            echo "Postagem excluída com sucesso!";
        }
        ?>
    </div>
    <script>
        setTimeout(function() {
            var url = new URL(window.location.href);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.pathname + url.search);
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gerenciar Posts</h1>
    <a href="add_post.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Post
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <?php if ($post['image']): ?>
                                <img src="../uploads/posts/<?php echo htmlspecialchars($post['image']); ?>" alt="Imagem do post" style="max-width: 50px; max-height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                        <td>
                            <?php if ($post['status'] == 'published'): ?>
                                <span class="badge bg-success">Publicado</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Rascunho</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>                            
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este post?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Paginação de posts">
            <ul class="pagination justify-content-center mt-4">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="posts.php?page=<?php echo $page - 1; ?>">Anterior</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="posts.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="posts.php?page=<?php echo $page + 1; ?>">Próxima</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
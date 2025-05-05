<?php
include 'includes/db.php';
include_once 'config.php';
include 'includes/Post.php';
include 'includes/Comment.php';
include 'includes/header.php';

// Verificar se o ID da categoria foi fornecido
if(isset($_GET['id'])) {
    $category_id = $_GET['id'];
    
    // Configuração da paginação
    $posts_per_page = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $posts_per_page;
    
    // Buscar informações da categoria
    $stmt = $connection->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($category) {
        // Buscar total de posts para paginação
        $count_stmt = $connection->prepare("
            SELECT COUNT(*) as total 
            FROM posts 
            WHERE category_id = ? AND status = 'published'
        ");
        $count_stmt->execute([$category_id]);
        $total_posts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_posts / $posts_per_page);
        
        // Buscar posts da categoria
        $query = "
            SELECT p.*, c.name as category_name 
            FROM posts p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = :category_id AND p.status = 'published' 
            ORDER BY p.created_at DESC 
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $connection->prepare($query);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        ?>
        
        <div class="container">
            <div class="row">
                <!-- Blog Entries Column -->
                <div class="col-md-8">
                    <h1 class="page-header">
                        Categoria: <?php echo htmlspecialchars($category['name']); ?>
                    </h1>
                    
                    <?php
                    if($stmt->rowCount() > 0) {
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <!-- Blog Post -->
                            <div class="card mb-4 border-0 shadow-sm rounded-4" style="overflow: hidden;">
                                <?php if (isset($row['image']) && $row['image']): ?>
                                    <div class="p-3 pb-0">
                                        <img src="uploads/posts/<?php echo htmlspecialchars($row['image']); ?>"
                                             class="card-img-top rounded-3 mx-auto d-block"
                                             alt="<?php echo htmlspecialchars($row['title']); ?>"
                                             style="width: 100%; max-width: 400px; height: 200px; object-fit: cover; margin-bottom: 1rem;">
                                    </div>
                                <?php endif; ?>
                                <div class="card-body px-4 py-3">
                                    <h5 class="card-title mb-2">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                                    </p>
                                    <p class="mb-3"><?php echo substr(strip_tags($row['content']), 0, 150) . '...'; ?></p>
                                    <a href="post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Ler mais</a>
                                </div>
                            </div>
                            <?php
                        }
                        
                        // Paginação
                        if($total_pages > 1) {
                            echo '<ul class="pagination">';
                            if($page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="category.php?id=' . $category_id . '&page=' . ($page - 1) . '">Anterior</a></li>';
                            }
                            
                            for($i = 1; $i <= $total_pages; $i++) {
                                $active = $i == $page ? 'active' : '';
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="category.php?id=' . $category_id . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                            
                            if($page < $total_pages) {
                                echo '<li class="page-item"><a class="page-link" href="category.php?id=' . $category_id . '&page=' . ($page + 1) . '">Próxima</a></li>';
                            }
                            echo '</ul>';
                        }
                    } else {
                        echo "<div class='alert alert-info'>Nenhum post encontrado nesta categoria.</div>";
                    }
                    ?>
                </div>

                <!-- Blog Sidebar Widgets Column -->
                <?php include 'includes/sidebar.php'; ?>
            </div>
            <!-- /.row -->
        </div>
        <?php
    } else {
        echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID da categoria não fornecido.</div>";
}

include 'includes/footer.php';
?>

<style>
.card .card-body p {
    margin-bottom: 1.1em;
}
</style> 
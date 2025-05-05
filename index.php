<?php
//echo "<div style='background:yellow;color:black;padding:10px;'>ESTE É O FRONT-END</div>";

include_once 'includes/db.php';
include_once 'config.php';
include 'includes/Post.php';
include 'includes/Comment.php';
include 'includes/header.php';

$posts_per_page = $config['posts_per_page'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Buscar total de posts para paginação
$count_stmt = $connection->query("SELECT COUNT(*) as total FROM posts WHERE status = 'published'");
$total_posts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Buscar posts com paginação
$query = "
    SELECT p.*, c.name as category_name 
    FROM posts p 
    JOIN categories c ON p.category_id = c.id 
    WHERE p.status = 'published' 
    ORDER BY p.created_at DESC 
    LIMIT :limit OFFSET :offset
";
$stmt = $connection->prepare($query);
$stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
?>

    <div class="container">
        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8">
                        <h1 class="page-header mb-4">
                Últimas Postagens
                        </h1>

            <?php
            if($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <!-- Blog Post -->
                    <div class="card mb-4 border-0 shadow-sm rounded-4" style="overflow: hidden;">
                        <?php if (!empty($row['image'])): ?>
                            <div class="p-3 pb-0">
                                <img src="uploads/posts/<?php echo htmlspecialchars($row['image']); ?>"
                                     class="card-img-top rounded-3 mx-auto d-block"
                                     alt="<?php echo htmlspecialchars($row['title']); ?>"
                                     style="width: 100%; max-width: 400px; height: 200px; object-fit: cover; margin-bottom: 1rem;">
                            </div>
                        <?php endif; ?>
                        <div class="card-body px-4 py-3">
                            <h2 class="h4 mb-2">
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="post-title text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </h2>
                            <p class="lead mb-2">
                                Categoria: <a href="category.php?id=<?php echo $row['category_id']; ?>" class="category-link">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </a>
                            </p>
                            <p class="text-muted mb-2"><span class="glyphicon glyphicon-time"></span> <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></p>
                            <p class="mb-3"><?php echo substr($row['content'], 0, 300) . "..."; ?></p>
                            <a class="btn btn-primary" href="post.php?id=<?php echo $row['id']; ?>">
                                Leia Mais <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
                
                // Paginação
                if($total_pages > 1) {
                    echo '<ul class="pagination">';
                    if($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page - 1) . '">Anterior</a></li>';
                    }
                    
                    for($i = 1; $i <= $total_pages; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    
                    if($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page + 1) . '">Próxima</a></li>';
                    }
                    echo '</ul>';
                }
            } else {
                echo "<div class='alert alert-info'>Nenhum post encontrado.</div>";
            }
            ?>
                </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include 'includes/sidebar.php'; ?>
        </div>
        <!-- /.row -->

        <hr>
    </div>
        
<?php include 'includes/footer.php'; ?>       

<style>
.card .card-body p {
    margin-bottom: 1.1em;
}
</style>       

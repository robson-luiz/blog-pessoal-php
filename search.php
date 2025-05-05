<?php 
require_once 'config.php';
include 'includes/Post.php';
include 'includes/Comment.php';
include 'includes/header.php';

// Configuração da paginação
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;
?>

<div class="container">
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
            if(isset($_GET['query'])){
                $search = cleanInput($_GET['query']);
                
                // Primeiro, contar o total de resultados
                $count_query = "
                    SELECT COUNT(*) as total 
                    FROM posts p 
                    JOIN categories c ON p.category_id = c.id 
                    WHERE (p.title LIKE :search 
                    OR p.content LIKE :search)
                    AND p.status = 'published'
                ";
                
                $count_stmt = $connection->prepare($count_query);
                $searchParam = "%" . $search . "%";
                $count_stmt->bindParam(':search', $searchParam);
                $count_stmt->execute();
                $total_posts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
                $total_pages = ceil($total_posts / $posts_per_page);
                
                // Agora buscar os posts com paginação
                $query = "
                    SELECT p.*, c.name as category_name 
                    FROM posts p 
                    JOIN categories c ON p.category_id = c.id 
                    WHERE (p.title LIKE :search 
                    OR p.content LIKE :search)
                    AND p.status = 'published'
                    ORDER BY p.created_at DESC
                    LIMIT :limit OFFSET :offset
                ";
                
                try {
                    $stmt = $connection->prepare($query);
                    $stmt->bindParam(':search', $searchParam);
                    $stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    if($stmt->rowCount() == 0) {
                        echo "<div class='alert alert-warning'>Nenhum resultado encontrado para: " . htmlspecialchars($search) . "</div>";
                    } else {
                        echo "<div class='alert alert-success'>Encontrados " . $total_posts . " resultados</div>";
                        
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <h2>
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="post-title">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </h2>
                            <p class="lead">
                                Categoria: <a href="category.php?id=<?php echo $row['category_id']; ?>" class="category-link">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </a>
                            </p>
                            <p><span class="glyphicon glyphicon-time"></span> <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></p>
                            <hr>
                            <p><?php echo substr(strip_tags($row['content']), 0, 150) . "..."; ?></p>
                            <a class="btn btn-primary" href="post.php?id=<?php echo $row['id']; ?>">
                                Leia Mais <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                            <hr>
                            <?php
                        }
                        
                        // Paginação
                        if($total_pages > 1) {
                            echo '<ul class="pagination justify-content-center">';
                            
                            // Link para página anterior
                            if($page > 1) {
                                echo '<li class="page-item">
                                    <a class="page-link" href="search.php?query=' . urlencode($search) . '&page=' . ($page - 1) . '">
                                        <i class="fas fa-chevron-left"></i> Anterior
                                    </a>
                                </li>';
                            }
                            
                            // Links das páginas
                            for($i = 1; $i <= $total_pages; $i++) {
                                $active = $i == $page ? 'active' : '';
                                echo '<li class="page-item ' . $active . '">
                                    <a class="page-link" href="search.php?query=' . urlencode($search) . '&page=' . $i . '">' . $i . '</a>
                                </li>';
                            }
                            
                            // Link para próxima página
                            if($page < $total_pages) {
                                echo '<li class="page-item">
                                    <a class="page-link" href="search.php?query=' . urlencode($search) . '&page=' . ($page + 1) . '">
                                        Próxima <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>';
                            }
                            
                            echo '</ul>';
                        }
                    }
                } catch(PDOException $e) {
                    echo "<div class='alert alert-danger'>Erro na pesquisa: " . $e->getMessage() . "</div>";
                }
            }
            ?>
        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <!-- /.row -->
</div>

<?php include 'includes/footer.php'; ?>       

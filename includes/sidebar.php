<!-- Blog Sidebar Widgets Column -->
<div class="col-md-4">
    <!-- Search Widget -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pesquisa</h5>
        </div>
        <div class="card-body">
            <form action="search.php" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" placeholder="Pesquisar por...">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Widget -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0 sidebar-title">Categorias</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-unstyled mb-0">
                        <?php
                        $query = "SELECT c.id, c.name, COUNT(p.id) as post_count 
                                 FROM categories c 
                                 LEFT JOIN posts p ON c.id = p.category_id 
                                 WHERE p.status = 'published' 
                                 GROUP BY c.id, c.name 
                                 HAVING post_count > 0 
                                 ORDER BY c.name";
                        $stmt = $connection->query($query);
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <li>
                                <a href="category.php?id=<?php echo $row['id']; ?>" class="text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i><?php echo htmlspecialchars($row['name']); ?>
                                    <span class="badge bg-secondary float-end"><?php echo $row['post_count']; ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Side Widget -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Sobre o Blog</h5>
        </div>
        <div class="card-body">
            <p>Este é um blog pessoal criado em 2017 e modernizado em 2025. Aqui compartilho meus conhecimentos e experiências sobre desenvolvimento web e tecnologia.</p>
        </div>
    </div>

    <!-- Recent Posts Widget -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Posts Recentes</h5>
        </div>
        <div class="card-body">
            <?php
            $recent_posts = $connection->query("SELECT id, title, created_at FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 5");
            if ($recent_posts) {
                while($row = $recent_posts->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="mb-3">
                    <h6 class="mb-1">
                        <a href="post.php?id=<?php echo $row['id']; ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </a>
                    </h6>
                    <small class="text-muted">
                        <i class="far fa-calendar-alt me-1"></i>
                        <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                    </small>
                </div>
            <?php }
            } else {
                echo "<div class='alert alert-warning'>Não foi possível carregar os posts recentes.</div>";
            }
            ?>
        </div>
    </div>
</div>
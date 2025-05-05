<?php 
require_once 'config.php';
include 'includes/Post.php';
include 'includes/Comment.php';

// Verificar se o ID do post foi fornecido
define('POST_ID', isset($_GET['id']) ? $_GET['id'] : null);
if (POST_ID) {
    $post_id = POST_ID;
    // Instanciar a classe Post
    $post = new Post($connection);
    $post->id = $post_id;
    // Ler o post específico
    if($post->readOne() && $post->status === 'published') {
        // Instanciar a classe Comment
        $comment = new Comment($connection);
        $comment->post_id = $post_id;
        // Processar o formulário de comentário ANTES do header.php
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
            $comment->author = $_POST['author'];
            $comment->content = $_POST['content'];
            if ($comment->create($post_id, $comment->author, $comment->content)) {
                header("Location: post.php?id=$post_id&comment=success");
                exit();
            }
        }
        // Ler os comentários do post
        $comments = $comment->readByPost();
    } else {
        // Se não for publicado, não mostrar o post
        include 'includes/header.php';
        echo "<div class='container'><div class='alert alert-danger mt-5'>Post não encontrado ou não publicado.</div></div>";
        include 'includes/footer.php';
        exit;
    }
}

include 'includes/header.php';

// Verificar se o ID do post foi fornecido
if(isset($_GET['id'])) {
    $post_id = $_GET['id'];
    
    // Instanciar a classe Post
    $post = new Post($connection);
    $post->id = $post_id;
    
    // Ler o post específico
    if($post->readOne()) {
        // Instanciar a classe Comment
        $comment = new Comment($connection);
        $comment->post_id = $post_id;
        
        // Ler os comentários do post
        $comments = $comment->readByPost();
        ?>
        
        <div class="container">
            <div class="row">
                <!-- Blog Post Content Column -->
                <div class="col-lg-8 mx-auto">
                    <!-- Blog Post -->
                    <div class="card mb-4 border-0 shadow-sm rounded-4" style="max-width: 800px; margin: 0 auto;">
                        <div class="card-body px-4 py-4" style="font-size: 1.15rem; line-height: 1.8;">
                            <h1 class="card-title display-5 mb-2 text-center"><?php echo htmlspecialchars($post->title); ?></h1>
                            <p class="text-muted mb-4 text-center">
                                <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($post->created_at)); ?>
                                <i class="fas fa-folder ms-2"></i> <?php echo htmlspecialchars($post->category_name); ?>
                            </p>
                            <?php if (!empty($post->image)): ?>
                                <img src="uploads/posts/<?php echo htmlspecialchars($post->image); ?>"
                                     class="card-img-top rounded shadow-sm mx-auto d-block mb-4"
                                     alt="<?php echo htmlspecialchars($post->title); ?>"
                                     style="width: 100%; max-width: 600px; height: 250px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-text fs-5 post-content">
                                <?php echo nl2br(htmlspecialchars($post->content)); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Comments -->
                    <div class="well">
                        <h4>Deixe um Comentário:</h4>
                        <?php
                        if(isset($_GET['comment']) && $_GET['comment'] === 'success') {
                            echo "<div id='comment-success' class='alert alert-success alert-dismissible fade show' role='alert'>
                                    Comentário enviado com sucesso! Aguarde aprovação.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                  </div>";
                        }
                        ?>
                        <form role="form" method="post" id="commentForm">
                            <div class="form-group mb-3">
                                <label for="author" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="author" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="content" class="form-label">Comentário</label>
                                <textarea class="form-control" name="content" rows="3" required></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" name="submit_comment" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                    </div>
                    <script>
                    // Esconder mensagem de sucesso após 4 segundos e limpar formulário
                    if (document.getElementById('comment-success')) {
                        setTimeout(function() {
                            document.getElementById('comment-success').style.display = 'none';
                            // Limpar formulário
                            var form = document.getElementById('commentForm');
                            if(form) form.reset();
                        }, 4000);
                    }
                    </script>
                    <hr>
                    <!-- Posted Comments -->
                    <?php while($comment_row = $comments->fetch()) { ?>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    Por <?php echo htmlspecialchars($comment_row['author']); ?> em 
                                    <?php echo date('d/m/Y H:i', strtotime($comment_row['created_at'])); ?>
                                </h6>
                                <p class="card-text">
                                    <?php 
                                    $content = strip_tags($comment_row['content']);
                                    echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Blog Sidebar Widgets Column -->
                    <?php include 'includes/sidebar.php'; ?>
                </div>
                <!-- /.row -->
        </div>
            <?php
        } else {
            echo "<div class='alert alert-danger'>Post não encontrado.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ID do post não fornecido.</div>";
    }

    include 'includes/footer.php';
    ?>

<style>
.post-content p {
    margin-bottom: 1.2em;
}
</style>

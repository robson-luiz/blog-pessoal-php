<?php
require_once '../config.php';
require_once '../includes/Post.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$postObj = new Post($connection);
$post = $postObj->getPostById($id);

if (!$post) {
    header('Location: posts.php');
    exit();
}

// Processar o formulário se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = cleanInput($_POST['title']);
    $content = $_POST['content'];
    $category = (int) $_POST['category_id'];
    $status = cleanInput($_POST['status']);
    $currentImage = $post['image'];
    
    // Processar upload da imagem
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $error = "Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF.";
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $error = "Arquivo muito grande. O tamanho máximo é 2MB.";
        } else {
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $uploadPath = '../uploads/posts/' . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Se houver uma imagem antiga, excluir
                if ($currentImage && file_exists('../uploads/posts/' . $currentImage)) {
                    unlink('../uploads/posts/' . $currentImage);
                }
                $currentImage = $filename;
            } else {
                $error = "Erro ao fazer upload da imagem.";
            }
        }
    }
    
    if (!isset($error)) {
        if ($postObj->update($id, $title, $content, $category, $status, $currentImage)) {
            header('Location: posts.php?success=2');
            exit();
        } else {
            $error = "Erro ao atualizar o post. Tente novamente.";
        }
    }
}

// Buscar categorias do banco
$categories = [];
$stmt = $connection->query("SELECT id, name FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Editar Post</h1>
    <a href="posts.php" class="btn btn-secondary">
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
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Imagem de Capa</label>
                <?php if ($post['image']): ?>
                    <div class="mb-2">
                        <img src="../uploads/posts/<?php echo htmlspecialchars($post['image']); ?>" alt="Imagem atual" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                <small class="text-muted">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
                <div id="imagePreview" class="mt-2" style="display: none;">
                    <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Conteúdo</label>
                <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="category_id" class="form-label">Categoria</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $post['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="draft" <?php echo ($post['status'] == 'draft') ? 'selected' : ''; ?>>Rascunho</option>
                    <option value="published" <?php echo ($post['status'] == 'published') ? 'selected' : ''; ?>>Publicado</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.style.display = 'block';
            previewImg.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?> 
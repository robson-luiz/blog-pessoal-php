<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = cleanInput($_POST['name']);
        $slug = cleanInput($_POST['slug']);
        // Verificar se o slug já existe
        $check = $connection->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
        $check->execute([$slug]);
        if ($check->fetchColumn() > 0) {
            $error = "Já existe uma categoria com esse slug. Escolha outro.";
        } else {
            $stmt = $connection->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            if ($stmt->execute([$name, $slug])) {
                $message = "Categoria adicionada com sucesso!";
            } else {
                $error = "Erro ao adicionar categoria. Tente novamente.";
            }
        }
    } elseif (isset($_POST['edit_category'])) {
        $id = (int)$_POST['id'];
        $name = cleanInput($_POST['name']);
        $slug = cleanInput($_POST['slug']);
        
        $stmt = $connection->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        if ($stmt->execute([$name, $slug, $id])) {
            $message = "Categoria atualizada com sucesso!";
        } else {
            $error = "Erro ao atualizar categoria. Tente novamente.";
        }
    }
}

// Obter todas as categorias
$stmt = $connection->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success" id="success-message">
        <?php
        if (isset($_GET['deleted'])) {
            echo 'Categoria apagada com sucesso!';
        } else {
            echo 'Categoria editada com sucesso!';
        }
        ?>
    </div>
    <script>
        setTimeout(function() {
            var url = new URL(window.location.href);
            url.searchParams.delete('success');
            url.searchParams.delete('deleted');
            window.history.replaceState({}, document.title, url.pathname + url.search);
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>

<?php if (isset($message)): ?>
<div class="alert alert-success" id="success-message-2">
    <?php echo $message; ?>
</div>
<script>
    setTimeout(function() {
        document.getElementById('success-message-2').style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gerenciar Categorias</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i> Nova Categoria
    </button>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger">
    <?php echo $error; ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Slug</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['slug']); ?></td>
                        <td>
                            <a href="view_category.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>                            
                            <a href="delete_category.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
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

<!-- Modal Adicionar Categoria -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="add_category" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modais Editar Categoria -->
<?php foreach ($categories as $category): ?>
<div class="modal fade" id="editCategoryModal<?php echo $category['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <div class="mb-3">
                        <label for="edit_name<?php echo $category['id']; ?>" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="edit_name<?php echo $category['id']; ?>" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_slug<?php echo $category['id']; ?>" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="edit_slug<?php echo $category['id']; ?>" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="edit_category" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?> 
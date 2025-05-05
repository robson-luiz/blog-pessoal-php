<?php
require_once 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$categoryId = (int)$_GET['id'];

// Buscar dados da categoria
$stmt = $connection->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    echo '<div class="alert alert-danger">Categoria não encontrada.</div>';
    require_once 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    if ($name !== '' && $slug !== '') {
        $update = $connection->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        if ($update->execute([$name, $slug, $categoryId])) {
            header('Location: categories.php?success=1');
            exit;
        } else {
            $error = "Erro ao atualizar a categoria.";
        }
    } else {
        $error = "O nome e o slug da categoria não podem ser vazios.";
    }
}
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Editar Categoria</h1>
    <a href="categories.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Nome da Categoria</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?> 
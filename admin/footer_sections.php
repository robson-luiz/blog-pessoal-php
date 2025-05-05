<?php
require_once 'includes/header.php';

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_section'])) {
        try {
            $id = (int)$_POST['id'];
            $title = cleanInput($_POST['title']);
            $content = cleanInput($_POST['content'] ?? '');
            
            // Se for a seção de redes sociais, processar os links
            if ($_POST['type'] === 'social_media') {
                $social_links = [
                    'facebook' => cleanInput($_POST['facebook'] ?? ''),
                    'twitter' => cleanInput($_POST['twitter'] ?? ''),
                    'instagram' => cleanInput($_POST['instagram'] ?? ''),
                    'linkedin' => cleanInput($_POST['linkedin'] ?? '')
                ];
                
                // Validar URLs
                foreach ($social_links as $platform => $url) {
                    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
                        throw new Exception("URL inválida para $platform");
                    }
                }
                
                $content = json_encode($social_links);
            }
            
            $stmt = $connection->prepare("UPDATE footer_sections SET title = ?, content = ? WHERE id = ?");
            if ($stmt->execute([$title, $content, $id])) {
                $message = "Seção atualizada com sucesso!";
            } else {
                throw new Exception("Erro ao atualizar a seção no banco de dados.");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Buscar seções
try {
    $sections = $connection->query("SELECT * FROM footer_sections ORDER BY type")->fetchAll();
} catch (PDOException $e) {
    $error = "Erro ao buscar seções: " . $e->getMessage();
    $sections = [];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gerenciar Seções do Footer</h1>
</div>

<?php if (isset($message)): ?>
<div id="footer-success" class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
<script>
    setTimeout(function() {
        var alert = document.getElementById('footer-success');
        if(alert) alert.style.display = 'none';
    }, 4000);
</script>

<?php if (isset($error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $error; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (empty($sections)): ?>
<div class="alert alert-warning">
    Nenhuma seção encontrada. Por favor, verifique se a tabela footer_sections existe e contém dados.
</div>
<?php else: ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <?php foreach ($sections as $section): 
                $content = $section['type'] === 'social_media' ? json_decode($section['content'], true) : $section['content'];
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo htmlspecialchars($section['title']); ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $section['id']; ?>">
                            <input type="hidden" name="type" value="<?php echo $section['type']; ?>">
                            
                            <div class="mb-3">
                                <label for="title_<?php echo $section['id']; ?>" class="form-label">Título</label>
                                <input type="text" class="form-control" id="title_<?php echo $section['id']; ?>" 
                                       name="title" value="<?php echo htmlspecialchars($section['title']); ?>" required>
                            </div>
                            
                            <?php if ($section['type'] === 'social_media'): ?>
                                <div class="mb-3">
                                    <label class="form-label">Links das Redes Sociais</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                        <input type="url" class="form-control" name="facebook" 
                                               placeholder="Link do Facebook" value="<?php echo htmlspecialchars($content['facebook'] ?? ''); ?>">
                                    </div>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        <input type="url" class="form-control" name="twitter" 
                                               placeholder="Link do Twitter" value="<?php echo htmlspecialchars($content['twitter'] ?? ''); ?>">
                                    </div>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        <input type="url" class="form-control" name="instagram" 
                                               placeholder="Link do Instagram" value="<?php echo htmlspecialchars($content['instagram'] ?? ''); ?>">
                                    </div>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                        <input type="url" class="form-control" name="linkedin" 
                                               placeholder="Link do LinkedIn" value="<?php echo htmlspecialchars($content['linkedin'] ?? ''); ?>">
                                    </div>
                                </div>
                            <?php elseif ($section['type'] !== 'quick_links'): ?>
                                <div class="mb-3">
                                    <label for="content_<?php echo $section['id']; ?>" class="form-label">Conteúdo</label>
                                    <textarea class="form-control" id="content_<?php echo $section['id']; ?>" 
                                              name="content" rows="5" required><?php echo htmlspecialchars($content); ?></textarea>
                                </div>
                            <?php endif; ?>
                            
                            <button type="submit" name="update_section" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?> 
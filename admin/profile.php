<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Verificar senha atual
    if ($currentPassword !== 'admin123') { // Substituir por verificação real no banco de dados
        $error = "Senha atual incorreta.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "As novas senhas não coincidem.";
    } elseif (strlen($newPassword) < 6) {
        $error = "A nova senha deve ter pelo menos 6 caracteres.";
    } else {
        // Atualizar senha no banco de dados
        // $stmt = $connection->prepare("UPDATE admins SET password = ? WHERE id = ?");
        // if ($stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $_SESSION['admin_id']])) {
            $message = "Senha atualizada com sucesso!";
        // } else {
        //     $error = "Erro ao atualizar a senha. Tente novamente.";
        // }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Perfil do Administrador</h1>
</div>

<?php if (isset($message)): ?>
<div class="alert alert-success">
    <?php echo $message; ?>
</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger">
    <?php echo $error; ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário</label>
                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($_SESSION['admin_username']); ?>" disabled>
            </div>
            
            <div class="mb-3">
                <label for="current_password" class="form-label">Senha Atual</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            
            <div class="mb-3">
                <label for="new_password" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar Senha
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
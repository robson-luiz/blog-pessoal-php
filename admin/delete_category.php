<?php
require_once __DIR__ . '/config.php';

if (!isset($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$categoryId = (int)$_GET['id'];

// Verificar se existem posts associados à categoria
$stmt = $connection->prepare("SELECT COUNT(*) FROM posts WHERE category_id = ?");
$stmt->execute([$categoryId]);
$postCount = $stmt->fetchColumn();

if ($postCount > 0) {
    header('Location: categories.php?error=1');
    exit;
}

// Excluir a categoria
$stmt = $connection->prepare("DELETE FROM categories WHERE id = ?");
if ($stmt->execute([$categoryId])) {
    header('Location: categories.php?success=1&deleted=1');
} else {
    header('Location: categories.php?error=2');
}
exit; 
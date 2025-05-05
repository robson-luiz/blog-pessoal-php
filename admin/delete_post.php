<?php
require_once '../config.php';
require_once '../includes/Post.php';

if (!isset($_GET['id'])) {
    header('Location: posts.php');
    exit;
}

$id = (int)$_GET['id'];
$post = new Post($connection);

if ($post->delete($id)) {
    header('Location: posts.php?success=3');
} else {
    header('Location: posts.php?error=1');
}
exit; 
<?php
require_once '../config.php';
require_once '../includes/Comment.php';

if (!isset($_GET['id'])) {
    header('Location: comments.php');
    exit;
}

$commentId = (int)$_GET['id'];
$comment = new Comment($connection);

if ($comment->delete($commentId)) {
    header('Location: comments.php?success=1');
} else {
    header('Location: comments.php?error=1');
}
exit; 
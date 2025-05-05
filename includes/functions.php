<?php
// Funções auxiliares
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function checkLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: admin/login.php");
        exit;
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function showMessage($type, $message) {
    return "<div class='alert alert-$type'>$message</div>";
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function getPostExcerpt($content, $length = 200) {
    $content = strip_tags($content);
    if (strlen($content) > $length) {
        $content = substr($content, 0, $length) . '...';
    }
    return $content;
}

?> 
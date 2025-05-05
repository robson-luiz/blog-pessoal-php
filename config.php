<?php
session_start();

// Detectar base_url automaticamente
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_url = rtrim($protocol . '://' . $host . $script_name, '/') . '/';

// Configurações do site
$config = [
    'site_name' => 'Meu Blog',
    'site_description' => 'Um blog simples e elegante',
    'admin_email' => 'admin@meublog.com',
    'posts_per_page' => 5,
    'base_url' => $base_url
];

// Incluir arquivos necessários
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Carregar configurações do banco de dados
try {
    $stmt = $connection->query("SELECT * FROM settings");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $config = array_merge($config, $settings);
} catch (PDOException $e) {
    // Se a tabela settings não existir, usar as configurações padrão
} 
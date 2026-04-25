<?php
// Carrega o Composer (Segurança e Autoload)
require_once __DIR__ . '/vendor/autoload.php';

// Carrega as variáveis do .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $host = $_ENV['DB_HOST'];
    $db   = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];

    // Cria a conexão PDO global
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão: " . $e->getMessage()]));
}

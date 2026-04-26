<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

class Database {
    private $pdo;

    public function getConnection() {
        if ($this->pdo === null) {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();

            try {
                $this->pdo = new PDO(
                    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8",
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS']
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]));
            }
        }
        return $this->pdo;
    }
}

<?php

// 1. Carrega automaticamente as bibliotecas do Composer (Dotenv, etc)
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

class Insumo {
    private $pdo;
    private $table = "insumos";

    public function __construct() {
        // 2. Carrega as variáveis do seu arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        try {
            // 3. Conexão protegida usando os dados do .env
            $host = $_ENV['DB_HOST'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Em produção, não mostre $e->getMessage() para o usuário final
            die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão"]));
        }
    }

    /**
     * Método para buscar itens de forma segura
     */
    public function buscar($termo = "") {
        try {
            $sql = "SELECT id, nome_item, preco_total, custo_por_grama 
                    FROM {$this->table} 
                    WHERE nome_item LIKE :busca 
                    ORDER BY custo_por_grama ASC";
            
            $stmt = $this->pdo->prepare($sql);
            
            // Proteção: limpa o termo antes de enviar pro banco
            $busca = "%" . htmlspecialchars(strip_tags($termo)) . "%";
            $stmt->bindParam(':busca', $busca);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ["erro" => $e->getMessage()];
        }
    }
}

// --- EXEMPLO DE USO ---
// Se você quiser testar no mesmo arquivo:
/*
$insumoObj = new Insumo();
$resultados = $insumoObj->buscar($_GET['q'] ?? '');
echo json_encode($resultados);
*/

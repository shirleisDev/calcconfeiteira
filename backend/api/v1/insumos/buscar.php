<?php

class Insumo {
    private $conn;
    private $table_name = "insumos";

    // O construtor recebe a conexão PDO
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para buscar insumos
    public function buscar($termo) {
        $query = "SELECT id, nome_item, preco_total, peso_total, custo_por_grama 
                  FROM " . $this->table_name . " 
                  WHERE nome_item LIKE :busca 
                  ORDER BY custo_por_grama ASC";

        $stmt = $this->conn->prepare($query);
        
        // Limpeza de segurança (Sanitize)
        $termo = htmlspecialchars(strip_tags($termo));
        $busca = "%{$termo}%";
        
        $stmt->bindParam(':busca', $busca);
        $stmt->execute();

        return $stmt;
    }
}

// --- Exemplo de uso no seu arquivo de API ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config.php'; 

try {
    // 1. Instancia a classe passando o $pdo do config.php
    $insumo = new Insumo($pdo);
    
    // 2. Chama o método de busca
    $termo = $_GET['q'] ?? '';
    $stmt = $insumo->buscar($termo);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "sucesso",
        "total" => count($resultados),
        "dados" => $resultados
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}


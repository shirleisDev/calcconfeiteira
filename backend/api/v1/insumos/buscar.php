<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Importa o config que acabamos de criar
require_once __DIR__ . '/../config.php'; 

// Pega o termo da URL
$termo = isset($_GET['q']) ? "%" . $_GET['q'] . "%" : "%";

try {
    $sql = "SELECT id, nome_item, preco_total, peso_total, custo_por_grama 
            FROM insumos 
            WHERE nome_item LIKE :busca 
            ORDER BY custo_por_grama ASC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':busca', $termo);
    $stmt->execute();

    $resultados = $stmt->fetchAll();

    echo json_encode([
        "status" => "sucesso",
        "total" => count($resultados),
        "dados" => $resultados
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}


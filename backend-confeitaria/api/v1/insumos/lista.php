
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Volta 3 pastas para achar o config.php na raiz do backend
require_once '../../../config.php'; 

try {
    // 1. Puxa tudo da tabela 'insumos'
    $sql = "SELECT id, nome_item, preco_total, peso_total, custo_por_grama FROM insumos ORDER BY nome_item ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 2. Transforma em Array
    $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Responde pro App
    echo json_encode([
        "status" => "sucesso",
        "dados" => $insumos
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}
?>
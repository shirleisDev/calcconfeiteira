<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../../config.php'; 

// Pega o termo de busca da URL (ex: buscar.php?q=chocolate)
$termo = isset($_GET['q']) ? "%" . $_GET['q'] . "%" : "%";

try {
    // A mágica acontece aqui: busca por NOME ou MERCADO (se você tiver a coluna)
    // Ordenamos pelo menor CUSTO POR GRAMA para mostrar o melhor preço primeiro!
    $sql = "SELECT id, nome_item, preco_total, peso_total, custo_por_grama 
            FROM insumos 
            WHERE nome_item LIKE :busca 
            ORDER BY custo_por_grama ASC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':busca', $termo);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "sucesso",
        "termo_buscado" => str_replace("%", "", $termo),
        "total_encontrado" => count($resultados),
        "dados" => $resultados
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}
?>

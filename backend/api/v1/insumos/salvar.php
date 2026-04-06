<?php
// 1. Configurações de Acesso (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 2. Importa a conexão (ajuste o caminho se o config.php estiver em outra pasta)
// Se o config.php estiver na raiz da pasta 'backend-confeitaria', o caminho é:
require_once '../../../config.php'; 

try {
    // 3. Busca todos os insumos do banco
    $sql = "SELECT * FROM insumos ORDER BY nome_item ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 4. Pega os resultados como um Array Associativo
    $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. Devolve para o App
    if ($insumos) {
        echo json_encode([
            "status" => "sucesso",
            "dados" => $insumos
        ]);
    } else {
        echo json_encode([
            "status" => "sucesso",
            "mensagem" => "Nenhum ingrediente cadastrado ainda.",
            "dados" => []
        ]);
    }

} catch (PDOException $e) {
    // Erro de banco
    http_response_code(500);
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao buscar dados: " . $e->getMessage()
    ]);
}
?>
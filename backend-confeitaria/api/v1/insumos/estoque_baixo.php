<?php
// 1. Configurações de Acesso (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 2. Importa a conexão
require_once '../../config.php';

try {
    // 3. Busca apenas insumos com pouca quantidade (ex: menos de 5 unidades/kg)
    // 3. Define o limite (aceita parâmetro via URL ou usa 5 como padrão)
$limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 5;

$sql = "SELECT * FROM insumos WHERE quantidade <= :limite ORDER BY quantidade ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    
    $itensBaixos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Retorna o resultado
    if ($itensBaixos) {
        echo json_encode([
            "status" => "alerta",
            "mensagem" => "Existem itens com estoque baixo!",
            "dados" => $itensBaixos
        ]);
    } else {
        echo json_encode([
            "status" => "sucesso",
            "mensagem" => "Tudo em ordem no estoque!",
            "dados" => []
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}
?>
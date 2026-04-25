    <?php
    // 1. "Portas Abertas": Permite que o App (React Native) acesse o PHP sem bloqueio de segurança (CORS)
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");

    // 2. Conecta ao Banco: Traz as configurações do arquivo que você acabou de criar
    include 'config.php';

    // 3. Ouve o App: Pega os dados que o JavaScript enviou em formato JSON
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    if ($dados) {        
        print($dados);

        // 4. Lógica de Negócio (Backend): Calcula o custo por grama antes de salvar
        $nome = $dados['nome'];
        $preco = (float)$dados['preco'];
        $peso = (float)$dados['peso'];
        $custo_g = $preco / $peso;

        try {
            // 5. Segurança Máxima (Prepared Statements): Protege contra Invasão SQL Injection
            $sql = "INSERT INTO insumos (nome_item, preco_total, peso_total, custo_por_grama) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $preco, $peso, $custo_g]);

            // 6. Resposta: O PHP avisa ao App que deu tudo certo
            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "Ingrediente '$nome' salvo no SQL!",
                "valor_calculado" => round($custo_g, 4)
            ]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Nenhum dado recebido."]);
    }
    ?>

<<?php
// --- 1. CONFIGURAÇÃO DOS MERCADOS (O SEU ARRAY) ---
$mercados = [
    "Rede Super Compras" => "https://redesupercompras.com",
    "Guanabara"          => "https://supermercadosguanabara.com.br", 
    "Mundial"            => "https://supermercadosmundial.com.br"
];

$ingredienteAlvo = $_GET['item'] ?? "Leite Condensado";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Radar de Preços - Confeitaria</title>
</head>
<body style="background-color: #05070a; color: white; font-family: sans-serif; padding: 20px;">

    <div style="background: linear-gradient(135deg, #0a1428, #162447); padding: 20px; border-radius: 10px; border: 1px solid #0096ff; margin-bottom: 20px; text-align: center;">
        <h2 style="color: #00d4ff;">🔍 RADAR DE PREÇOS GALÁCTICO</h2>
        <form method="GET" action="">
            <input type="text" name="item" 
                   placeholder="Digite o produto..." 
                   value="<?php echo htmlspecialchars($ingredienteAlvo); ?>"
                   style="padding: 12px; width: 300px; border-radius: 5px; border: 1px solid #0096ff; outline: none;">
            <button type="submit" style="padding: 12px 20px; background: #0096ff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                BUSCAR EM TODOS OS MERCADOS
            </button>
        </form>
    </div>

<?php
echo "<h2>🕵️‍♂️ Operação Espião Iniciada...</h2>";

// --- 2. O LAÇO QUE PERCORRE O ARRAY ---
foreach ($mercados as $nomeMercado => $url) {
    echo "<div style='background:#1a1d23; padding:15px; border-radius:8px; border: 1px solid #333; margin-bottom: 20px;'>";
    echo "<h3 style='color:#00d4ff'>🏪 Mercado: $nomeMercado</h3>";

    $opcoes = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/115.0.0.0\r\n"
        ]
    ];
    $contexto = stream_context_create($opcoes);
    $conteudo = @file_get_contents($url, false, $contexto);

    if ($conteudo === FALSE) {
        echo "<p style='color:red'>❌ O $nomeMercado bloqueou o robô ou o site está fora do ar.</p>";
    } else {
        echo "<p style='color:green'>✅ Sucesso! Entrei no site.</p>";
        
        // Busca a posição do item
        $posicaoItem = stripos($conteudo, $ingredienteAlvo);

        if ($posicaoItem !== false) {
            echo "<p style='color:#50fa7b'>🎯 Achei <b>$ingredienteAlvo</b> no código!</p>";

            $trechoProximo = substr($conteudo, $posicaoItem, 400); // Pegamos um pouco mais de texto
            $achouPreco = false;
            
            // Procura o valor R$
            if (preg_match('/R\$\s?([0-9.,]+)/', $trechoProximo, $valor)) {
                echo "<div style='background:#238636; padding:15px; border-radius:8px; border:1px solid #7ee787;'>";
                echo "<h2 style='margin:0;'>💰 PREÇO EM $nomeMercado: <span style='color:#fff'>{$valor[0]}</span></h2>";
                echo "</div>";
                $achouPreco = true;
            }

            if (!$achouPreco) {
                echo "<p style='color:#ffb86c'>⚠️ Nome encontrado, mas o preço está difícil de ler.</p>";
            }
        } else {
            echo "<p style='color:#ff5555'>❌ O termo <b>$ingredienteAlvo</b> não foi encontrado no $nomeMercado.</p>";
        }
    }
    echo "</div>";
}
?>
</body>
</html>

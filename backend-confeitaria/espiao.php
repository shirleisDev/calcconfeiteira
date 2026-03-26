<?php
// A URL que vamos "invadir" (Super Compras Rio)
$url = "https://redesupercompras.com";

echo "<h2>🕵️‍♂️ Operação Espião Iniciada...</h2>";

// 1. Criamos a "máscara" de navegador (User-Agent) para não ser bloqueado
$opcoes = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/115.0.0.0\r\n"
    ]
];
$contexto = stream_context_create($opcoes);

// 2. O robô tenta ler o site
$conteudo = @file_get_contents($url, false, $contexto);

if ($conteudo === FALSE) {
    echo "<p style='color:red'>❌ O mercado bloqueou o robô ou o site está fora do ar.</p>";
} else {
    echo "<p style='color:green'>✅ Sucesso! O robô entrou no site.</p>";
    
    // 3. Vamos procurar o <title> do site (o que aparece na aba do navegador)
    preg_match("/<title>(.*)<\/title>/i", $conteudo, $matches);
    
    echo "<h3>O que o robô leu no topo do site:</h3>";
    echo "<div style='background:#eee; padding:10px; border-radius:5px;'>";
    echo $matches[1] ?? "Título não encontrado, mas o código foi lido!";
    echo "</div>";
    
    echo "<h4>Primeiros 200 caracteres do 'DNA' do site:</h4>";
    echo "<pre>" . htmlspecialchars(substr($conteudo, 0, 200)) . "...</pre>";
}
?>

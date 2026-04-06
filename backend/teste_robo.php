<?php
// --- 1. LISTA DE ALVOS (Basta adicionar o nome e o link aqui) ---
$alvos = [
    "Super Compras"  => "https://redesupercompras.com",
    "Casa Pedro"     => "https://casapedro.com.br",
    "Grão da Terra"  => "https://graodaterra.com.br", // Verifique se o link da sua região é este
    "Padaria Local"  => "https://exemplo-padaria.com.br" 
];

echo "<h1>🕵️‍♂️ Operação Espião: Multilocais</h1>";

// O "Disfarce" do robô (User-Agent) para os sites não barrarem
$opcoes = [
    "http" => ["header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"]
];
$contexto = stream_context_create($opcoes);

// --- 2. O ROBÔ PERCORRENDO A LISTA ---
foreach ($alvos as $nome => $url) {
    echo "<div style='border-left: 5px solid #00d4ff; padding-left: 15px; margin-bottom: 30px;'>";
    echo "<h3>📍 Verificando: <span style='color:#00d4ff'>$nome</span></h3>";
    
    // Tentando ler o código bruto
    $html_bruto = @file_get_contents($url, false, $contexto);

    if ($html_bruto === FALSE) {
        echo "<p style='color:red'>❌ Falha na conexão com $nome. (Site protegido ou link quebrado)</p>";
    } else {
        echo "<p style='color:green'>✅ O robô entrou com sucesso!</p>";
        
        // Mostra o "DNA" do site (os primeiros 600 caracteres)
        echo "<div style='background:#1a1d23; color:#ccc; padding:10px; border-radius:5px; font-family:monospace; font-size:11px;'>";
        echo htmlspecialchars(substr($html_bruto, 0, 600)) . "...";
        echo "</div>";
    }
    echo "</div>";
}
?>

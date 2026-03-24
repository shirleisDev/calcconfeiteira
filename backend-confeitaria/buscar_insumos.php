<?php
// 1. HEADERS (A "Autorização"): 
// Como o React (Porta 5173) vai pedir dados para o PHP (Porta 80), 
// o navegador bloqueia se não tiver essas linhas.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 2. CONEXÃO: O porteiro do banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "calcconfeitera"; // Verifique se o nome do seu banco é este mesmo!

$conn = new mysqli($host, $user, $pass, $dbname);

// Se a conexão falhar, avisamos em formato JSON (que o React entende)
if ($conn->connect_error) {
    die(json_encode(["erro" => "Conexão falhou: " . $conn->connect_error]));
}

// 3. CAPTURA DA BUSCA: 
// O comando $_GET['q'] pega o que vem na URL (ex: ?q=farinha)
$buscaUsuario = isset($_GET['q']) ? $_GET['q'] : '';

// 4. SEGURANÇA (Prepared Statements): 
// Nunca coloque a variável direto no SQL. Usamos o "?" como um "lugar reservado".
// O "LIKE" com "%" permite achar "Farinha de Trigo" pesquisando apenas "Trigo".
$sql = "SELECT * FROM insumos WHERE nome LIKE ?";
$stmt = $conn->prepare($sql);

// O "s" diz que o parâmetro é uma String (texto)
$termoComCuringa = "%" . $buscaUsuario . "%";
$stmt->bind_param("s", $termoComCuringa);

// 5. EXECUÇÃO E RESPOSTA
$stmt->execute();
$resultado = $stmt->get_result();

// Pegamos todas as linhas encontradas e transformamos em uma lista (Array)
$listaInsumos = $resultado->fetch_all(MYSQLI_ASSOC);

// 6. CONVERSÃO FINAL: Transforma a lista de PHP para JSON (O "tradutor")
echo json_encode($listaInsumos);

// Fecha as conexões para não gastar memória do PC
$stmt->close();
$conn->close();
?>

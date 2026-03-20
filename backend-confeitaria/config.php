<?php
// Configurações do Banco de Dados
$host    = 'localhost';
$db_name = 'nome_do_seu_banco'; // <--- COLOQUE O NOME DO SEU BANCO AQUI
$user    = 'root';               // Usuário padrão do XAMPP/WAMP
$pass    = '';                   // Senha padrão (geralmente vazia no local)

try {
    // Cria a conexão via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $pass);
    
    // Configura o PDO para avisar se houver qualquer erro de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Se a conexão falhar, interrompe e mostra o erro
    die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão: " . $e->getMessage()]));
}
?>
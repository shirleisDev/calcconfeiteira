<?php
// Configurações do Banco de Dados
$host    = 'localhost';
$dbname = "calcconfeitera"; // <--- COLOQUE O NOME DO SEU BANCO AQUI
$user    = 'root';               // Usuário padrão do XAMPP/WAMP
$pass    = '';                   // Senha padrão (geralmente vazia no local)


try {
    // Agora usamos EXATAMENTE $dbname (sem o underline entre db e name)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // Configura o PDO para avisar se houver qualquer erro de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
<?php
require_once 'Database.php';

class Insumo {
    private $conn;
    private $table = "insumos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ... (Aqui entram os métodos de buscar e salvar que criamos antes) ...
    
    public function buscar($termo = "") {
        $sql = "SELECT * FROM {$this->table} WHERE nome_item LIKE :busca";
        $stmt = $this->conn->prepare($sql);
        $busca = "%" . htmlspecialchars(strip_tags($termo)) . "%";
        $stmt->bindParam(':busca', $busca);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

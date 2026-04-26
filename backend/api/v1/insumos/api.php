<?php

require_once 'Database.php';

class Insumo {
    private $conn;
    private $table = "insumos";

    // O construtor já busca a conexão segura da classe Database
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * BUSCAR: Protegido contra SQL Injection e XSS
     */
    public function buscar($termo = "") {
        try {
            $sql = "SELECT id, nome_item, preco_total, peso_total, custo_por_grama 
                    FROM {$this->table} 
                    WHERE nome_item LIKE :busca 
                    ORDER BY custo_por_grama ASC";
            
            $stmt = $this->conn->prepare($sql);
            
            // Limpa o termo de busca (XSS Protection)
            $termoLimpo = htmlspecialchars(strip_tags($termo));
            $busca = "%" . $termoLimpo . "%";
            
            $stmt->bindParam(':busca', $busca);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["erro" => "Falha na busca profissional"];
        }
    }

    /**
     * SALVAR: Blindagem Nível CI/CD (Tipagem e Sanitização)
     */
    public function salvar($nome, $preco, $peso) {
        try {
            // 1. Travas de Segurança (Prevenir transbordo/injeção de dados)
            if (strlen($nome) > 100) return ["erro" => "Nome muito longo"];
            
            $preco = filter_var($preco, FILTER_VALIDATE_FLOAT);
            $peso = filter_var($peso, FILTER_VALIDATE_FLOAT);

            if ($preco === false || $peso <= 0) {
                return ["erro" => "Valores de preço ou peso inválidos"];
            }

            // 2. Cálculo do custo
            $custoPorGrama = $preco / $peso;

            // 3. Query preparada (Proteção contra SQL Injection)
            $sql = "INSERT INTO {$this->table} 
                    (nome_item, preco_total, peso_total, custo_por_grama) 
                    VALUES (:nome, :preco, :peso, :custo)";
            
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':nome', htmlspecialchars(strip_tags($nome)));
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':peso', $peso);
            $stmt->bindValue(':custo', $custoPorGrama);

            if ($stmt->execute()) {
                return ["sucesso" => true, "id" => $this->conn->lastInsertId()];
            }

            return ["erro" => "Não foi possível salvar"];

        } catch (PDOException $e) {
            // Log interno, resposta limpa para o usuário
            error_log($e->getMessage());
            return ["erro" => "Erro interno no servidor"];
        }
    }
}

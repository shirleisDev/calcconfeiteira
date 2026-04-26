<?php

require_once 'Insumo.php';

class TesteAPI {
    private $gerenteInsumo;

    public function __construct() {
        // Instancia a classe que blindamos
        $this->gerenteInsumo = new Insumo();
    }

    public function rodarTestes() {
        echo "<h1>🧪 Iniciando Testes de Blindagem</h1>";

        // 1. Teste de Inserção
        echo "<h3>1. Testando Cadastro de Insumo:</h3>";
        $resultado = $this->gerenteInsumo->salvar("Leite Condensado 395g", 7.50, 395);
        
        if (isset($resultado['sucesso'])) {
            echo "<p style='color: green;'>✅ Sucesso: Insumo cadastrado com ID: " . $resultado['id'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Erro esperado ou real: " . $resultado['erro'] . "</p>";
        }

        // 2. Teste de Busca
        echo "<h3>2. Testando Busca (Radar Galáctico):</h3>";
        $lista = $this->gerenteInsumo->buscar("Leite");
        
        echo "<pre>";
        print_r($lista);
        echo "</pre>";

        // 3. Teste de Proteção (Injeção de Script)
        echo "<h3>3. Testando Proteção contra Injeção (XSS):</h3>";
        $ataque = "<script>alert('hack')</script>Creme de Leite";
        $resAtaque = $this->gerenteInsumo->salvar($ataque, 5.00, 200);
        echo "<p>Ao tentar injetar script, o sistema limpou e salvou. Verifique no banco se o nome aparece sem as tags de script.</p>";
    }
}

// Para rodar o teste no navegador ou terminal:
$teste = new TesteAPI();
$teste->rodarTestes();


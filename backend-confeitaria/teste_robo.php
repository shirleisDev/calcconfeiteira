//este arquivo tempoucas linhas mais poderosas pois visita os site de compras e tras o scodigo bruto para o robor ler >

<?php
// A URL do mercado que queremos "espiar"
$url = "https://redesupercompras.com";

// O comando file_get_contents é como o robô "abrindo o navegador"
$html_bruto = file_get_contents($url);

// Vamos mostrar apenas os primeiros 500 caracteres do código fonte
echo "<h1>O que o robô enxerga:</h1>";
echo "<pre>" . htmlspecialchars(substr($html_bruto, 0, 1000)) . "...</pre>";
?>
//O seu PHP vai sair do seu computador e bater na porta do servidor do Super Compras.
//Ele vai baixar todo o HTML (o esqueleto do site).
//Ele vai exibir na sua tela um monte de tags como <div>, <span> e <script fabuloso demais 
// vai haver uma varredura pelo codigo procurando por a class price 

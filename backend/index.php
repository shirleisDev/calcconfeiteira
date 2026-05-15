<?php
// Linhas mágicas que removem o erro de Unauthorized na internet
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: text/html; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

echo "<div style='text-align: center; margin-top: 50px; font-family: Arial, sans-serif;'>";
echo "    <h1 style='color: #6a1b9a;'>🚀 API DO PROJETO CALC CONFEITEIRA</h1>";
echo "    <p style='font-size: 18px; color: #424242;'>O coração do sistema está <b>ONLINE</b> e rodando na nuvem Render!</p>";
echo "    <p style='color: #757575;'>Pronto para receber as conexões do Frontend e processar a Visão de Raio-X Galáctica do OCR.</p>";
echo "</div>";


<?php
require_once 'vendor/autoload.php';
use TesseractOCR\TesseractOCR;

// 1. Caminho da imagem (certifique-se que ela existe na pasta!)
$imagem = 'print_mercado.jpg'; 

// 2. Caminho do motor que você acabou de instalar
$caminhoTesseract = 'C:\Program Files\Tesseract-OCR\tesseract.exe';

echo "🚀 ACIONANDO VISÃO DE RAIO-X GALÁCTICA...\n";

try {
    if (!file_exists($imagem)) {
        die("❌ Erro: Coloque a imagem 'print_mercado.jpg' na pasta backend primeiro!\n");
    }

    $ocr = new TesseractOCR($imagem);
    $ocr->executable($caminhoTesseract);
    $ocr->lang('por'); // Português para reconhecer preços brasileiros

    $texto = $ocr->run();

    echo "\n--- 📄 TEXTO EXTRAÍDO DO ENCARTE ---\n";
    echo $texto;
    echo "\n------------------------------------\n";
    echo "🎯 O robô leu com sucesso!";

} catch (Exception $e) {
    echo "❌ FALHA NA MISSÃO: " . $e->getMessage();
}

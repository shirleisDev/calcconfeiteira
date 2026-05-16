<?php

header("Content-Type: application/json");

require "supermercados.php";
require "buscarProduto.php";
require "ocr.php";

$produto = $_GET['produto'] ?? '';

if (empty($produto)) {

    echo json_encode([
        "erro" => "Produto não informado"
    ]);

    exit;
}
/** @disregard Undefined variable $supermercados */
$resultados = buscarProduto($produto, $supermercados);

if (!empty($resultados)) {

    echo json_encode([
        "success" => true,
        "resultados" => $resultados
    ]);

} else {

    /*
      EXEMPLO OCR
      imagem fictícia
    */

    $textoOCR = lerImagemOCR("encarte.png");

    if (stripos($textoOCR, $produto) !== false) {

        echo json_encode([
            "success" => true,
            "mensagem" => "Produto encontrado via OCR",
            "ocr" => $textoOCR
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "mensagem" => "produto nao reconhecido"
        ]);
    }
}
<?php

function lerImagemOCR($imagem)
{
    $arquivoSaida = "saida";

    exec("tesseract $imagem $arquivoSaida");

    $texto = file_get_contents($arquivoSaida . ".txt");

    return trim($texto);
}
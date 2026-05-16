<?php
function buscarProduto($nomeProduto, $supermercados)
{
    $resultados = [];
    foreach ($supermercados as $mercado) {

        $url = $mercado['api'] . "?q=" . urlencode($nomeProduto);
    error_log(print_r($url, true));

        $response = @file_get_contents($url);

        if ($response) {

            $dados = json_decode($response, true);

            if (!empty($dados)) {

                $resultados[] = [
                    "mercado" => $mercado['nome'],
                    "produto" => $dados[0]['nome'] ?? "Produto encontrado",
                    "preco" => $dados[0]['preco'] ?? "Sem preço"
                ];
            }
        }
    }


    return $resultados;
}
<?php
// Configurações para o React conseguir acessar este arquivo sem erro de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

class RoboSonicOCR 
{
    // Esta é uma chave pública de testes para o robô funcionar agora
    private $apiKey = "K81234567888957"; 

    public function executarVisaoRaioX($caminhoImagem)
    {
        try {
            if (!file_exists($caminhoImagem)) {
                throw new Exception("Arquivo de imagem não foi recebido no servidor!");
            }

            // Prepara os dados para mandar para a internet
            $dados = [
                'apikey' => $this->apiKey,
                'language' => 'por', // Configurado para ler em português
                'isOverlayRequired' => 'false',
                'file' => new CURLFile($caminhoImagem)
            ];

            // Faz a conexão com o servidor de leitura
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://ocr.space');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $resposta = curl_exec($ch);
            curl_close($ch);

            $resultado = json_decode($resposta, true);

            // Verifica se a leitura funcionou
            if (isset($resultado['ParsedResults'][0]['ParsedText'])) {
                $textoExtraido = $resultado['ParsedResults'][0]['ParsedText'];
                
                if (empty(trim($textoExtraido))) {
                    return [
                        "sucesso" => false, 
                        "mensagem" => "⚠️ O robô processou, mas não encontrou texto legível."
                    ];
                }

                return [
                    "sucesso" => true,
                    "mensagem" => "🎯 O robô leu com sucesso!",
                    "texto" => $textoExtraido
                ];
            } else {
                throw new Exception("Falha na resposta do servidor de OCR.");
            }

        } catch (Exception $e) {
            return [
                "sucesso" => false,
                "mensagem" => "❌ FALHA NA MISSÃO: " . $e->getMessage()
            ];
        }
    }
}

// --- EXECUÇÃO RECEBENDO A FOTO DO FRONTEND ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $robo = new RoboSonicOCR();
        $imagemTemporaria = $_FILES['file']['tmp_name'];
        
        $respostaFinal = $robo->executarVisaoRaioX($imagemTemporaria);
        echo json_encode($respostaFinal);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Nenhum arquivo enviado."]);
    }
    exit;
}

<?php

function loadEnv($path = null)
{
    $vars = [];

    // Read .env file when available (local development)
    if ($path && file_exists($path)) {
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            [$key, $value] = explode('=', $line, 2);
            $vars[trim($key)] = trim($value);
        }
    }

    // Fall back to environment variables (Google Cloud Functions)
    $knownVars = [
        'API_KEY', 'MONGO_DATA_API_URL', 'MONGO_API_KEY',
        'MONGO_DATABASE', 'MONGO_COLLECTION', 'MONGO_DATA_SOURCE'
    ];
    foreach ($knownVars as $var) {
        if (empty($vars[$var]) && getenv($var) !== false) {
            $vars[$var] = getenv($var);
        }
    }

    return $vars;
}

function visionCurlPost($url, $payload, $timeout = 180)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        logMessage("cURL error: $curlError", 'ERROR');
        return null;
    }

    if ($httpCode !== 200) {
        $decoded = json_decode($response, true);
        $apiError = $decoded['error']['message'] ?? $response;
        logMessage("Vision API HTTP $httpCode: $apiError", 'ERROR');
        return null;
    }

    return json_decode($response, true);
}

function visionAnnotateImage($filePath, $apiKey)
{
    $content = base64_encode(file_get_contents($filePath));

    $payload = [
        'requests' => [[
            'image' => ['content' => $content],
            'features' => [['type' => 'TEXT_DETECTION']]
        ]]
    ];

    $url = "https://vision.googleapis.com/v1/images:annotate?key=$apiKey";
    $data = visionCurlPost($url, $payload);

    if (!$data) return '';

    return $data['responses'][0]['textAnnotations'][0]['description'] ?? '';
}

function parseProductsWithGemini($rawText, $apiKey)
{
    if (empty(trim($rawText))) return [];

    $prompt = <<<PROMPT
You are a supermarket price catalog extractor for a Brazilian confectionery cost calculator.
Given raw OCR text from a Brazilian supermarket flyer, extract every product that has a visible price.

Rules:
- Return ONLY a valid JSON array, no markdown, no explanation.
- Each element: {"name": "product name (lowercase, in Portuguese)", "description": "brand, size, weight or any relevant detail — empty string if unavailable", "price": numeric}
- Price: convert Brazilian format (e.g. "R$ 9,99" or "9,99") to a float (9.99). Use the lowest price when a range or promotion is shown.
- Skip: store slogans, section headers, addresses, phone numbers, and anything without a clear numeric price.

Text:
$rawText
PROMPT;

    $payload = [
        'contents' => [['parts' => [['text' => $prompt]]]],
        'generationConfig' => ['responseMimeType' => 'application/json']
    ];

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        logMessage("Gemini cURL error: $curlError", 'ERROR');
        return [];
    }

    if ($httpCode !== 200) {
        $decoded = json_decode($response, true);
        $apiError = $decoded['error']['message'] ?? $response;
        logMessage("Gemini API HTTP $httpCode: $apiError", 'ERROR');
        return [];
    }

    $data = json_decode($response, true);
    $jsonText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
    $products = json_decode($jsonText, true);

    return is_array($products) ? $products : [];
}

function visionAnnotatePdf($filePath, $apiKey)
{
    $content = base64_encode(file_get_contents($filePath));
    $url = "https://vision.googleapis.com/v1/files:annotate?key=$apiKey";
    $fullText = '';
    $batchSize = 5;
    $page = 1;

    while (true) {
        $pages = range($page, $page + $batchSize - 1);

        $payload = [
            'requests' => [[
                'inputConfig' => [
                    'content' => $content,
                    'mimeType' => 'application/pdf'
                ],
                'features' => [['type' => 'DOCUMENT_TEXT_DETECTION']],
                'pages' => $pages
            ]]
        ];

        $data = visionCurlPost($url, $payload);

        if (!$data) break;

        $responses = $data['responses'][0]['responses'] ?? [];

        $batchText = '';
        foreach ($responses as $pageResponse) {
            $batchText .= $pageResponse['fullTextAnnotation']['text'] ?? '';
        }

        if (empty(trim($batchText))) break;

        $fullText .= $batchText;
        $page += $batchSize;
    }

    return $fullText;
}

<?php

if (php_sapi_name() !== 'cli') {
    die("This script must be run from command line\n");
}

require __DIR__ . '/scraper_config.php';
require __DIR__ . '/scraper_functions.php';
require __DIR__ . '/ocr_functions.php';

logMessage("=== Starting OCR Service ===");

$env = loadEnv(__DIR__ . '/../.env');
$apiKey = $env['API_KEY'] ?? null;

if (!$apiKey) {
    logMessage("API_KEY not found in .env", 'ERROR');
    exit(1);
}

$outputFile = getDatedPath(__DIR__ . '/output/todaysproducts.json');

if (file_exists($outputFile)) {
    logMessage("Today's OCR output already exists: $outputFile, skipping");
    exit(0);
}

$results = [
    'date' => gmdate('Y-m-d'),
    'supermarkets' => []
];

$successCount = 0;
$failureCount = 0;

foreach ($scraperConfig as $config) {
    $name = $config['name'];
    logMessage("Processing: $name");

    $text = '';

    if ($config['type'] === 'image') {
        foreach ($config['outputs'] as $output) {
            $datedFile = getDatedPath($output);

            if (!file_exists($datedFile)) {
                logMessage("File not found: $datedFile (run scraper first)", 'WARNING');
                continue;
            }

            logMessage("Annotating image: $datedFile");
            $pageText = visionAnnotateImage($datedFile, $apiKey);

            if (empty($pageText)) {
                logMessage("No text extracted from: $datedFile", 'WARNING');
            } else {
                logMessage("Extracted " . strlen($pageText) . " chars from: $datedFile", 'SUCCESS');
                $text .= $pageText . "\n";
            }
        }
    }

    if ($config['type'] === 'pdf') {
        $datedFile = getDatedPath($config['output']);

        if (!file_exists($datedFile)) {
            logMessage("File not found: $datedFile (run scraper first)", 'WARNING');
            $failureCount++;
            continue;
        }

        logMessage("Annotating PDF: $datedFile");
        $text = visionAnnotatePdf($datedFile, $apiKey);

        if (empty($text)) {
            logMessage("No text extracted from: $datedFile", 'WARNING');
        } else {
            logMessage("Extracted " . strlen($text) . " chars from PDF", 'SUCCESS');
        }
    }

    if (!empty(trim($text))) {
        logMessage("Parsing products with Gemini for: $name");
        $products = parseProductsWithGemini($text, $apiKey);
        $results['supermarkets'][$name] = $products;
        logMessage("Found " . count($products) . " product(s)", 'SUCCESS');
        $successCount++;
    } else {
        $results['supermarkets'][$name] = [];
        $failureCount++;
    }

    logMessage("");
}

$dir = dirname($outputFile);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

file_put_contents($outputFile, json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

logMessage("=== OCR Complete ===");
logMessage("Successful: $successCount");
logMessage("Failed/empty: $failureCount");
logMessage("Output: $outputFile");

exit($failureCount > 0 ? 1 : 0);

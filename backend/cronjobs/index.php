<?php

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/scraper_config.php';
require_once __DIR__ . '/scraper_functions.php';
require_once __DIR__ . '/ocr_functions.php';
require_once __DIR__ . '/mongo_functions.php';

FunctionsFramework::http('runScraper', 'runScraper');

function runScraper(ServerRequestInterface $request): string
{
    global $scraperConfig;

    $env = loadEnv(__DIR__ . '/../.env');

    $apiKey = $env['API_KEY'] ?? '';
    $mongoConfig = [
        'url'        => rtrim($env['MONGO_DATA_API_URL'] ?? '', '/'),
        'apiKey'     => $env['MONGO_API_KEY'] ?? '',
        'database'   => $env['MONGO_DATABASE'] ?? '',
        'collection' => $env['MONGO_COLLECTION'] ?? 'supermarket_products',
        'dataSource' => $env['MONGO_DATA_SOURCE'] ?? 'Cluster0',
    ];

    $today = gmdate('Y-m-d');

    logMessage("=== Scraper Cloud Function started for $today ===");

    if (mongoTodayExists($today, $mongoConfig)) {
        logMessage("Today's data already in MongoDB, skipping");
        return json_encode(['status' => 'already_done', 'date' => $today]);
    }

    $summary = [];

    foreach ($scraperConfig as $config) {
        $name = $config['name'];
        logMessage("Processing: $name");

        $html = fetchHtml($config['url']);
        if (!$html) {
            logMessage("Failed to fetch HTML for $name", 'ERROR');
            $summary[$name] = ['error' => 'fetch_failed'];
            continue;
        }

        $text = '';

        if ($config['type'] === 'image') {
            $imageUrls = parseHtmlForImages(
                $html,
                $config['selector']['value'],
                $config['selector']['limit']
            );

            foreach ($imageUrls as $idx => $imgUrl) {
                $absolute = resolveUrl($imgUrl, $config['url']);
                $fileDest = getDatedPath($config['outputs'][$idx]);
                if (downloadFile($absolute, $fileDest)) {
                    $text .= visionAnnotateImage($fileDest, $apiKey) . "\n";
                    @unlink($fileDest);
                }
            }
        }

        if ($config['type'] === 'pdf') {
            $pdfUrl = parseHtmlForPdfLink(
                $html,
                $config['selector']['value'],
                $config['selector']['method'] ?? 'button_text'
            );

            if ($pdfUrl) {
                $dest = getDatedPath($config['output']);
                if (downloadFile(resolveUrl($pdfUrl, $config['url']), $dest)) {
                    $text = visionAnnotatePdf($dest, $apiKey);
                    @unlink($dest);
                }
            }
        }

        if (empty(trim($text))) {
            logMessage("No text extracted for $name", 'WARNING');
            $summary[$name] = ['error' => 'no_text'];
            continue;
        }

        logMessage("Parsing products with Gemini for: $name");
        $products = parseProductsWithGemini($text, $apiKey);
        logMessage("Found " . count($products) . " product(s) for $name", 'SUCCESS');

        $saved = mongoSaveProducts($name, $products, $today, $mongoConfig);
        $summary[$name] = [
            'products' => count($products),
            'saved'    => $saved,
        ];

        logMessage("");
    }

    logMessage("=== Done ===");

    return json_encode([
        'status'  => 'done',
        'date'    => $today,
        'summary' => $summary,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

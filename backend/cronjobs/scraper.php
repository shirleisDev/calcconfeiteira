<?php

if (php_sapi_name() !== 'cli') {
    die("This script must be run from command line\n");
}

require __DIR__ . '/scraper_config.php';
require __DIR__ . '/scraper_functions.php';

logMessage("=== Starting Supermarket Scraper ===");

$successCount = 0;
$failureCount = 0;

foreach ($scraperConfig as $config) {
    $name = $config['name'];
    logMessage("Processing: $name");

    if ($config['type'] === 'image') {
        $allExist = true;
        foreach ($config['outputs'] as $output) {
            if (!file_exists(getDatedPath($output))) {
                $allExist = false;
                break;
            }
        }
        if ($allExist) {
            logMessage("Today's files already exist for $name, skipping download", 'INFO');
            $successCount += count($config['outputs']);
            logMessage("");
            continue;
        }
    }

    if ($config['type'] === 'pdf') {
        $destination = getDatedPath($config['output']);
        if (file_exists($destination)) {
            logMessage("Today's file already exists for $name ($destination), skipping download", 'INFO');
            $successCount++;
            logMessage("");
            continue;
        }
    }

    logMessage("Fetching HTML from: {$config['url']}");
    $html = fetchHtml($config['url']);

    if (!$html) {
        logMessage("Failed to fetch HTML for $name", 'ERROR');
        $failureCount++;
        continue;
    }

    logMessage("HTML fetched successfully");

    if ($config['type'] === 'image') {
        $className = $config['selector']['value'];
        $limit = $config['selector']['limit'];

        logMessage("Searching for images with class: $className");
        $imageUrls = parseHtmlForImages($html, $className, $limit);

        if (empty($imageUrls)) {
            logMessage("No images found for $name", 'ERROR');
            $failureCount++;
            continue;
        }

        logMessage("Found " . count($imageUrls) . " image(s)");

        foreach ($imageUrls as $index => $imageUrl) {
            $absoluteUrl = resolveUrl($imageUrl, $config['url']);
            $destination = getDatedPath($config['outputs'][$index]);

            logMessage("Downloading: $absoluteUrl");
            logMessage("Saving to: $destination");

            if (downloadFile($absoluteUrl, $destination)) {
                logMessage("Successfully saved: $destination", 'SUCCESS');
                $successCount++;
            } else {
                logMessage("Failed to download: $absoluteUrl", 'ERROR');
                $failureCount++;
            }
        }
    }

    if ($config['type'] === 'pdf') {
        $selectorValue = $config['selector']['value'];
        $selectorMethod = $config['selector']['method'] ?? 'button_text';

        logMessage("Searching for PDF link (method: $selectorMethod, value: $selectorValue)");
        $pdfUrl = parseHtmlForPdfLink($html, $selectorValue, $selectorMethod);

        if (!$pdfUrl) {
            logMessage("PDF link not found for $name", 'ERROR');
            $failureCount++;
            continue;
        }

        $absoluteUrl = resolveUrl($pdfUrl, $config['url']);
        $destination = getDatedPath($config['output']);

        logMessage("Found PDF URL: $absoluteUrl");
        logMessage("Downloading to: $destination");

        if (downloadFile($absoluteUrl, $destination)) {
            logMessage("Successfully saved: $destination", 'SUCCESS');
            $successCount++;
        } else {
            logMessage("Failed to download: $absoluteUrl", 'ERROR');
            $failureCount++;
        }
    }

    logMessage("");
}

logMessage("=== Scraping Complete ===");
logMessage("Successful: $successCount");
logMessage("Failed: $failureCount");

exit($failureCount > 0 ? 1 : 0);

<?php

function getDatedPath($path)
{
    $date = gmdate('Y-m-d');
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $base = substr($path, 0, -(strlen($ext) + 1));
    return $base . '_' . $date . '.' . $ext;
}

function logMessage($message, $type = 'INFO')
{
    $timestamp = date('Y-m-d H:i:s');
    $formatted = "[$timestamp] [$type] $message" . PHP_EOL;
    echo $formatted;
}

function fetchHtml($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200 || !$html) {
        return false;
    }

    return $html;
}

function parseHtmlForImages($html, $className, $limit = 2)
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    $query = "//img[contains(@class, '$className')]";
    $images = $xpath->query($query);

    $urls = [];
    $count = 0;

    foreach ($images as $img) {
        if ($count >= $limit) break;

        $src = $img->getAttribute('src');
        if ($src) {
            $urls[] = $src;
            $count++;
        }
    }

    return $urls;
}

function parseHtmlForPdfLink($html, $selectorValue, $method = 'button_text')
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    if ($method === 'href_pattern') {
        $links = $xpath->query("//a[contains(@href, '$selectorValue')]");
        if ($links->length > 0) {
            return $links->item(0)->getAttribute('href');
        }
        return false;
    }

    $buttonText = strtolower($selectorValue);
    $query = "//a[contains(translate(., 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '$buttonText')]";

    $links = $xpath->query($query);

    if ($links->length > 0) {
        $href = $links->item(0)->getAttribute('href');
        return $href;
    }

    $query = "//button[contains(translate(., 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '$buttonText')]";
    $buttons = $xpath->query($query);

    if ($buttons->length > 0) {
        $button = $buttons->item(0);
        $onclick = $button->getAttribute('onclick');
        if (preg_match('/window\.open\([\'"]([^\'"]+)[\'"]\)/', $onclick, $matches)) {
            return $matches[1];
        }
    }

    return false;
}

function resolveUrl($url, $baseUrl)
{
    if (preg_match('/^https?:\/\//', $url)) {
        return $url;
    }

    if (strpos($url, '//') === 0) {
        return 'https:' . $url;
    }

    if (strpos($url, '/') === 0) {
        $parts = parse_url($baseUrl);
        return $parts['scheme'] . '://' . $parts['host'] . $url;
    }

    $baseDir = dirname($baseUrl);
    return rtrim($baseDir, '/') . '/' . ltrim($url, '/');
}

function downloadFile($url, $destination)
{
    $dir = dirname($destination);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    $fp = fopen($destination, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);

    $success = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    fclose($fp);

    if (!$success || $httpCode != 200) {
        if (file_exists($destination)) {
            unlink($destination);
        }
        return false;
    }

    return true;
}

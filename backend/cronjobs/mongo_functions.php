<?php

function mongoCurl($endpoint, $payload, $apiKey)
{
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'api-key: ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$httpCode, json_decode($response, true)];
}

function mongoTodayExists($date, $mongoConfig)
{
    $payload = [
        'dataSource' => $mongoConfig['dataSource'],
        'database'   => $mongoConfig['database'],
        'collection' => $mongoConfig['collection'],
        'filter'     => ['date' => $date],
        'projection' => ['_id' => 1]
    ];

    [$code, $data] = mongoCurl($mongoConfig['url'] . '/action/findOne', $payload, $mongoConfig['apiKey']);

    return $code === 200 && !empty($data['document']);
}

function mongoSaveProducts($supermarket, $products, $date, $mongoConfig)
{
    $deletePayload = [
        'dataSource' => $mongoConfig['dataSource'],
        'database'   => $mongoConfig['database'],
        'collection' => $mongoConfig['collection'],
        'filter'     => ['date' => $date, 'supermarket' => $supermarket]
    ];
    mongoCurl($mongoConfig['url'] . '/action/deleteOne', $deletePayload, $mongoConfig['apiKey']);

    $insertPayload = [
        'dataSource' => $mongoConfig['dataSource'],
        'database'   => $mongoConfig['database'],
        'collection' => $mongoConfig['collection'],
        'document'   => [
            'date'        => $date,
            'supermarket' => $supermarket,
            'products'    => $products,
            'createdAt'   => gmdate('c')
        ]
    ];

    [$code, $data] = mongoCurl($mongoConfig['url'] . '/action/insertOne', $insertPayload, $mongoConfig['apiKey']);

    return $code === 201;
}

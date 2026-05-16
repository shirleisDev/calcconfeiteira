<?php

// In GCF: SCRAPER_OUTPUT_DIR is not set, so we use /tmp (ephemeral, cleaned after invocation).
// Locally: set SCRAPER_OUTPUT_DIR=backend/cronjobs/output in your shell or .env.
$outputDir = getenv('SCRAPER_OUTPUT_DIR') ?: sys_get_temp_dir();

$scraperConfig = [
    [
        'name' => 'Rede Super Compras',
        'url' => 'https://redesupercompras.com/ofertas',
        'type' => 'image',
        'selector' => [
            'method' => 'class',
            'value' => 'swiper-slide-image',
            'limit' => 2
        ],
        'outputs' => [
            $outputDir . '/rede-super-compras-1.jpg',
            $outputDir . '/rede-super-compras-2.jpg'
        ]
    ],
    [
        'name' => 'Guanabara',
        'url' => 'https://supermercadosguanabara.com.br/encarte',
        'type' => 'pdf',
        'selector' => [
            'method' => 'button_text',
            'value' => 'baixar encarte'
        ],
        'output' => $outputDir . '/guanabara.pdf'
    ],
    [
        'name' => 'Mundial',
        'url' => 'https://supermercadosmundial.com.br/encarte',
        'type' => 'pdf',
        'selector' => [
            'method' => 'href_pattern',
            'value' => '/api/encarte/'
        ],
        'output' => $outputDir . '/mundial.pdf'
    ],
    [
        'name' => 'Rede Supermarket',
        'url' => 'https://redesupermarket.com.br/',
        'type' => 'pdf',
        'selector' => [
            'method' => 'button_text',
            'value' => 'encarte'
        ],
        'output' => $outputDir . '/rede-supermarket.pdf'
    ],
    [
        'name' => 'Campeão',
        'url' => 'https://supermercadoscampeao.com.br/encarte-geral/',
        'type' => 'pdf',
        'selector' => [
            'method' => 'button_text',
            'value' => 'clique aqui e baixe encarte digital'
        ],
        'output' => $outputDir . '/campeao.pdf'
    ]
];

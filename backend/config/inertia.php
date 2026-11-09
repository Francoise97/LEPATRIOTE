<?php

return [
    'pages' => [
        'ensure_pages_exist' => true,
        'paths' => [
            base_path('../resources/js/Pages'),
        ],
        'extensions' => [
            'js',
            'jsx',
            'svelte',
            'ts',
            'tsx',
            'vue',
        ],
    ],
    'testing' => [
        'ensure_pages_exist' => true,
    ],
];

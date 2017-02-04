<?php
declare(strict_types = 1);
return [
    'paths' => [
        base_path('themes/*')
    ],
    'theme' => env('THEME', 'default'),
    'assets'=>'themes'
];

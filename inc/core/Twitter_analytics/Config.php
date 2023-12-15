<?php
return [
    'id' => 'twitter_analytics',
    'folder' => 'core',
    'name' => 'Twitter analytics',
    'author' => 'Stackcode',
    'author_uri' => 'sp',
    'desc' => 'sp - Social Media Management & Analysis Platform',
    'icon' => 'fab fa-twitter-square',
    'color' => '#00acee',
    'parent' => [
        "id" => "twitter",
        "name" => "Twitter"
    ],
    'cron' => [
        'name' => 'Twitter analytics',
        'uri' => 'twitter_analytics/cron',
        'style' => '* * * * *',
    ]
];
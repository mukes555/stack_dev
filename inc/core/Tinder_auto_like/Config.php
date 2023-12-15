<?php
return [
    'id' => 'tinder_auto_like',
    'folder' => 'core',
    'name' => 'Tinder auto like',
    'author' => 'Stackcode',
    'author_uri' => 'https://stackposts.com',
    'desc' => 'Save you time on Tinder',
    'icon' => 'fas fa-fire',
    'color' => '#FE3C72',
    'parent' => [
        "id" => "tinder_auto_like",
        "name" => "Tinder auto like"
    ],
    'menu' => [
        'tab' => 2,
        'type' => 'top',
        'position' => 990,
        'name' => 'Tinder auto like'
    ],
    "js" => [
        //'Assets/js/Tinder.js'
    ],
    'cron' => [
        'name' => 'Tinder auto like',
        'uri' => 'tinder_auto_like/cron',
        'style' => '* * * * *',
    ]
];
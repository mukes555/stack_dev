<?php
return [
    'id' => 'instagram_profiles',
    'folder' => 'core',
    'name' => 'Instagram profiles',
    'author' => 'Stackcode',
    'author_uri' => 'sp',
    'desc' => 'Customize system interface',
    'icon' => 'fab fa-instagram',
    'color' => '#d62976',
    'position' => '4000',
    'parent' => [
        "id" => "instagram",
        "name" => "Instagram"
    ],
    "js" => [
        'Assets/js/instagram_profiles.js',
    ],
];
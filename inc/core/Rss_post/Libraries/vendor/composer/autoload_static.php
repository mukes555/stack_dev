<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitad33f61a10a343cd664d6b4e0a341b05
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Feed' => __DIR__ . '/..' . '/dg/rss-php/src/Feed.php',
        'FeedException' => __DIR__ . '/..' . '/dg/rss-php/src/Feed.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitad33f61a10a343cd664d6b4e0a341b05::$classMap;

        }, null, ClassLoader::class);
    }
}
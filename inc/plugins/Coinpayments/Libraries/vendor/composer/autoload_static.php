<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3336369b689202b7054e9a5c6a083420
{
    public static $classMap = array (
        'CoinpaymentsAPI' => __DIR__ . '/..' . '/coinpaymentsnet/coinpayments-php/src/CoinpaymentsAPI.php',
        'CoinpaymentsCurlRequest' => __DIR__ . '/..' . '/coinpaymentsnet/coinpayments-php/src/CoinpaymentsCurlRequest.php',
        'CoinpaymentsValidator' => __DIR__ . '/..' . '/coinpaymentsnet/coinpayments-php/src/CoinpaymentsValidator.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit3336369b689202b7054e9a5c6a083420::$classMap;

        }, null, ClassLoader::class);
    }
}
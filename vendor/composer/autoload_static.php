<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit77cdb05f3c6768fad4f2dc9de4f73bf3
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'GlobalPayments\\Api\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'GlobalPayments\\Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/globalpayments/php-sdk/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit77cdb05f3c6768fad4f2dc9de4f73bf3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit77cdb05f3c6768fad4f2dc9de4f73bf3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
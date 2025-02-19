<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcdc8a9c540fbb9acee3e3f79d3079cc2
{
    public static $files = array (
        'ac548b80908778fbd23763cdf0403c52' => __DIR__ . '/../..' . '/functions/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'STORE_MANAGER\\Rest\\' => 19,
            'STORE_MANAGER\\Frontend\\' => 23,
            'STORE_MANAGER\\Backend\\' => 22,
            'STORE_MANAGER\\App\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'STORE_MANAGER\\Rest\\' => 
        array (
            0 => __DIR__ . '/../..' . '/rest',
        ),
        'STORE_MANAGER\\Frontend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/frontend',
        ),
        'STORE_MANAGER\\Backend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend',
        ),
        'STORE_MANAGER\\App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcdc8a9c540fbb9acee3e3f79d3079cc2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcdc8a9c540fbb9acee3e3f79d3079cc2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

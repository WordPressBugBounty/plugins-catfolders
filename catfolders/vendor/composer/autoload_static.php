<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit31254149aee6dc3deee6fc1587b115a8
{
    public static $files = array (
        '11298418fac2d36d6d38c7c3efc5b206' => __DIR__ . '/..' . '/10quality/wp-query-builder/src/Lib/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'e' => 
        array (
            'enshrined\\svgSanitize\\' => 22,
        ),
        'T' => 
        array (
            'TenQuality\\WP\\Database\\' => 23,
            'TenQuality\\Data\\' => 16,
        ),
        'C' => 
        array (
            'CatFolders\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'enshrined\\svgSanitize\\' => 
        array (
            0 => __DIR__ . '/..' . '/enshrined/svg-sanitize/src',
        ),
        'TenQuality\\WP\\Database\\' => 
        array (
            0 => __DIR__ . '/..' . '/10quality/wp-query-builder/src',
        ),
        'TenQuality\\Data\\' => 
        array (
            0 => __DIR__ . '/..' . '/10quality/php-data-model/src',
        ),
        'CatFolders\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit31254149aee6dc3deee6fc1587b115a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit31254149aee6dc3deee6fc1587b115a8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit31254149aee6dc3deee6fc1587b115a8::$classMap;

        }, null, ClassLoader::class);
    }
}

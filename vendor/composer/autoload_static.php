<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd35ad648fd23cb9114bafa4f1b7b3786
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd35ad648fd23cb9114bafa4f1b7b3786::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd35ad648fd23cb9114bafa4f1b7b3786::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd35ad648fd23cb9114bafa4f1b7b3786::$classMap;

        }, null, ClassLoader::class);
    }
}

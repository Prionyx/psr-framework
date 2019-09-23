<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9d751718f31f799eb0bba42fe0748a1f
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Framework\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Framework\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Framework',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9d751718f31f799eb0bba42fe0748a1f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9d751718f31f799eb0bba42fe0748a1f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

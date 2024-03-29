<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd800f47156647026a3e3f063c69342a5
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MyProject\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MyProject\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/MyProject',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd800f47156647026a3e3f063c69342a5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd800f47156647026a3e3f063c69342a5::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitd800f47156647026a3e3f063c69342a5::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}

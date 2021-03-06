<?php

namespace MyPagesTest;

use Zend\Loader\AutoloaderFactory;
use RuntimeException;

class Bootstrap
{
    public static function init()
    {
        static::initAutoloader();
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (is_readable($vendorPath . '/autoload.php')) {
            $loader = include $vendorPath . '/autoload.php';

            return;
        }

        $zf2Path = getenv('ZF2_PATH') ?:
            (defined('ZF2_PATH') ? ZF2_PATH :
                (is_dir($vendorPath . '/ZF2/library') ? $vendorPath . '/ZF2/library' : false)
            );

        if (!$zf2Path) {
            throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
        }

        if (isset($loader)) {
            $loader->add('Zend', $zf2Path . '/Zend');
        } else {
            include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
            include $zf2Path . '/Zend/Loader/ClassMapAutoloader.php';
            AutoloaderFactory::factory([
                'Zend\Loader\ClassMapAutoloader' => [
                    __DIR__. '/../autoload_classmap.php',
                ],
                'Zend\Loader\StandardAutoloader' => [
                    'autoregister_zf' => true,
                    'namespaces' => [
                        __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                        'MyPages' => __DIR__ . '/../src/MyPages',
                    ],
                ],
            ]);
        }

    }

    /**
     * @param string $path
     */
    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }
}

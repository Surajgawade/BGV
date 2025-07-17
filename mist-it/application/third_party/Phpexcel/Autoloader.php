<?php

namespace PhpOffice\PhpSpreadsheet;

class Autoloader
{
    /**
     * Register the Autoloader with SPL
     */
    public static function register()
    {
        if (function_exists('__autoload')) {
            // Register any existing autoloader function with SPL, so we don't get any clashes
            spl_autoload_register('__autoload');
        }
        // Register ourselves with SPL
        return spl_autoload_register([\PhpOffice\PhpSpreadsheet\Autoloader::class, 'load'], true, true);
    }

    /**
     * Autoload a class identified by name
     *
     * @param  string  $className  Name of the object to load
     */
    public static function load($className)
    {
        $prefix = 'PhpOffice\\PhpSpreadsheet\\';
        if ((class_exists($className, false)) || (strpos($className, $prefix) !== 0)) {
            // Either already loaded, or not a PhpSpreadsheet class request
            return false;
        }

        $classFilePath = __DIR__ . DIRECTORY_SEPARATOR .
            'PhpSpreadsheet' . DIRECTORY_SEPARATOR .
            str_replace([$prefix, '\\'], ['', '/'], $className) .
            '.php';

        if ((file_exists($classFilePath) === false) || (is_readable($classFilePath) === false)) {
            // Can't load
            return false;
        }
        require $classFilePath;
    }
}

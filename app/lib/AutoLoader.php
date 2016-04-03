<?php
define('PHP_FILE_EXTENSION', '.php');
define ('NAMESPACE_DELIMITER', '\\');

/**
 * Auto loader
 * @param string $className
 * @return bool
 */
function autoload($className)
{
    $className = str_replace(NAMESPACE_DELIMITER, DIRECTORY_SEPARATOR, $className) . PHP_FILE_EXTENSION;
    if (file_exists($className)) {
        return require_once $className;
    }
    return false;
}

spl_autoload_register("autoload");
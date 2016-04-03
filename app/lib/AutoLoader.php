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



    $class = "app/lib/database/" . str_replace("_", "/", strtolower($className)) . ".php";

    if (file_exists($class)) {
        return require_once($class);
    }
    
    return false;
}

spl_autoload_register("autoload");
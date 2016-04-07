<?php

namespace SocialNetwork\app\lib\traits;

/**
 *
 * @author j
 * Date: 10/6/15
 * Time: 8:38 PM
 *
 * File: ClassExists.php
 */

trait ClassExists
{

    /**
     * check for factories if a class does exist
     *
     * @param string $namespace
     * @param string $name
     *
     * @return bool
     */
    public function classExists($namespace, $name)
    {
        return self::classExistsStatic($namespace, $name);
    }

    /**
     * @param string $namespace
     * @param string $name
     * @return string mixed
     */
    public static function classExistsStatic($namespace, $name)
    {
        $className = "$namespace\\$name";
        return class_exists($className, true);
    }
}
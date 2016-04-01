<?php

namespace transformer;
use traits\ClassExists;

/**
 *
 * @author j
 * Date: 10/6/15
 * Time: 8:37 PM
 *
 * File: TransformerFactory.php
 */

class TransformerFactory
{

    /**
     * @trait
     */
    use ClassExists;

    /**
     * @var array
     */
    private static $runtimeCache = [];


    /**
     * @param string $name
     * @return null|mixed
     */
    public static function makeStatic($name)
    {
        if (isset(self::$runtimeCache[$name])) {
            return self::$runtimeCache[$name];
        }

        if (!self::classExistsStatic(__NAMESPACE__, $name)) {
            return null;
        }

        $className = __NAMESPACE__ . '\\' . $name;

        self::$runtimeCache[$name] = $className();

        return self::$runtimeCache[$name];
    }


    /**
     * @param string $name
     *
     * @return null|mixed
     */
    public function make($name)
    {
        // transformers ares stateless we can reuse them as we like
        if (isset(self::$runtimeCache[$name])) {
            return self::$runtimeCache[$name];
        }

        if (!$this->classExists(__NAMESPACE__, $name)) {
            return null;
        }
        $className = __NAMESPACE__ . '\\' . $name;

        self::$runtimeCache[$name] = $className();

        return self::$runtimeCache[$name];
    }

    /**
     * @param array $array
     * @return \SplObjectStorage
     */
    public function makeFromArray(array $array)
    {
        $storage = new \SplObjectStorage();
        foreach ($array as $transformerName) {
            $transformer = $this->make($transformerName);
            if ($transformer) {
                $storage->attach($transformer);
            }
        }
        return $storage;
    }

}
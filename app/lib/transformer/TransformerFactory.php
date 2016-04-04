<?php
namespace SocialNetwork\app\lib\transformer;
use SocialNetwork\app\lib\traits\ClassExists;


/**
 * Class TransformerFactory
 * @package SocialNetwork\app\lib\transformer
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

        self::$runtimeCache[$name] = new $className();

        return self::$runtimeCache[$name];
    }


    /**
     * @param string $name
     *
     * @return null|mixed
     */
    public function make($name)
    {
        return static::makeStatic($name);
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

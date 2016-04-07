<?php
namespace SocialNetwork\app\lib\model;

use SocialNetwork\app\lib\BaseModel;
use SocialNetwork\app\lib\traits\ClassExists;

class ModelFactory
{
    use ClassExists;

    const MODEL_NAMESPACE = 'SocialNetwork\app\model';

    /**
     * @param string $modelName
     * @return BaseModel
     */
    public static function make($modelName) 
    {
        if (!$modelName) {
            throw new \RuntimeException('no ModelName was given');
        }
        
        if (!self::classExistsStatic(self::MODEL_NAMESPACE, $modelName)){
            throw new \RuntimeException('Model does not exist');
        }

        $className = self::MODEL_NAMESPACE . '\\'. $modelName;

        return new $className();
    }
}
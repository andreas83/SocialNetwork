<?php
namespace validator;
use transformer\string\NameSpacePrepend;

/**
 * Created by PhpStorm.
 * User: j
 * Date: 28.09.15
 * Time: 23:32
 */

class ValidatorFactory
{
    /**
     * @param array $validatorList
     */
    public function make(array $validatorNameList = [])
    {
        $validatorStorage = new \SplObjectStorage();
        if (!$validatorNameList) {
            return $validatorStorage;
        }

        $transformer = new NameSpacePrepend();
        foreach ($validatorNameList as $validatorName) {
            $validatorName = $transformer($validatorName,
                [
                    NameSpacePrepend::NAMESPACE_OPTION_INDEX => __NAMESPACE__
                ]
            );
       }
        
    }
}
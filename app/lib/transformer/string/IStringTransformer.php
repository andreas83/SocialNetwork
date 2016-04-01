<?php

namespace transformer\string;

/**
 * Created by PhpStorm.
 * User: j
 * Date: 28.09.15
 * Time: 23:49
 */

/**
 * Interface IStringTransformer
 * @package transformer
 */
interface IStringTransformer {

    /**
     * @param string $content
     * @param mixed $options
     *
     * @return string
     */
    public function transform($content, $options);

    /**
     * @param string $content
     * @param mixed $options
     *
     * @return string
     */
    public function __invoke($content, $options);
}
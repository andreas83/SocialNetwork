<?php
namespace transformer\string\html;

/**
 *
 * @author j
 * Date: 10/7/15
 * Time: 8:41 PM
 *
 * File: NoOptionsTransformerTrait.php
 */

trait NoOptionsTransformerTrait
{
    /**
     * @param string $content
     * @param mixed $options
     *
     * @return string
     */
    public function transform($content, $options)
    {
        if (!$content) {
            return $content;
        }

        return preg_replace(self::PATTERN, self::REPLACE_PATTEN, $content);
    }

    /**
     * @param string $content
     * @param mixed $options
     *
     * @return string
     */
    public function __invoke($content, $options)
    {
        return $this->transform($content, $options);
    }
}
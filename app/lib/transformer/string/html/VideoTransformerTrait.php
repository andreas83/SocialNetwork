<?php
namespace app\lib\transformer\string\html;

/**
 * Class VideoTransformerTrait
 * @package app\lib\transformer\string\html
 */
trait VideoTransformerTrait
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

        $width = (isset($options['width'])) ? $options['width'] : "";
        $height = (isset($options['height'])) ? $options['height'] : "";

        return preg_replace(self::PATTERN, sprintf(self::REPLACE_PATTEN, $width, $height), $content);
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
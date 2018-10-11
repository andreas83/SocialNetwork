<?php
namespace SocialNetwork\app\lib\transformer\string\html;

/**
 * Class NoOptionsTransformerTrait
 * @package SocialNetwork\app\lib\transformer\string\html
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

        return preg_replace(self::PATTERN, sprintf(self::REPLACE_PATTEN, $options['url']), $content);
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

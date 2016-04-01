<?php
namespace transformer\string;

/**
 * Created by PhpStorm.
 * User: j
 * Date: 28.09.15
 * Time: 23:49
 */

/**
 * Class NameSpacePrepend
 * @package transformer\string
 */
class NameSpacePrepend implements IStringTransformer
{

    /**
     * @var string
     */
    const NAMESPACE_DELIMITER = '\\';

    /**
     * key name in the array
     *
     * @var string
     */
    const NAMESPACE_OPTION_INDEX = 'namespace';

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

    /**
     * @param string $content
     * @param mixed $options
     *
     * @return string
     */
    public function transform($content, $options)
    {
        if (strpos($content, self::NAMESPACE_DELIMITER) === false) {
            $content = self::NAMESPACE_DELIMITER . $content;
        }

        if (!isset($options[self::NAMESPACE_OPTION_INDEX])) {
            return $content;
        }

        return self::NAMESPACE_DELIMITER . $options[self::NAMESPACE_OPTION_INDEX] . $content;
    }


}
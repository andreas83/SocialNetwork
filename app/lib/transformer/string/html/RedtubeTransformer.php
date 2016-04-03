<?php
namespace app\lib\transformer\string\html;
use app\lib\transformer\string\IStringTransformer;

/**
 * Class RedtubeTransformer
 * @package app\lib\transformer\string\html
 */
class RedtubeTransformer implements IStringTransformer
{
    /**
     * the transform and invoke actions are inside the trait -> DRY
     */
    use VideoTransformerTrait;

    /**
     * pattern for search and replace
     */
    const PATTERN = "/((http|https)\:\/\/)?([w]{3}\.)?redtube.com\/(\d+)+/i";
    const REPLACE_PATTEN = '<iframe src="http://embed.redtube.com/?id=$4" width="%s" height="%s" frameborder="0" scrolling="no"></iframe>';

}
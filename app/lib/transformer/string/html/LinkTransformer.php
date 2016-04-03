<?php
namespace SocialNetwork\app\lib\transformer\string\html;
use SocialNetwork\app\lib\transformer\string\IStringTransformer;

/**
 * Class LinkTransformer
 * @package SocialNetwork\app\lib\transformer\string\html
 */
class LinkTransformer implements IStringTransformer
{
    /**
     * DRY
     */
    use NoOptionsTransformerTrait;

    /**
     * pattern for search and replace
     */
    const PATTERN = "@((www|http://|https://)[^ ]+)@";
    const REPLACE_PATTEN = '<a href="\1">\1</a>';
}

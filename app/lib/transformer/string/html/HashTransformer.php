<?php
namespace SocialNetwork\app\lib\transformer\string\html;

use SocialNetwork\app\lib\transformer\string\IStringTransformer;

/**
 * Class HashTransformer
 * @package SocialNetwork\app\lib\transformer\string\html
 */
class HashTransformer implements IStringTransformer
{
    /**
     * DRY
     */
    use NoOptionsTransformerTrait;

    /**
     * pattern for search and replace
     */
    const PATTERN = '/(^|\s)#(\w*[a-zA-Z0-9öäü_-]+\w*)/';
    const REPLACE_PATTEN = '\1<a href="%s/hash/\2">#\2</a>';
}

<?php
namespace SocialNetwork\app\lib\transformer\string\html;
use SocialNetwork\app\lib\transformer\string\IStringTransformer;

/**
 * Class WebVideoTransformer
 * @package SocialNetwork\app\lib\transformer\string\html
 */
class WebVideoTransformer implements IStringTransformer
{
    /**
     * the transform and invoke actions are inside the trait -> DRY
     */
    use VideoTransformerTrait;

    /**
     * pattern for search and replace
     */
    const PATTERN = "/((.*)+.(mp4|webm|mpeg)$)/i";
    const REPLACE_PATTEN = '<video width="%s" height="%s" autoplay="autoplay" loop controls ><source src="$1" type="video/$3">Your browser does not support the video tag or webm</video>';
}
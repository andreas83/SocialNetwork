<?php
namespace app\lib\transformer\string\html;


use app\lib\transformer\string\IStringTransformer;

/**
 * Class YoutubeTransformer
 * @package app\lib\transformer\string\html
 */
class YoutubeTransformer implements IStringTransformer
{
    /**
     * the transform and invoke actions are inside the trait -> DRY
     */
    use VideoTransformerTrait;

    /**
     * pattern for search and replace
     */
    const PATTERN = "/(.+(\?|&)v=([a-zA-Z0-9_-]+).*)|https\:\/\/youtu.be\/([a-zA-Z0-9_-]+).*/";
    const REPLACE_PATTEN = '<iframe id="ytplayer" type="text/html"  width="%s" height="%s" src="https://www.youtube.com/embed/$3$4" frameborder="0"></iframe>';
}
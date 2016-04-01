<?php
namespace transformer\string\html;
use transformer\string\IStringTransformer;

/**
 *
 * @author j
 * Date: 10/7/15
 * Time: 8:29 PM
 *
 * File: WebVideoTransformer.php
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
    const PATTERN = "/((http|https)\:\/\/)?([w]{3}\.)?vimeo.com\/(\d+)+/i";
    const REPLACE_PATTEN = '<iframe src="http://player.vimeo.com/video/$4?badge=0" width="%s" height="%s" frameborder="0" webkitAllowFullScreen="true" mozallowfullscreen="true" allowFullScreen="true"></iframe>';
}
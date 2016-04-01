<?php
namespace transformer\string\html;
use transformer\string\IStringTransformer;

/**
 *
 * @author j
 * Date: 10/7/15
 * Time: 8:25 PM
 *
 * File: RedtubeTransformer.php
 */

/**
 * Class RedtubeTransformer
 *
 * @package transformer\string\html
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
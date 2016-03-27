<?php
namespace transformer\string\html;
use transformer\string\IStringTransformer;


/**
 *
 * @author j
 * Date: 10/7/15
 * Time: 8:35 PM
 *
 * File: LinkTransformer.php
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
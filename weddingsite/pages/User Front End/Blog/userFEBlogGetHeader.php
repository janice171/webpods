<?php

/**
 * Wedding site User Front End Blog processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* If we are a FE blog page, output the header */
$parent = $modx->resource->get('parent');
$parentResource = $modx->getObject('modResource', $parent);
if ( !$parentResource ) return;

$parentTitle = $parentResource->get('longtitle');
if ( $parentTitle == 'blog' ) {
    $header = $modx->getChunk('userFEBlogPageHeader');
    return $header;
}

return;

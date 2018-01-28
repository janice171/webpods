<?php

/**
 * Wedding site userGenerateFEWeblinks
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Generates the links in the FE for user domains
 * 
 *  
 *  Parameters :- None
 * 
 * 
 * Returns - html for the generated links
 * 
 */
/* Get the parent container */
$parent = $modx->resource->get('parent');

/* Add the back to Blogs link if we are on a blog page */
$output = "";
$parentResource = $modx->getObject('modResource', $parent);
$type = $parentResource->get('longtitle');

if ($type == 'blog') {

    /* Move the parent up 1 level to the users FE top, not
     * the top of the blogs.
     */
     $parent= $parentResource->get('parent');
}

/* Call Wayfinder as we would normally but with a 'full' url */
$output .= $modx->runSnippet('Wayfinder', array('startId' => $parent,
    'outerTpl' => 'wed1-Outer',
    'rowTpl' => 'wed1-Row',
    'level' => 1,
    'hereClass' => 'active',
    'textOfLinks' => 'pagetitle',
    'scheme' => 'full'));

/* If we are previewing leave the URLS alone, otherwise make them 
 * user domain relative.
 */
$urlType = $modx->runSnippet('userGetURLType');
if ($urlType != 'front')
    return $output;

/* Get the website name */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Replace the Wayfinder generated 'www' with the website domain */
$output = str_replace('www', $website, $output);

/* Remove the 'users' path */
$output = str_replace("/users/$website", '', $output);

return $output;
​
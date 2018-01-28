<?php
/**
 * Wedding site userGenerateFEBloglinks
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Generates the links in the FE for the users blogs
 * 
 *  
 *  Parameters :- None
 * 
 * 
 * Returns - html for the generated links
 * 
 */

/* Get the parents, which is where we are */
$parent = $modx->resource->get('id');

/* Call getArchive as we would normally but with a 'full' url*/
$output = $modx->runSnippet('getArchives', array('parents' => $parent,
                                               'tpl' => 'userFEBlogArchiveTemplate',
                                               'limit' => '50',
                                               'sortby' => 'editedon',
                                               'includeContent' => 1));

/* If we are previewing leave the URLS alone, otherwise make them 
 * user domain relative.
 */        
$urlType = $modx->runSnippet('userGetURLType');
if ( $urlType != 'front') return $output;

/* Get the website name */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Replace the Wayfinder generated 'www' with the website domain */
$output = str_replace('www', $website, $output);

/* Remove the 'users' path */
$output = str_replace("/users/$website", '', $output);

return $output;

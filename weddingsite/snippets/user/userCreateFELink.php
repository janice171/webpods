<?php
/**
 * Wedding site userCreateFELink
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates FE links. MUST use this to do this so all FE links
 * not in the sidebar, ie Wayfinder generated are generated relative 
 * to the users FE website(subdomain).
 * 
 * Parameters :- 
 * 
 * website - the users website name
 * id - the page id of the link
 * params - an array of parameters
 * 
 * Returns 
 * 
 * The front end link
 *  
 */

/* Make the link as normal using makeURL */
$context = $modx->context->get('key');
$link = $modx->makeURL($id, $context, $params, 'full');

/* Check the URL type, if not front return the www domain link */
$urlType = $modx->runSnippet('userGetURLType');
if ( $urlType != 'front' ) return $link;

/* Substitute the www for the users website and lose the trailing path */
$output = str_replace('www', $website, $link);
$output = str_replace("/users/$website", '', $output);

return $output;

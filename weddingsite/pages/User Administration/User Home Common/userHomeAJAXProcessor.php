<?php
/**
 * Wedding site User home AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Assemble the parameters from the request data */
$id = $_REQUEST['id'];

/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ( $user ) $userId = $user->get('id');
if ( $userId == "" ) {
    
    echo "Error! No logged in user";
    return;
}

/* Toggle the current state of published */
$resource = $modx->getObject('modResource', $id);
if ( !$resource ) return "Error - can't find resource";
$published = $resource->get('published');
$newPublished = 0;
$output = 'off';
if ( $published == 0 ) {
    
    $newPublished = 1;
    $output = "on";
}
$resource->set('published', $newPublished);
$resource->save();

/* Clear the cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache(array (
        "{$resource->context_key}/",
    ),
    array(
        'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
        'publishing' => true
    )
);

/* Return the output */
echo $output;



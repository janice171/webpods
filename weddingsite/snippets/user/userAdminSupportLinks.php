<?php
/**
 * Wedding site userAdminSupportLinks
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Draws the user admin area sidebar support links
 * 
 *  
 *  Parameters :-
 * 
 * attributes - an array of the users attributes
 * 
 * Returns - nothing, sets placeholders only
 */
$userSupportContainerId = $modx->getOption('userSupportContainerId');
$supportContainer = $modx->getObject('modResource', $userSupportContainerId);
$children = $supportContainer->getMany('Children');
$context = $modx->context->get('key');
$supportOutput = "";
foreach ( $children as $child ) {
    
    $id = $child->get('id');
    $title = $child->get('pagetitle');
    $pageLink = $modx->makeURL($id, $context, "", "full");
    $linkParams = array('userHomeSupportTitle' =>  $title ,
                        'userHomeSupportLink' => $pageLink);
                                     
    
    $supportOutput .= $modx->getChunk('userHomeSupportBody', $linkParams);
}

$modx->setPlaceholder('userHomeSupportBody', $supportOutput);


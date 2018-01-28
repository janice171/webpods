<?php

/**
 * Wedding site User Edit Blog processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  Initialise the users blog page */
        
/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditBlogError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditBlogError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$context = $modx->context->get('key');

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

/* Initialise the common form part */
$modx->runSnippet('userEditCommonInitialise');

/* Get the users blog list */
if ( !isset($_REQUEST['userPage'])) {
    
    $modx->setPlaceholder('userEditBlogError', "Error - No user page id found");
    return;
}
$blogPageId = $_REQUEST['userPage'];
$modx->setPlaceholder('userPageId', $blogPageId);

/* Get the resource and its children */
$blogResource = $modx->getObject('modResource', array('id' => $blogPageId));
if ( !$blogResource ) {
    
    $modx->setPlaceholder('userEditBlogError', "Error - No blog resource found");
    return;
}

$children = $blogResource->getMany('Children');
$output = "";
foreach ( $children as $child ) {
    
    $id = $child->get('id');
    $args = array('id' => $id);
    $childLink = $modx->makeURL($id, $context, $args, "full");
    $title = $child->get('pagetitle');
    $editedOn = $child->get('editedon');
    $editedOnTime = strtotime($editedOn);
    $edited = strftime('%d/%m/%y', $editedOnTime);
    $params = array('userEditBlogDate' => $edited,
                    'userEditBlogLink' => $childLink,
                    'userEditBlogTitle' => $title,
                    'userEditBlogAJAXLink' => $childLink);
    $output .= $modx->getChunk('userEditBlogListItem', $params);
    
}

/* Set the placeholder */
$modx->setPlaceholder('userEditBlogList', $output);



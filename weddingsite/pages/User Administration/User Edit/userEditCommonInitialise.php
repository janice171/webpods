<?php
/**
 * Wedding site User Edit Common processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditCommonError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditCommonError', "Error - No user found please log in");
    return;
}

/* Get the users FE page id */
$userPageId = $_REQUEST['userPage'];

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$pages = $weddingUser->getMany('Pages');
foreach ( $pages as $page ) {
    if ( $page->get('pageId') == $userPageId ) {
        $pageSelected = $page->toArray();
        break;
    }
}



/* Set the user page id placeholder */
$modx->setPlaceholder('userPageId', $userPageId);

/* Set the page title */
$userPage = $modx->getObject('modResource', $userPageId );
if ( !$userPage ) {
    $modx->setPlaceholder('userEditCommonError', "Error - No user page found");
    return;
}
$title = $userPage->get('pagetitle');
$modx->setPlaceholder('userEditCommonPageTitle', $title);

/* Set the webpage active/inactive status */
$published = $userPage->get('published');
if ( $published == 1 ) {
    
    $modx->setPlaceholder('userEditCommonYesSelected', 'checked'); 

} else {

    $modx->setPlaceholder('userEditCommonNoSelected', 'checked'); 
   
}

/* Set the page protected status */
$protected = $pageSelected['passwordProtect'];
if ( $protected == 1 ) {
    
    $modx->setPlaceholder('userEditCommonProtectYesSelected', 'checked'); 

} else {

    $modx->setPlaceholder('userEditCommonProtectNoSelected', 'checked'); 
   
}


/* Set the site preview button link */
$context = $modx->context->get('key');
$previewLink = $modx->makeURL($userPageId, $context, "", "full");
$ready = '<script>$(document).ready(function(){
    
   
   /* Site preview */
   $(".userPreview").attr("href", "' . $previewLink . '");
        
                 
});</script>';
$modx->regClientStartupScript($ready, true);


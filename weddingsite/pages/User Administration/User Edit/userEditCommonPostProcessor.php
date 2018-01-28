<?php

/**
 * Wedding site User Edit Common processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Check for and process a form submission */
if ( isset($_REQUEST['userEditCommonFormSubmit']) ) {
            
    /* Get the current logged in user details */
    $user = $modx->user;
    $userId = $user->get('id');
    if ( $userId == "" ) {
        $modx->setPlaceholder('userEditCommonError', "Error - No user found please log in");
        return;
    }
    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ( $weddingUserObject == null ) {
        $modx->setPlaceholder('userEditCommonError', "Error - No user found please log in");
        return;
    }
    
    
    /* Get the users FE page id */
    $userPageId = $_REQUEST['userPage'];

    $weddingUser = $weddingUserObject->getOne('Attributes');
    $pages = $weddingUserObject->getMany('Pages');
    foreach ( $pages as $page ) {
        if ( $page->get('pageId') == $userPageId ) {
            $pageSelected = $page;
            break;
        }
    }
    
    /* Check for page attribute change */
    
    /* Get the page id and the page being edited */
    $userPageId = $_REQUEST['userCommonUserPageId'];
    $userPage = $modx->getObject('modResource', $userPageId );
    if ( !$userPage ) {
        $modx->setPlaceholder('userEditCommonError', "Error - No user page found");
        return;
    } 
    
    /* Title */
    if (isset($_REQUEST['userCommonPageTitle'])) {
    
        $title = $userPage->get('pagetitle');
        $newTitle = strip_tags($_REQUEST['userCommonPageTitle']);
        if ( $newTitle == "" ) return;
        if ( $title != $newTitle) {
                 
            $userPage->set('pagetitle', $newTitle);
            $userPage->save();
        }
    }
    
     /* Web page active(published) */
    if (isset($_REQUEST['userCommonPageActive'])) {
        
        $userPage->set('published', $_REQUEST['userCommonPageActive']);
        $userPage->save();
    }
    
     /* Web page protected */
    if (isset($_REQUEST['userCommonPageProtect'])) {
        
        $pageSelected->set('passwordProtect', $_REQUEST['userCommonPageProtect']);
        $pageSelected->save();
       
    }
}
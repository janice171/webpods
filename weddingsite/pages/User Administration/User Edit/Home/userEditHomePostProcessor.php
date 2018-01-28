<?php

/**
 * Wedding site User Edit Home processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Check for and process a form submission */
if ( isset($_REQUEST['userEditWebsiteProtectSubmit']) ) {
            
    /* Get the current logged in user details */
    $user = $modx->user;
    $userId = $user->get('id');
    if ( $userId == "" ) {
        $modx->setPlaceholder('userEditHomeError', "Error - No user found please log in");
        return;
    }
    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ( $weddingUserObject == null ) {
        $modx->setPlaceholder('userEditHomeError', "Error - No user found please log in");
        return;
    }
    $weddingUser = $weddingUserObject->getOne('Attributes');
    
    $status = $_REQUEST['userEditWebsiteProtect'];
    $weddingUser->set('websiteActive', $status);
    if (!$weddingUser->save()) {
	    $modx->setPlaceholder('userEditHomeError', "Error - Failed to update this wedding user");
	}
    
}

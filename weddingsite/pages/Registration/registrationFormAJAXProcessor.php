<?php
/**
 * Wedding site Registration processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Validate the nominated remote fields, validator requires a simple 'true' or 'false' 
 *  value returning, indicating passed validation or failed respectively */

/* Email */
if ( $_GET['registrationEmail'] != "" ) {
	
    /* Check if we already have a user with this name*/
    $userEmail = $_GET['registrationEmail'];
    $user = $modx->getObject('modUser', array('username' => $userEmail));
    
    if ( $user ) {
		return 'false';
	} else {
		return 'true';
	}
}

/* Website name */
if ($_GET['userRegistrationWebsiteName'] != "") {

    /* Check if we already have one in use by another user */
    $websiteName = $_GET['userRegistrationWebsiteName'];
    $user = $modx->getObject('weddingUserAttribute', array('website' => $websiteName));

    if  ( $user ) {
        return 'false';
    } else {
        return 'true';
    }
}

/* If we have reached here its an unknown field so fail validation */
return 'false';


<?php
/**
 * Wedding site Guests add/edit  processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/*  The parameters from the settings forms form are already valid at this point, we only need
 * to update the wedding user
 */

/* Check for a guest add/edit submit operation */
if ( isset($_REQUEST['userGuestsName']) ) {
	
	/* Get the MODX user */
    $user = $modx->user;
    $userId = $user->get('id');
    if ( $userId == "" ) {
        $modx->setPlaceholder('userSettingError', "Error - No MODX user found please log in");
        return;
    }
    
    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ( $weddingUserObject == null ) {
        $modx->setPlaceholder('userSettingError', "Error - No Wedding user found please log in");
        return;
    }
	
    /* Check for a guest Id, if none this is an add */
    if ( $_REQUEST['userGuestsId'] == "" ) {
        
        $guestObject = $modx->newObject('weddingUserGuest');
        $updateType = "Add";
        
    } else {
        
    $guestObject = $modx->getObject('weddingUserGuest', 
                                     array('id' => $_REQUEST['userGuestsId']));
    $updateType ="Update"; 
    
    }
    
    if ( $guestObject == null ) {
        $modx->setPlaceholder('userSettingError', "Error - No Guest with this ID found ");
        return;
    }
    
    $guestObject->set('name', $_REQUEST['userGuestsName']);
    $guestObject->set('email', $_REQUEST['userGuestsEmail']);
    $guestObject->set('address1', $_REQUEST['userGuestsAddressLine1']);
    $guestObject->set('address2', $_REQUEST['userGuestsAddressLine2']);
    $guestObject->set('city', $_REQUEST['userGuestsCity']);
    $guestObject->set('postCode', $_REQUEST['userGuestsPostcode']);
    $guestObject->set('telephone', $_REQUEST['userGuestsTel']);
    $guestObject->set('guestOf', $_REQUEST['userGuestsGuestOf']);
    $active = 0;
    if ( isset($_REQUEST['userGuestsActive'])) $active = 1;
	$guestObject->set('active', $active);
        
    if ( $updateType == "Add" ) {
        
        $weddingUserObject->addMany($guestObject, 'Guests');
        if ( $weddingUserObject->save() == false ) {
            $modx->setPlaceholder('userSettingError', "Error - Failed to create this wedding user guest"); 
            return;
        }
        
        /* Process any associated events */
        $guestId = $modx->lastInsertId();
        $c = $modx->newQuery('weddingUserEvent');
        $c->where(array('user' => $userId));      
        $events = $modx->getCollection('weddingUserEvent',$c);
        
        foreach ( $events as $event ) {
            
            $eventId = $event->get('id');
            $eventName = $event->get('name');
            
            /* Create the checkbox name */
            $checkboxName = str_replace( ' ', '', $eventName );
            $checkboxName = "userGuests" . $checkboxName;
            
            /* Check if we have an association, no need to check for existence */
            if ( $_REQUEST[$checkboxName] == 1 ) {
                
                /* Create one */
                $guestEventObject = $modx->newObject('guestEvents');
                $guestEventObject->set('guestId',$guestId);
                $guestEventObject->set('eventId',$eventId);            
                $guestEventObject->save();
            }
            
        }
        
    } else {
        
        if (!$guestObject->save()) {
            $modx->setPlaceholder('userSettingError', "Error - Failed to update this wedding user guest");    
        }
    }
    
    /* Redirect back to the guest list */
    $context = $modx->context->get('key');
    $guestPageId = $modx->getOption('guestPageId');
    $pageURL = $modx->makeURL($guestPageId, $context, "", "full");
    header("Location: {$pageURL}");
}



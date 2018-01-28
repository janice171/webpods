<?php
/**
 * Wedding site getUserManagementPage
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets a users management page link
 * 
 * Parameters :- 
 * 
 * $page - one of guest, event, invite, home, setting
 *  
 */

/* Get the user details */
$context = $modx->context->get('key');
if ( !$modx->user->isAuthenticated($context) ) return "Error no authenticated user";

switch ( $page ) {
    
    case 'event' :
        
        $eventPageId = $modx->getOption('eventPageId');
        $pageLink = $modx->makeURL($eventPageId, $context, "", 'full');
        $pageLink = htmlspecialchars_decode($pageLink);
        break;
    
    case 'guest' :
        
        $guestPageId = $modx->getOption('guestPageId');
        $pageLink = $modx->makeURL($guestPageId, $context, "", 'full');
        $pageLink = htmlspecialchars_decode($pageLink);
        break;
    
    case 'invite' :
        
        $invitePageId = $modx->getOption('invitePageId');
        $pageLink = $modx->makeURL($invitePageId, $context, "", 'full');
        $pageLink = htmlspecialchars_decode($pageLink);
        break;
    
    case 'setting' :
        
        $settingPageId = $modx->getOption('settingPageId');
        $pageLink = $modx->makeURL($settingPageId, $context, "", 'full');
        $pageLink = htmlspecialchars_decode($pageLink);
        break;
    
    default :
    case 'home' :
    
        $homePageId = $modx->getOption('homePageId');
        $pageLink = $modx->makeURL($homePageId, $context, "", 'full');
        $pageLink = htmlspecialchars_decode($pageLink);
        break;
}

return $pageId;
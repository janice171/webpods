<?php
/**
 * Wedding site User Invites processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user)
    $userId = $user->get('id');
if ($userId == "") {

    echo "Error! No logged in user";
    return;
}

/* Check for a command */
if ($_REQUEST['command'] == "iframeLoad") {
	
	/* Ignore the initial load */
	if ( $_REQUEST['initial'] == 'yes') return;
	
	/* Parameter check */
	$eventId = $_REQUEST['event'];
	if (  $eventId == 0 ) {
		
		$output = $modx->getChunk('userInvitesNoEvents');
		return $output;
	}
	
	$guestEventId = $_REQUEST['guest'];
	if ( $guestEventId == 0 ) {
		
		$output = $modx->getChunk('userInvitesNoGuests');
		return $output;
	}
	
	$themeName = $_REQUEST['design'];
	
	/* Load the design and fill in the event/guest parameters */
	$weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ($weddingUserObject == null) {
        $errorText = "Error - No Wedding user found please log in";
        return $modx->getChunk('userInvitesThemeError', array('errorText' => $errorText));
    }

    $userProfile = $user->getOne('Profile');
    $profile = $userProfile->toArray();
    $weddingUser->attributes = $weddingUserObject->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();
    $context = $modx->context->get('key');
    
    $event = $modx->getObject('weddingUserEvent', $eventId);
    if (!$event) {
        $errorText = "Error - No Event found with an id of $eventId";
        return $modx->getChunk('userInvitesThemeError', array('errorText' => $errorText));
    }
    $eventArray = $event->toArray();
    
    
    $guestMapObject = $modx->getObject('guestEvents', $guestEventId);
    if (!$guestMapObject) {
        $errorText = "Error - No Guest Map Object found with an id of $guestEventId";
        return $modx->getChunk('userInvitesThemeError', array('errorText' => $errorText));
    }
    
    $guestId = $guestMapObject->get('guestId');
	$guest = $modx->getObject('weddingUserGuest', $guestId);
    if (!$guest) {
        $errorText = "Error - No Guest found with an id of $guestId";
        return $modx->getChunk('userInvitesThemeError', array('errorText' => $errorText));
    }
    $guestArray = $guest->toArray();
    
    /* Get the users rsvp page id */
    $c = $modx->newQuery('weddingUserPage');
    $c->where(array('editType:=' => 'rsvp'));

    $pages = $weddingUserObject->getMany('Pages', $c);
    if (!$pages) {
        $errorText = "Error - No user page data found";
        return $modx->getChunk('userInvitesThemeError', array('errorText' => $errorText));
    }
    $pages = array_values($pages);
    $pageId = $pages[0]->get('pageId');
    
    /* Get the event data */
    $eventName = $eventArray['name']; 
    $dateString = strftime('%d/%m/%y', $eventArray['date']);
    $address1 = $eventArray['location'];
    $address2 = $eventArray['address2'];
    $address3 = $eventArray['address3'];
    $address4 = $eventArray['address4'];
    $start = $eventArray['startTime'];
    $end = $eventArray['endTime'];
    $notes = $eventArray['notes'];
    
    
    /* Get the user attribute/profile data */
    $partner1= $attributes['partnerName1'];
    $partner2 = $attributes['partnerName2'];
    $partner1 .= ' & ' . $partner2;
    $website = $attributes['website'];   
    $weddingDate = $attributes['date'];
    $weddingDateString = strftime('%d/%m/%y', $weddingDate);  
    $tel = $profile['tel'];
    $fromMail = $profile['email'];
    
    /* Link to website */ 
    $domain = $modx->getOption('domain');
    $tld = $modx->getOption('tld');
    $linkTitle = $website . '.' . $domain . '.' . $tld;
    $link = 'http://' . $linkTitle;
    
    /* Get the guest data */
    $guestName = $guestArray['name'];
    $guestEmail = $guestArray['email'];
    $guid = $modx->runSnippet('getGuestsGUID', array('guestId' => $guestId));
    
    /* RSVP page link */
    $params = array('guid' => $guid, 'event' => $eventId);
    $pageLink = $modx->makeURL($pageId, $context, $params, 'full');
    $pageLink = htmlspecialchars_decode($pageLink);
    /* Convert to FE link */
    $pageLink = str_replace('www', $website,  $pageLink);
    $pageLink = str_replace("/users/$website", '',  $pageLink);
        
    /* Get the theme image */
    $assets_url = $modx->getOption('assets_url');
    $imageURL = $assets_url . 'templates/wedding/images/invites/' . $themeName . '.gif';
    
    /* Disable links */
    $disableLinks = $modx->getChunk('userInvitesDisableLinks');
    
    /* Placeholders */
    $placeholders = array(
            'disableLinks' => $disableLinks,
            'guestName' => $guestName,
            'userPartner1' => $partner1,
            'userPartner2' => $partner2,
            'eventName' => $eventName,
            'userWeddingDate' => $weddingDateString,
            'eventStart' => $start,
            'eventEnd' => $end,
            'eventDate' => $dateString,
            'eventAddress1' => $address1,
            'eventAddress2' => $address2,
            'eventAddress3' => $address3,
            'eventAddress4' => $address4,
            'eventPageLink' => $pageLink,
            'guestIdentifier' => $guid,
            'userTel' => $tel,
            'inviteImageURL' => $imageURL,
            'userEmail' => $fromMail,
            'websiteLink' => $link,
            'userInvitesNotes' => $notes);
        
   $output = $modx->getChunk($themeName, $placeholders);
   
   /* Finally, return the output */
   return $output;
   
}

 $errorText = "Error - No command specified";
 return $modx->getChunk('userInvitesThemeError', array('errorText' => $errorText));
 
 

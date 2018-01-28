<?php

/**
 * Wedding site Invites processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Check for an event add/edit submit operation */
if (isset($_REQUEST['userInvitesEventsSubmit'])) {

    /* Get the MODX user */
    $user = $modx->user;
    $userId = $user->get('id');
    if ($userId == "") {
        $modx->setPlaceholder('userInviteError', "Error - No MODX user found please log in");
        return;
    }

    $weddingUserObject = $modx->getObject('weddingUser', $userId);
    if ($weddingUserObject == null) {
        $modx->setPlaceholder('userInviteError', "Error - No Wedding user found please log in");
        return;
    }

    $userProfile = $user->getOne('Profile');
    $profile = $userProfile->toArray();
    $weddingUser->attributes = $weddingUserObject->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();
    $context = $modx->context->get('key');
    $website = $attributes['website'];
    
    /* Get the event */
    $eventId = $_REQUEST['userInvitesEventsEventSelection'];
    $event = $modx->getObject('weddingUserEvent', $eventId);
    if (!$event) {
        $modx->setPlaceholder('userInviteError', "Error - No Event found with an id of $eventId");
        return;
    }
    $eventName = $event->get('name');

    /* Get the guests that are active for the selected event and 
      ones we have to send an invite to.
     */
    $eventGuests = $event->getMany('guestEvents');
    foreach ($eventGuests as $guestPtr) {

        $guest = $guestPtr->getOne('weddingUserGuest');
        $active = $guest->get('active');
        $inviteSent = $guestPtr->get('InviteSent');
        if ($active && !$inviteSent)
            $guests[] = $guest;
    }

    /* Get the users rsvp page id */
    $c = $modx->newQuery('weddingUserPage');
    $c->where(array('editType:=' => 'rsvp'));

    $pages = $weddingUserObject->getMany('Pages', $c);
    if (!$pages) {
        $modx->setPlaceholder('userInviteError', "Error - No user page data found");
        return;
    }
    $pages = array_values($pages);
    $pageId = $pages[0]->get('pageId');

    /* Get the request data */
    $partner1String = $_REQUEST['userInvitesEventsPartner1'];
    $partner1Array = explode('&', $partner1String);
    $partner1 = trim($partner1Array[0]);
    $address1 = $_REQUEST['userInvitesEventsAddress1'];
    $address2 = $_REQUEST['userInvitesEventsAddress2'];
    $address3 = $_REQUEST['userInvitesEventsAddress3'];
    $address4 = $_REQUEST['userInvitesEventsAddress4'];
    $start = $_REQUEST['userInvitesEventsStartTime'];
    $end = $_REQUEST['userInvitesEventsEndTime'];
    $tel = $_REQUEST['userInvitesEventsPhone'];
    $dateString = $_REQUEST['userInvitesEventsDatepicker'];
    $fromMail = $_REQUEST['userInvitesEventsEmail'];
    $notes = $_REQUEST['userInvitesEventsNotes'];
    $themeName = $_REQUEST['userInvitesEventsTheme'];
    
    /* If empty pick the first theme */
    if ($themeName == "") {

        $themeCategory = $modx->getObject('modCategory', array('category' => $themeCategoryName));
        if (!$themeCategory)
            return;
        $catId = $themeCategory->get('id');
        $themes = $modx->getCollection('modChunk', array('category' => $catId));
        foreach ($themes as $theme) {
            $themeName = $theme->get('name');
            break;
        }
    }

    /* Wedding/profile data */
    $partner2 = $attributes['partnerName2'];
    $weddingDate = $attributes['date'];
    $weddingDateString = strftime('%d/%m/%y', $weddingDate);

    /* Link to website */ 
    $domain = $modx->getOption('domain');
    $tld = $modx->getOption('tld');
    $linkTitle = $website . '.' . $domain . '.' . $tld;
    $link = 'http://' . $linkTitle;
        
    /* Process each guest */
    foreach ($guests as $guest) {

        /* Guest dependant data */
        $guestArray = $guest->toArray();
        $guestName = $guestArray['name'];
        $guestEmail = $guestArray['email'];

        /* Get the guest/event map entry */
        $mapObject = $modx->getObject('guestEvents', array('guestId' => $guestArray['id'],
            'eventId' => $eventId));
        if (!$mapObject)
            continue;

        /* Get the guid */
        $guid = $modx->runSnippet('getGuestsGUID', array('guestId' => $guestArray['id']));

        /* RSVP page link */
        $params = array('guid' => $guid,
            'event' => $eventId);
        $pageLink = $modx->makeURL($pageId, $context, $params, 'full');
        $pageLink = htmlspecialchars_decode($pageLink);
        /* Convert to FE link */
        $pageLink = str_replace('www', $website,  $pageLink);
        $pageLink = str_replace("/users/$website", '',  $pageLink);
        
        /* Get the themed chunk */
        $assets_url = $modx->getOption('assets_url');
        $imageURL = $assets_url . 'templates/wedding/images/invites/' . $themeName . '.gif';
        
        /* Placeholders */
        $placeholders = array('guestName' => $guestName,
            'userPartner1' => $partner1String,
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
        
        $themedChunk = $modx->getChunk($themeName, $placeholders);

        /* Send the mail */
        $fromName = $partner1 . ' & ' . $partner2;
        $mailArray = array('to' => $guestEmail,
            'from' => $fromMail,
            'fromName' => $fromName,
            'subject' => 'Wedding Event Invite from Wedding Websites UK',
            'message' => $themedChunk,
            'html' => true);


        $modx->runSnippet('mailMan', $mailArray);

        /* Update the attendance data */
        $mapObject->set('InviteSent', 1);
        $mapObject->set('lastReminderDate', time());
        $mapObject->set('willAttend', 0);
        $mapObject->set('RSVPdOnline', 0);
        $mapObject->set('RSVPdManual', 0);
        $mapObject->set('RSVPDate',0);
        $mapObject->save();
    }
    
    /* Add an alert to inform the user the invites have been sent */
    $alert = '<script>alert("Your invites for the ' . $eventName . ' event have been sent, please check your guest list");</script>';
    $modx->regClientScript($alert,true);
}


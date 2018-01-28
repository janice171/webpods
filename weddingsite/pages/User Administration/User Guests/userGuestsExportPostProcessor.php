<?php

/**
 * Wedding site Guests export  processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/*  Export the guest list and guest data if requested
 */

/* Check for a submit */
if ($_REQUEST['userGuestsExportSubmit'] == 'Export') {

    /* Get the user details */
    $user = $modx->user;
    $userId = $user->get('id');
    if ($userId == "") {
        $modx->setPlaceholder('userGuestError', "Error - No MODX user found please log in");
        return;
    }

    $weddingUser = $modx->getObject('weddingUser', $userId);
    if (!$weddingUser) {
        $modx->setPlaceholder('userGuestError', "Error - No Wedding user found please log in");
        return;
    }

    /* Get a file and open it */
    $filename = tempnam(sys_get_temp_dir(), "csv");
    $file = fopen($filename, "w");

    /* Get the guests and events */
    $guests = $weddingUser->getMany('Guests');
    $events = $weddingUser->getMany('Events');

    /* Build the column headers */
    $eventIdArray = array();
    $columnHeader = array();
    $coulumnHeader[] = 'DBID';
    $coulumnHeader[] = 'NAME';
    foreach ($events as $event) {

        if ($event->get('active') == 1) {

            $name = $event->get('name');
            $coulumnHeader[] = strtoupper($name);
            $eventId = $event->get('id');
            $eventIdArray[] = $eventId;
        }
    }

    $coulumnHeader[] = 'GUEST OF';
    $coulumnHeader[] = 'GUID';
    $coulumnHeader[] = 'ACTIVE';

    /* Write them out */
    fputcsv($file, $coulumnHeader);

    /* Construct the row data */
    foreach ($guests as $guest) {

        $guestArray = $guest->toArray();
        
        $rowData = array();
        $rowData[] = $guestArray['id'];
        $rowData[] = $guestArray['name'];
        

        /* Get the event array into a string we can use in the table body for this guest */
        $attendOutput = "";
        foreach ($eventIdArray as $id) {

            /* See if we are invited and what attendance there has been */
            $guestEvent = $modx->getObject('guestEvents', array(
                'guestId' => $guestArray['id'],
                'eventId' => $id));

            if (!$guestEvent) {

                $attend = "Not Invited";
            } else {

                /* Invited , check status */
                $attend = "Invited";
                $attendance = $guestEvent->get('willAttend');
                if ($attendance == 1) {

                    $attend = "Yes";
                } else {

                    $rsvpOnline = $guestEvent->get('RSVPdOnline');
                    $rsvpManual = $guestEvent->get('RSVPdManual');
                    if ($rsvpOnline || $rsvpManual) {

                        $attend = "No";
                    }
                }
            }
            $rowData[] = $attend;
        }
        
        $rowData[] = $guestArray['guestOf'];
        $rowData[] = $modx->runSnippet('getGuestsGUID', array('guestId' => $guestArray['id'] ));
        $rowData[] = $guestArray['active'] ? "Yes" : "No";
        
        /* Write the row out*/
        fputcsv($file, $rowData);
    }
    
    /* Output guest details if requested */
    if ($_REQUEST['userGuestsExportGuestDetails'] == '1') {
        
        fputs($file, PHP_EOL);
        fputs($file, PHP_EOL);
        
        /* Column headers */
        $guestDetailsHeader = array();
        $guestDetailsHeader[] = 'ID';
        $guestDetailsHeader[] = 'NAME';
        $guestDetailsHeader[] = 'EMAIL';
        $guestDetailsHeader[] = 'ADDRESS 1';
        $guestDetailsHeader[] = 'ADDRESS 2';
        $guestDetailsHeader[] = 'CITY';
        $guestDetailsHeader[] = 'POSTCODE';
        $guestDetailsHeader[] = 'TELEPHONE';
        $guestDetailsHeader[] = 'GUEST OF';
        $guestDetailsHeader[] = 'PARTY';
        $guestDetailsHeader[] = 'ACTIVE';
        
        /* Write them out */
        fputcsv($file, $guestDetailsHeader);
        
        /* Construct the row data */
        foreach ($guests as $guest) {

            $guestDetailsArray = $guest->toArray();
        
            $rowDetailsData = array();
            $rowDetailsData[] = $guestDetailsArray['id'];
            $rowDetailsData[] = $guestDetailsArray['name'];
            $rowDetailsData[] = $guestDetailsArray['email'];
            $rowDetailsData[] = $guestDetailsArray['address1'];
            $rowDetailsData[] = $guestDetailsArray['address2'];
            $rowDetailsData[] = $guestDetailsArray['city'];
            $rowDetailsData[] = $guestDetailsArray['postCode'];
            $rowDetailsData[] = $guestDetailsArray['telephone'];
            $rowDetailsData[] = $guestDetailsArray['guestOf'];
            $rowDetailsData[] = $guestDetailsArray['party'];
            $rowDetailsData[] = $guestDetailsArray['active'] ? "Yes" : "No";
            
            /* Write the row out*/
            fputcsv($file, $rowDetailsData);
            
        }
    }

    /* Close the file */
    fclose($file);

    /* Set the content of the output page */
    $output = file_get_contents($filename);
    unlink($filename);

    $resource = $modx->getObject('modResource', array('id' => $userGuestCSVPage));
    if ($resource) {

        $resource->set('content', $output);
        $resource->save();
    }

    /* Redirect to the CSV generation page */
    $context = $modx->context->get('key');
    $userGuestCSVPage = $modx->getOption('userGuestCSVPage');
    $pageURL = $modx->makeURL($userGuestCSVPage, $context, "", "full");
    header("Location: {$pageURL}");
}

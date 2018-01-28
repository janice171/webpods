<?php

/**
 * Wedding site getGuestsGUID
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Gets a guests unique guid
 * 
 * Parameters :- 
 * 
 * $guestId - The id of the guest
 *  
 */
$guest = $modx->getObject('weddingUserGuest', $guestId);
if (!$guest)
    return "No guest";
$guestArray = $guest->toArray();
$guestName = $guestArray['name'];
$guestEmail = $guestArray['email'];
$checksum = 0;
$length = strlen($guestEmail);
for ($count = 0; $count <= $length; $count++) {

    $checksum += ord($guestEmail[$count]);
}

$checksum %= 256;
$guestNameArray = explode(' ', $guestName);
$guidArray = array();
$guidArray[] = $guestNameArray[0];
$guidArray[] = $guestArray['id'];
$guidArray[] = $checksum;
$guid = implode('-', $guidArray);

return $guid;
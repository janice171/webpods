<?php

/**
 * Wedding site User Edit Gallery processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/*  Initialise the users home page */

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ($userId == "") {
    $modx->setPlaceholder('userEditGalleryError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ($weddingUser == null) {
    $modx->setPlaceholder('userEditGalleryError', "Error - No user found please log in");
    return;
}

$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Website links */
$modx->runSnippet('userAdminWebPageLinks', array('attributes' => $attributes));

/* Support */
$modx->runSnippet('userAdminSupportLinks', array('attributes' => $attributes));

/* Initialise the common form part */
$modx->runSnippet('userEditCommonInitialise');

/* Album List */
$weddingUser->albums = $weddingUser->getMany('GalleryAlbum');

$albumCount = 0;
$output = "";
$context = $modx->context->get('key');

/* Sort by position */
foreach ($weddingUser->albums as $album) {

    $albumPosition = $album->get('albumPosition');
    $albumPositionArray[$albumPosition] = $album;
}

ksort($albumPositionArray);

foreach ($albumPositionArray as $album) {

    $albumName = $album->get('albumName');
    $albumDescription = $album->get('albumDescription');
    $id = $album->get('id');
    $active = $album->get('active');
    $status = "on";
    if ($active == 0)
        $status = "off";
    $AJAXArgs = array('id' => $id);
    $albumPosition = $album->get('albumPosition');
    $albumPosition = $id . "-" . $albumPosition;
    $galleryAJAXProcessorId = $modx->getOption('galleryAJAXProcessorId');
    $ajaxLink = $modx->makeURL($galleryAJAXProcessorId, $context, $AJAXArgs, "full");
    $output .= $modx->getChunk('userEditGalleryAlbumList', array('albumName' => $albumName,
        'albumDescription' => $albumDescription,
        'galleryAlbumAJAX' => $ajaxLink,
        'galleryActiveStatus' => $status,
        'albumId' => $id,
        'galleryAlbumPosition' => $albumPosition));
    $albumCount++;
}

/* Set the output placeholder */
$modx->setPlaceholder('galleryAlbumList', $output);


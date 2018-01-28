<?php

/**
 * Wedding site User Edit Gallery processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) {
    $modx->setPlaceholder('userEditGalleryError', "Error - No user found please log in");
    return;
}
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) {
    $modx->setPlaceholder('userEditGalleryError', "Error - No user found please log in");
    return;
}

$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$weddingUser->albums = $weddingUser->getMany('GalleryAlbum');

/* Check common form processing */
$modx->runSnippet('userEditCommonPostProcessor');

/* Album Create */
if ( isset($_REQUEST['galleryAlbumCreateSubmit'])) {
    
    $albumObject = $modx->newObject('weddingUserGalleryAlbum');
    if ( !$albumObject ) return;
    $albumObject->set('albumName', strip_tags($_REQUEST['galleryAlbumName']));
    $albumObject->set('albumDescription', strip_tags($_REQUEST['galleryAlbumDescription']));
    $albumObject->set('active', $_REQUEST['galleryAlbumActive']);
    $nextPosition = count($weddingUser->albums) + 1;
    $albumObject->set('albumPosition', $nextPosition);
    $weddingUser->addMany($albumObject, 'GalleryAlbum');
    if ( $weddingUser->save() == false ) {
            $modx->setPlaceholder('userEditGalleryError', "Error - Failed to create this album"); 
            return;
        }
    
}
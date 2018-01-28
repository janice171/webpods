<?php
/**
 * Wedding site User FE Gallery Page  processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the album id if we have one */
isset($_REQUEST['albumId'] ) ? $albumIdPage =  $_REQUEST['albumId']  : $albumIdPage = 0;

/* Get the website and the user details */
$assetsURL = $modx->getOption('assets_url');
if ( !isset($_REQUEST['website'])) $website = $modx->runSnippet('userGetWebsiteFromUrl');
    
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:='  => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if ( !$weddingUserAttributes  ) return;
$weddingUserId = $weddingUserAttributes->get('user');
$weddingUser = $modx->getObject('weddingUser', $weddingUserId); 
if ( !$weddingUser ) return;
$galleryURL =  $modx->runSnippet('userGetDirectoryURL', array('directory' => "gallery"));

/* Construct the page */

/* Album Selection */
$albums = $weddingUser->getMany('GalleryAlbum');

/* Sort by position */
foreach ($albums as $album) {

    $albumPosition = $album->get('albumPosition');
    $albumPositionArray[$albumPosition] = $album;
}

ksort($albumPositionArray);

$output = "";
foreach ( $albumPositionArray as $album ) {
    
    /* Check for active, no items is not active even if marked as such */
    $active = $album->get('active');
    $albumId = $album->get('id');
    $albumItems = $modx->getCollection('albumItems', array('albumId' => $albumId));
    if ( count( $albumItems) == 0) $active = 0;
    if ( $active == 1 ) {
        
        $albumName = $album->get('albumName');
        $pageId = $modx->resource->get('id');
        $params = array('albumId' => $albumId);
        $albumLink = $modx->runSnippet('userCreateFELink', array('id' => $pageId,
                                                           'website' => $website,
                                                           'params' => $params));
        
        /* If we have a nominated album picture display it */
        $itemURL = $assetsURL . "templates/wedding/images/icon-gallerySmall.gif";
        $itemTitle = 'Album Icon';
        foreach ( $albumItems as $item ) {
            
            $galleryItemId = $item->get('itemId');
            $galleryItem = $modx->getObject('weddingUserGalleryItem', $galleryItemId);
            $useForAlbum = $galleryItem->get('useForAlbum');
            
            if ( $useForAlbum == 1 ) {
                
                $itemURL =  $galleryURL . DIRECTORY_SEPARATOR;
                $itemURL .= $galleryItem->get('itemFileName');
                $itemTitle = $galleryItem->get('itemDisplayName');
                $itemDescription = $galleryItem->get('itemDescription');
                
            }
        }
    
        $placeholderArray = array ( 'userFEgalleryAlbumLink' => $albumLink,
                                    'albumName' => $albumName,
                                    'userFEGalleryImageTitle' =>$itemTitle,
                                    'userFEGalleryImage' => $itemURL,
                                    'userFEGalleryImageDescription' => $title);
        $output .= $modx->getChunk('userFEGalleryAlbumItem', $placeholderArray);
    }
    
}

/* Check for no active albums */
$noActiveAlbums = false;
if ( $output == "" ) $noActiveAlbums = true;

/* Set the placeholder */
$modx->setPlaceholder('userFEAlbumItems',  $output);

/* Item Selection */

/* Check for no album id */
if ( $albumIdPage == 0 ) {
    
    if ( $noActiveAlbums ) {
        
        $output = $modx->getChunk('userFEGalleryDescriptionNoAlbums');
        
    } else {
        
         $output = $modx->getChunk('userFEGalleryDescriptionNoItems');
    }

    
    $modx->setPlaceholder('userFEGalleryAlbumDescription', $output);
    return;
    
}

/* Get the gallery items for this album and return them */
$albumItems = $modx->getCollection('albumItems', array('albumId' => $albumIdPage));
$itemPositionArray = array();

/* Sort by position */
foreach ( $albumItems as $albumItem ) {

    $itemId = $albumItem->get('itemId');
    $galleryItem = $modx->getObject('weddingUserGalleryItem', $itemId);
    if ( !$galleryItem ) continue;
    $galleryItemPosition = $galleryItem->get('itemPosition');
    $itemPositionArray[$galleryItemPosition] =  $galleryItem;

}   
ksort($itemPositionArray);

$output = "";
foreach ($itemPositionArray as $position => $galleryItem ) {

    $itemURL =  $galleryURL . DIRECTORY_SEPARATOR;
    $itemURL .= $galleryItem->get('itemFileName');
    $itemTitle = $galleryItem->get('itemDisplayName');
    $itemDescription = $galleryItem->get('itemDescription');
    $title = $modx->getChunk('userFEGalleryItemTitle', 
            array('userFEGalleryImageTitle' => $itemTitle,
                  'userFEGalleryImageDescription' => $itemDescription));
    $parameters = array('userFEGalleryImageTitle' =>$itemTitle,
                                    'userFEGalleryImage' => $itemURL,
                                    'userFEGalleryImageDescription' => $title);
    $output  .= $modx->getChunk('userFeGalleryItem', $parameters);

}

/* Items and the album description */
$modx->setPlaceholder('userFEGalleryItems', $output);
$output = $modx->getChunk('userFeGalleryElastislideFormat');
$modx->setPlaceholder('userFEGalleryElastislideFormat', $output);
$albumObject = $modx->getObject('weddingUserGalleryAlbum', array('id' => $albumIdPage));
if ( !$albumObject ) return;
$albumDescription = $albumObject->get('albumDescription');
$albumDescriptionOutput = $modx->getChunk('userFEGalleryAlbumDescription', 
                                                                           array('userFEGalleryDescription' => $albumDescription));
$modx->setPlaceholder('userFEGalleryAlbumDescription', $albumDescriptionOutput);



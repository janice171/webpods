<?php

/**
 * Wedding site User Edit Gallery AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Assemble the parameters from the request data */
$id = $_REQUEST['id'];
$command = $_REQUEST['command'];
$output = "";

/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user) {
    $userId = $user->get('id');
    if ($userId == "")  return "Error! No logged in user";
}

$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null )  return "Error! No logged in Wedding user";
    
$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();

/* Get the album from the request */
$album = $modx->getObject('weddingUserGalleryAlbum', $id);
if (!$album)  return "Error - can't find album";
$albumId = $id;

/* Switch on command type */
switch ($command) {

    case 'status' :

        /* Toggle the current state of active for the album */
        $active = $album->get('active');
        $newActive = 0;
        $output = 'off';
        if ($active == 0) {

            $newActive = 1;
            $output = "on";
        }
        $album->set('active', $newActive);
        $album->save();
        break;

    case 'albumDetails' :

        /* Get the album details */
        $name = $album->get('albumName');
        $description = $album->get('albumDescription');
        $active = $album->get('active');
        $status = $active ? 1 : 0;
        $response = array('name' => $name, 'description' => $description, 'status' => $status);
        $output = json_encode($response);
        break;

    case 'albumDetailsUpdate' :

        /* Update the album details */
        $album->set('albumName', strip_tags($_REQUEST['name']));
        $album->set('albumDescription', strip_tags($_REQUEST['description']));
        $album->save();
        break;

    case 'filesAdded' :

        /* Nothing to do here currently */
        break;

    case 'uploadComplete':

       /* Get album/user details */
       $uploadDirectory = $modx->runSnippet('userGetDirectory', array('directory' => "uploads"));
       $galleryDirectory = $modx->runSnippet('userGetDirectory', array('directory' => "gallery")); 
       if ( ($uploadDirectory == 'error') || ($galleryDirectory == 'error') ) return;   
       $galleryURL = $modx->runSnippet('userGetDirectoryURL', array('directory' => "gallery")); 
       if ( ($galleryURL == 'error') ) return;   
       
       /* Look for any zip files and expand them */
        if (is_dir($uploadDirectory) && ($dir = opendir($uploadDirectory))) {
            while (($file = readdir($dir)) !== false) {
               
                if ( ($file == '..') || ($file == '.') ) continue;  
                $fileInfo = pathinfo($file);
                if ( $fileInfo['extension'] == 'zip' ) {
                    
                    /* Unpack it */
                    $zip = new ZipArchive;
                    $res = $zip->open($uploadDirectory .  DIRECTORY_SEPARATOR . $file);
                    if ($res === TRUE) {
                        $zip->extractTo($uploadDirectory);
                        $zip->close();  
                   } else {
                     @unlink($uploadDirectory . DIRECTORY_SEPARATOR . $file);
                   }
                }
                closedir($dir);
            }        
        }
        
       /* Delete any files with an invalid extension */
       $validExtensionsArray = explode(',', $validExtensions);
        if (is_dir($uploadDirectory) && ($dir = opendir($uploadDirectory))) {
            while (($file = readdir($dir)) !== false) {
               
                if ( ($file == '..') || ($file == '.') ) continue;  
                $fileInfo = pathinfo($uploadDirectory . DIRECTORY_SEPARATOR . $file);
                if ( !in_array($fileInfo['extension'], $validExtensionsArray) ) {
                     @unlink($uploadDirectory . DIRECTORY_SEPARATOR . $file);
                }
                
            }
        }
        
       /* Get the count of any existing album items for position calculation */
       $albumItems = $modx->getCollection('albumItems', array('albumId' => $albumId));
       $nextPosition = count($albumItems) + 1;
       
        /* Process the uploaded files */
       if (is_dir($uploadDirectory) && ($dir = opendir($uploadDirectory))) {
            while (($file = readdir($dir)) !== false) {
               
                if ( ($file == '..') || ($file == '.') ) continue;  
                
                /* Create an album item and move the file to the gallery directory */
                $newAlbumItem = $modx->newObject('weddingUserGalleryItem');
                if (! $newAlbumItem ) continue;
                $newAlbumItem->set('itemFileName', $file);
                $newAlbumItem->set('itemPosition', $nextPosition);
                $nextPosition++;
                $newAlbumItem->set('active', true);
                
                 /* Add the newly created items to the user */
                $weddingUser->addMany( $newAlbumItem, 'GalleryItem');
                $weddingUser->save();
                
                 /* Link the items to the album */
                $albumItemId = $modx->lastInsertId();
                $albumItemsObject = $modx->newObject('albumItems');
                if ( !$albumItemsObject) continue;
                $albumItemsObject->set('albumId', $albumId);
                $albumItemsObject->set('itemId', $albumItemId);
                if ( !$albumItemsObject->save() ) continue;
                
                /* Move the file from the upload folder to the gallery folder */
                $filePath = $uploadDirectory . DIRECTORY_SEPARATOR . $file;
                $galleryPath = $galleryDirectory . DIRECTORY_SEPARATOR . $file;
                if ( !@rename($filePath, $galleryPath ) ) continue;
                
            }
            closedir($dir);
           
          
        }
        break;
        
     case 'albumGetItems':
         
         /* Get the items for this album */
         $galleryURL =  $modx->runSnippet('userGetDirectoryURL', array('directory' => "gallery")); 
         if ( $galleryURL == 'error'  ) return; 
         $albumItems = $modx->getCollection('albumItems', array('albumId' => $albumId));
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
         
         /* Output the items */
         $itemOutput = array();
         foreach ($itemPositionArray as $position => $galleryItem ) {
             
             $itemId = $galleryItem->get('id');
             $itemURL =  $galleryURL . DIRECTORY_SEPARATOR;
             $itemURL .= $galleryItem->get('itemFileName');
             $itemTitle = $galleryItem->get('itemDisplayName');
             $itemPosition = $galleryItem->get('itemPosition');
             $itemPosition = $itemId . "-" . $itemPosition;
             $args= array('itemId' => $itemId,
                                   'id' => $albumId,
                                   'command' => 'itemDetails');
             $context = $modx->context->get('key');
             $galleryAJAXProcessorId = $modx->getOption('galleryAJAXProcessorId');
             $itemEditLink = $modx->makeURL($galleryAJAXProcessorId, $context, $args, "full");
             $parameters = array('galleryItemTitle' =>$itemTitle,
                                               'galleryItemSrc' => $itemURL,
                                               'galleryItemLink' => $itemEditLink,
                                              'galleryItemPosition' => $itemPosition);
             $itemOutput [] = $modx->getChunk('userEditGalleryItem', $parameters);
             
         }
         
         $response = array('items' => $itemOutput);
         $output = json_encode($response);
         break;
         
    case 'itemDetails' :

        /* Get the item details */
        $itemId = $_REQUEST['itemId'];
        $galleryItemObject = $modx->getObject('weddingUserGalleryItem', $itemId);
        $error = "none";
        if ( !$galleryItemObject ) $error = "No Item Found";
        $galleryItem = $galleryItemObject->toArray();
        $active = $galleryItem['active'];
        $status = $active ? 1 : 0;
        $useForAlbum = $galleryItem['useForAlbum'];
        $galleryURL = $modx->runSnippet('userGetDirectoryURL', array('directory' => "gallery")); 
         if ( ($galleryURL == 'error') ) return; 
        $fileURL = $galleryURL . DIRECTORY_SEPARATOR . $galleryItem['itemFileName'];
        
        /* Response */
        $response = array('filename' => $galleryItem['itemFileName'],
                          'displayname' => $galleryItem['itemDisplayName'],
                          'description' => $galleryItem['itemDescription'],
                          'position' => $galleryItem['itemPosition'],
                          'url' => $fileURL,
                          'status' => $status,
                          'error' => $error,
                          'id' => $itemId,
                          'useForAlbum' => $useForAlbum);
        $output = json_encode($response);
        break;
    
     case 'albumUpdateItem' :
         
         /* Update the gallery item */
         $itemId = $_REQUEST['userEditGalleryItemId'];
         $galleryItem = $modx->getObject('weddingUserGalleryItem', $itemId);
         if (!$galleryItem ) return;
         $galleryItem->set('itemDisplayName', strip_tags($_REQUEST['userEditGalleryItemDisplayName']));
         $galleryItem->set('itemDescription', strip_tags($_REQUEST['userEditGalleryItemDescription']));
         
         /* Update the use for album state */
         if ( $_REQUEST['userEditGalleryItemAlbumImage'] == 1 ) {
                
                /* Get the last one used if there is one */
                $c = $modx->newQuery('weddingUserGalleryItem');
                $c->where(array('user:=' => $userId));
                $c->andCondition(array('useForAlbum:=' => 1));
                $items = $modx->getCollection('weddingUserGalleryItem', $c);
                
                foreach ($items as $item ) {
                    
                    $itemId = $item->get('id');
                    $albumMap = $modx->getObject('albumItems', array('itemId' => $itemId));
                    if ( !$albumMap ) continue;
                    $albumMapId = $albumMap->get('albumId');
                    if ( $albumMapId == $albumId) {
                    
                        $item->set('useForAlbum', 0);
                        $item->save();
                    }
                    
                }
                
                $galleryItem->set('useForAlbum',1);
            
         }
         /* See if it has been unchecked */
         if ( $_REQUEST['uncheckAlbumImage'] == 1) $galleryItem->set('useForAlbum',0);
         
         $_REQUEST['userEditGalleryItemActive'] == 1 ? $active = 1 : $active = 0;
         $galleryItem->set('active', $active);
         $galleryItem->save();
         
         break;
     
     case 'albumItemDelete' :
         
         $itemId = $_REQUEST['itemId'];
         $galleryItemObject = $modx->getObject('weddingUserGalleryItem', $itemId);
          if ( !$galleryItemObject ) return;
          
         /* Delete the file */
         $galleryDirectory = $modx->runSnippet('userGetDirectory', array('directory' => "gallery")); 
         if ( $galleryDirectory == 'error')  return;
         $itemFilename = $galleryItemObject->get('itemFileName');
         @unlink($galleryDirectory . DIRECTORY_SEPARATOR .  $itemFilename);
         
         /* Delete the item */
        
         $galleryItemObject->remove();
         
         /* Update the positions of the pictures */
         $albumItems = $modx->getCollection('albumItems', array('albumId' => $albumId));
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
         
         /* Update the positions */
         $newPosition = 1;
         foreach ($itemPositionArray as $position => $galleryItem ) {
             
             $galleryItem->set('itemPosition', $newPosition);
             $galleryItem->save();
             $newPosition++;
         }
         
         break;
         
    case 'galleryItemReposition':
   
        /* Get the items into an array */
        $itemArray = json_decode($_REQUEST['positions'] , true);
        $length = count($itemArray);
        for ( $i = 0; $i<=$length; $i++) {
            
            $itemObject = $modx->getObject('weddingUserGalleryItem', $itemArray[$i]);
            if ( !$itemObject ) continue;
            $itemObject->set('itemPosition', $i+1);
            $itemObject->save();
                 
        }
        
        break;
        
    case 'albumReposition':
   
        /* Get the albums into an array */
        $albumArray = json_decode($_REQUEST['positions'] , true);
        $length = count($albumArray);
        for ( $i = 0; $i<=$length; $i++) {
            
            $albumObject = $modx->getObject('weddingUserGalleryAlbum', $albumArray[$i]);
            if ( !$albumObject ) continue;
            $albumObject->set('albumPosition', $i+1);
            $albumObject->save();
                 
        }
        
        break;
        
    default:

        $output = "Error - no valid command";
        break;
}

/* Return the output */
echo $output;
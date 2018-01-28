<?php
/**
 * Wedding site User Edit Gallery processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');
$pluploadPath = $wsJQueryPath . 'js/plupload/js/';

/* Common Scripts */
$websiteLinks = $modx->runSnippet('userAdminWebPageLinksCommonJS');
$modx->regClientStartupScript($websiteLinks, true);

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) return;
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) return;

/* Get the album count and other static variables*/
$albums = $weddingUser->getMany('GalleryAlbum');
$albumCount = count($albums);
$plupLoadFlash = $pluploadPath . "plupload.flash.swf";
$plupLoadFlash = '"' . $plupLoadFlash . '"';
$plupLoadSilverlight = $pluploadPath . "plupload.silverlight.xap";
$plupLoadSilverlight = '"' . $plupLoadSilverlight . '"';
$context = $modx->context->get('key');
$galleryUploadProcessorId = $modx->getOption('galleryUploadProcessorId');
$uploadLink = $modx->makeURL($galleryUploadProcessorId, $context, "", "full");
$plupLoadURL =  '"' . $uploadLink . '"';
$galleryAJAXProcessorId = $modx->getOption('galleryAJAXProcessorId');
$galleryAJAXProcessorLink = $modx->makeURL($galleryAJAXProcessorId, $context, "", "full");
$galleryAJAXProcessorLink = '"' . $galleryAJAXProcessorLink . '"';
$ready = '<script>
    
        WS.albumCount = ' . $albumCount . ';
        WS.pluploadFlash = ' .  $plupLoadFlash . ';
        WS.pluploadSilverlight = ' . $plupLoadSilverlight . ';
        WS.pluploadURL = ' . $plupLoadURL . ';   
        WS.galleryAJAXProcessorLink = ' . $galleryAJAXProcessorLink . ';   
        WS.sortable;  
        WS.albumSortable;    
            
</script>';
$modx->regClientStartupScript($ready, true);

/* WS javascript gallery */
$gallery = $javascriptPath . "js/pages/user/edit/gallery/gallery.js";
$modx->regClientStartupScript($gallery);


/* Plupload */
$pluploadCSS = $assets_url . "templates/wedding/styles/jquery.ui.plupload.css";
$modx->regClientCSS($pluploadCSS);
$modx->regClientStartupScript("http://bp.yahooapis.com/2.4.21/browserplus-min.js");
$full = $pluploadPath . 'plupload.full.js';
$modx->regClientStartupScript($full);
$ui = $pluploadPath . 'jquery.ui.plupload/jquery.ui.plupload.js';
$modx->regClientScript($ui);

/* WS javascript plupload */
$plupload = $javascriptPath . "js/pages/user/edit/gallery/plupload.js";
$modx->regClientStartupScript($plupload);




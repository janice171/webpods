<?php

/**
 * Wedding site User Front End Gallery processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */
/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url', null, $modx->getOption('assets_url') . 'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url', null, $modx->getOption('assets_url') . 'components/ws_javascript/');
$cssPath = $assets_url . "templates/wedding/styles/";

/* Dont include the gallery if we have no albums or empty albums */
$website = $modx->runSnippet('userGetWebsiteFromUrl');
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:='  => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if ( !$weddingUserAttributes  ) return;
$weddingUserId = $weddingUserAttributes->get('user');
$weddingUser = $modx->getObject('weddingUser', $weddingUserId); 
if ( !$weddingUser ) return;
$albums = $weddingUser->getMany('GalleryAlbum');
$activeAlbums = false;
foreach ( $albums as $album ) {
    
    /* Check for active, no items is not active even if marked as such */
    $active = $album->get('active');
    $albumId = $album->get('id');
    $albumItems = $modx->getCollection('albumItems', array('albumId' => $albumId));
    if ( count( $albumItems) == 0) $active = 0;
    if ( $active == 1 ) $activeAlbums = true;
}

if ( !$activeAlbums ) return;

/* If we have no album selected return also */
isset($_REQUEST['albumId'] ) ? $albumIdPage =  $_REQUEST['albumId']  : $albumIdPage = 0;
if ( $albumIdPage == 0 ) return;

/* Styles */
$css1 = $cssPath . "galleryStyle.css";
$modx->regClientCSS($css1);
$css2 = $cssPath . "galleryElastislide.css";
$modx->regClientCSS($css2);
$css3 = '<noscript>
	<style>
	    .es-carousel ul{
		display:block;
		}
	</style>
</noscript>';
$modx->regClientCSS($css3);

/* Head scripts */
$head1 = '<script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">	
                    <div class="rg-image-wrapper">
                            {{if itemsCount > 1}}
                                    <div class="rg-image-nav">
                                            <a href="#" class="rg-image-nav-prev">Previous Image</a>
                                            <a href="#" class="rg-image-nav-next">Next Image</a>
                                    </div>
                            {{/if}}
                            <div class="rg-image"></div>
                            <div class="rg-loading"></div>
                            <div class="rg-caption-wrapper">
                                    <div class="rg-caption" style="display:none;">
                                            <p></p>
                                    </div>
                            </div>
                    </div>
</script>';
$modx->regClientStartupScript($head1, true);

/* Body scripts */
$ui1 = $wsJQueryPath . 'js/galleryFE/jquery.tmpl.min.js"';
$modx->regClientScript($ui1);
$ui2 = $wsJQueryPath . 'js/galleryFE/jquery.easing.1.3.js"';
$modx->regClientScript($ui2);
$ui3 = $wsJQueryPath . 'js/galleryFE/jquery.elastislide.js"';
$modx->regClientScript($ui3);
$ui4 = $javascriptPath . "js/pages/user/frontend/gallery/gallery.js";
$modx->regClientScript($ui4);
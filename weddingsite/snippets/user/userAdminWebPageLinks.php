<?php
/**
 * Wedding site userAdminWebPageLinks
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Draws the user admin area sidebar web page links
 * 
 *  
 *  Parameters :-
 * 
 * attributes - an array of the users attributes
 * 
 * Returns - nothing, sets placeholders only
 */

$website = $attributes['website'];
$c = $modx->newQuery('modResource');
$c->where(array('alias:='  => $website));
$userWebpageContainerId = $modx->getOption('userWebpageContainerId');
$c->andCondition(array('parent:='  => $userWebpageContainerId));
$userContainer = $modx->getObject('modResource', $c);
/* If no container we can't draw any links! Maybe this is
 * a first login case.
 */
if ( !$userContainer ) return;
 $websiteParentId = $userContainer->get('id');
 
/* Get the children, sorted by menuindex */
$c = $modx->newQuery('modResource');
$c->where((array('parent:='  => $websiteParentId)));
$c->sortBy('menuindex', 'ASC');
$children = $modx->getCollection('modResource',$c);

$context = $modx->context->get('key');
$weblinkOutput = "";
foreach ( $children as $child ) {
    
    $id = $child->get('id');
    $title = $child->get('pagetitle');
    $published = $child->get('published');
    $published = $published == 0 ? "off" : "on";
    
    /* Get the documents edit type and generate the edit links */
    $editType = $child->get('longtitle');
    switch ( $editType ) {
        
        case "blog":
            
            $userEditPageBlogId = $modx->getOption('userEditPageBlogId');
            $linkId = $userEditPageBlogId;
            break;
        
        case "rsvp":
            
            $userEditPageRSVPId = $modx->getOption('userEditPageRSVPId');
            $linkId = $userEditPageRSVPId;
            break;
        
        case "gallery":
            
            $userEditPageGalleryId = $modx->getOption('userEditPageGalleryId');
            $linkId = $userEditPageGalleryId;
            break;
        
        case "guestbook":
            
            $userEditPageGuestbookId = $modx->getOption('userEditPageGuestbookId');
            $linkId = $userEditPageGuestbookId;
            break;
        
        default:
            
            $userEditPageGenericId = $modx->getOption('userEditPageGenericId'); 
            $linkId = $userEditPageGenericId;
            
    }
    $linkArgs = array('userPage' => $id );
    $pageLink = $modx->makeURL($linkId, $context, $linkArgs, "full");
    $AJAXArgs = array('id' => $id);
    $userHomeAJAXPage = $modx->getOption('userHomeAJAXPage');
    $ajaxLink = $modx->makeURL($userHomeAJAXPage, $context, $AJAXArgs, "full");
    $linkParams = array('userHomeWeblinkLink' =>  $pageLink ,
                                     'userHomeWeblinkTitle' => $title,
                                     'userHomeWeblinkPublished' => $published,
                                     'userHomeWeblinkAJAX' => $ajaxLink);
    
    $webLink .= $modx->getChunk('userHomeWebPagesLinks', $linkParams);
}

$modx->setPlaceholder('userWebPageslinks',  $webLink);



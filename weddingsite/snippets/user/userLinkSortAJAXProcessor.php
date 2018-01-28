<?php
/**
 * Wedding site User common link sort AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Assemble the parameters from the request data */
$listOrder= $_REQUEST['order'];
$listOrderArray = json_decode($listOrder, true);

/* Get the user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) return;
$weddingUserObject = $modx->getObject('weddingUser', $userId);
if ( $weddingUserObject == null ) return;

$weddingUser = $weddingUserObject->getOne('Attributes');
$attributes = $weddingUser->toArray();

/* Get the users page set container  */
$website = $attributes['website'];
$c = $modx->newQuery('modResource');
$c->where(array('alias:='  => $website));
$userPagesParentContainerId = $modx->getOption('userPagesParentContainerId');
$c->andCondition(array('parent:='  => $userPagesParentContainerId));
$userContainer = $modx->getObject('modResource', $c);
if ( !$userContainer ) return;
$children = $userContainer->getMany('Children');

/* Update the page set with the new order */
$position = 1;
foreach ( $listOrderArray as $entry ) {
    
    foreach ( $children as $child ) {
        
        $title = $child->get('pagetitle');
        if ( $title == $entry ) {
            
            $menuPosition = $child->get('menuindex');
            if ( $menuPosition != $position ) {
                
                $child->set('menuindex', $position);
                $child->save();
            }
        }
    }
    
    $position++;
    
}

<?php
/**
 * Wedding site creatUserPageSetRegistration
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Creates the user page set under the users area from the default set.
 * 
 * Parameters :- 
 * 
 * $name - The name of the users desired subdomain e.g. janiceswedding
 *  
 */

/* Sanity check the name */
if ( $name == "" ) $name = "nouser";

$weddingUserAttribute = $modx->getObject('weddingUserAttribute', array('website' => $name));
if ( !$weddingUserAttribute ) return;

$userId = $weddingUserAttribute->get('user');
$weddingUserObject = $modx->getObject('weddingUser', $userId);
if ( $weddingUserObject == null ) return;

    
/* Duplicate the users page set from the default set */
$defaultUserPageSetContainerId = $modx->getOption('defaultUserPageSetContainerId');
$defaultUserPage = $modx->getObject('modResource', $defaultUserPageSetContainerId);
$newResource = $defaultUserPage->duplicate(array(
    'newName' => $name,
    'duplicateChildren' => true,
    'prefixDuplicate' => false,
));

/* Reparent the new resource to the user page area */
$userPagesParentContainerId = $modx->getOption('userPagesParentContainerId');
$newResource->set('parent', $userPagesParentContainerId);

/* Publish this resource and its children */
$newResource->set('published', true);
$newResource->save();

$newResourceId = $newResource->get('id');
$children = $modx->getcollection('modResource', array('parent' => $newResourceId));

foreach ( $children as $child ) {
    
    $child->set('published', true);
    $child->save();
    
    /* Add details to the user page table */
    $pageObject = $modx->newObject('weddingUserPage');
    $childId = $child->get('id');
    $editType = $child->get('longtitle');
    $pageObject->set('pageId', $childId);
    $pageObject->set('editType', $editType);
    $weddingUserObject->addMany($pageObject, 'Pages');
    $weddingUserObject->save();
}

/* We need to clear the cache to regen the alias map */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache(array (
            "{$resource->context_key}/",
        ),
        array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
        )
    );

/* Done */

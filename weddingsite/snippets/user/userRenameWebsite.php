<?php

/**
 * Wedding site userRenameWebsite
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Renames s a users website
 * 
 * Parameters :-
 *
 * website - the new website name
 * existingWebsite - the existing website name
 * 
 * 
 * Returns :-
 * 
 * None
 *
 * 
 */
/* Sanity check the parameters */
if (($website == "") || ($existingWebsite == ""))
    return;

/* Change the sub domain name */
$modx->runSnippet('cPanelChangeSubDomain', array('from' => $existingWebsite,
    'to' => $website));
/* Rename the user directory */
$modx->runSnippet('renameUserDirectory', array('from' => $existingWebsite,
    'to' => $website));

/* Adjust the existing user alias */
$c = $modx->newQuery('modResource');
$c->where(array('alias:=' => $existingWebsite));
$userPagesParentContainerId = $modx->getOption('userPagesParentContainerId');
$c->andCondition(array('parent:=' => $userPagesParentContainerId));
$userContainer = $modx->getObject('modResource', $c);

if ($userContainer) {

    $userContainer->set('alias', $website);
    $userContainer->set('pagetitle', $website);
    $userContainer->save();
    $cacheManager = $modx->getCacheManager();
    $cacheManager->clearCache(array(
        "{$resource->context_key}/",
            ), array(
        'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
        'publishing' => true
            )
    );
}

<?php

/**
 * Wedding site User Edit Generic AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Check for a logged in user */
$user = $modx->user;
$userId = "";
if ($user) {
    $userId = $user->get('id');
    if ($userId == "")
        return "Error! No logged in user";
}

/* Save the tiny MCE edit area */

$userPageId = $_REQUEST['userPage'];

/* Set the page content */
$userPage = $modx->getObject('modResource', $userPageId);
if (!$userPage) {
    $modx->setPlaceholder('userEditGenericError', "Error - No user page found");
    return "Error - No user page found";
}

/* Add the HTML Purifier  path and include the class */
$htmlPurifierPath = $modx->getOption('ws_htmlpurifier.core_path', null, $modx->getOption('core_path') . 'components/ws_htmlpurifier/');
include_once $htmlPurifierPath . "library/HTMLPurifier.auto.php";
$purifier = new HTMLPurifier();

$content = $_REQUEST['userEditGenericTinyMCEcontent'];
$content = $purifier->purify($content);
$userPage->set('content', $content);
$userPage->save();

/* Clear the cache */
$cacheManager = $modx->getCacheManager();
$cacheManager->clearCache(array(
    "{$resource->context_key}/",
        ), array(
    'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
    'publishing' => true
        )
);


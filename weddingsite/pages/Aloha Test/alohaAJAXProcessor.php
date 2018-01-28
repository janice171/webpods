<?php
/**
 * Wedding site User Aloha AJAX processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/* Prepare the content */

$content = $_REQUEST['content'];
$element = $_REQUEST['element'];
$resourceId  = $_REQUEST['resourceId'];
$resource = $modx->getObject('modResource', $resourceId);

/* Update the content part dependant on element type */
switch ($element) {

    case "alohaTitle":
        $resource->set('pagetitle', $content);
        break;

    case "alohaLongTitle":
        $resource->set('longtitle', $content);
        break;

    case "alohaDescription":
        $resource->set('description', $content);
        break;

    case "alohaAlias":
        $resource->set('alias', $content);
        break;

    case "alohaSummary":
        $resource->set('introtext', $content);
        break;

    case "alohaMenuTitle":
        $resource->set('menutitle', $content);
        break;

    case "alohaMenuIndex":
        $resource->set('menuindex', $content);
        break;

    case "alohaContent":
        $resource->set('content', $content);
        break;

    default:
        break;
}

/* Save the resource */
$success = $resource->save();
if ($success === false) {
    $this->modx->setPlaceholder('fp.error_message', $this->modx->lexicon('cantsave'));
    return;
}

/* Clear the cache */
$cacheManager = $modx->getCacheManager();
$cacheManager->clearCache(array(
    "{$resource->context_key}/resources/",
    "{$resource->context_key}/context.cache.php",
        ), array(
    'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
    'publishing' => true
        )
);

return $element;

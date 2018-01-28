<?php

/**
 * Wedding site User Edit Blog AJAX processing
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


/* Get the command type and switch */
if (!isset($_REQUEST['command'])) {

    $response['error'] = "No command specified";
    $output = json_encode($response);
    return $output;
}

$command = $_REQUEST['command'];
$response = array();

switch ($command) {

    case 'edit':

        /* Get the blog details and return them */
        $error = "none";
        $id = $_REQUEST['id'];
        $blog = $modx->getObject('modResource', array('id' => $id));
        if (!$blog) {

            $response['error'] = "No blog resource found with id $id";
            $output = json_encode($response);
            return $output;
        }

        $title = $blog->get('pagetitle');
        $content = $blog->getContent();
        $response['error'] = "none";
        $response['title'] = $title;
        $response['content'] = $content;
        $response['id'] = $id;
        $output = json_encode($response);

        break;


    case 'save' :

        /* Get the page id, If the id is -1, this is a create operation */
        $userBlogPageId = $_REQUEST['userBlogPage'];
        $create = $userBlogPageId == -1 ? true : false;
        
        /* Set the page content and title */
        if ( $create ) {
            
            $userBlogPage = $modx->newObject('modResource');
        
        } else {
            
            $userBlogPage = $modx->getObject('modResource', $userBlogPageId);
        }
        if (!$userBlogPage) {
            $modx->setPlaceholder('userEditBlogError', "Error - No user blog page found");
            return "Error - No user blog page found";
        }

        /* Add the HTML Purifier path and include the class */
        $htmlPurifierPath = $modx->getOption('ws_htmlpurifier.core_path', null, $modx->getOption('core_path') . 'components/ws_htmlpurifier/');
        include_once $htmlPurifierPath . "library/HTMLPurifier.auto.php";
        $purifier = new HTMLPurifier();

        $content = $_REQUEST['userEditBlogTinyMCEcontent'];
        $content = $purifier->purify($content);
        $userBlogPage->set('content', $content);
        
        /* Title and edited on */
        $title = strip_tags($_REQUEST['userEditBlogTitle']);
        $userBlogPage->set('pagetitle', $title);
        $userBlogPage->set('editedon', time());
        
        /* Additionals for create */
        if ( $create ) {
            
            /* Get the parent data */
            $userPageId = $_REQUEST['userPage'];
            $blogParent = $modx->getObject('modResource', $userPageId);
            if ( !$blogParent ) return "Create - no parent found";
            $template = $blogParent->get('template');
            
            /* Set the parent data */
            $userBlogPage->set('createdon', time());
            $userBlogPage->set('template', $template);
            $userBlogPage->set('parent', $userPageId);
            $userBlogPage->set('published', 1);
            $userBlogPage->set('publishedon', time());
            $userBlogPage->set('publishedby', $userId);
            $userBlogPage->set('createdby', $userId);
            $userBlogPage->set('richtext', 1);
            $alias = $userBlogPage->cleanAlias($title);
            $userBlogPage->set('alias', $alias);
            $context = $modx->context->get('key');
            $userBlogPage->set('context_key', $context);
            
        }
        
        /* Save it */
        $userBlogPage->save();

        /* Add the id just to force the alias to be unique on create */
        if ( $create ) {
            
            $id = $modx->lastInsertId();
            $alias = $userBlogPage->get('alias');
            $alias .= "-$id";
            $userBlogPage->set('alias', $alias);
            $userBlogPage->save();
        }
        
        /* Clear the cache */
        $cacheManager = $modx->getCacheManager();
        $cacheManager->clearCache(array(
            "{$resource->context_key}/",
                ), array(
            'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
            'publishing' => true
                )
        );

        break;
        
    case 'delete':
        
        /* Get the blog resource id */
        $error = "none";
        $id = $_REQUEST['id'];
        $blog = $modx->getObject('modResource', array('id' => $id));
        if (!$blog) {

            $response['error'] = "No blog resource found with id $id";
            $output = json_encode($response);
            return $output;
        }
        
        /* Delete it */
        $blog->remove();
        
        break;

    default:
        
        return "Unknown command";
}

/* Return the response */
return $output;





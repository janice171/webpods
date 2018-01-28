<?php
/**
 * Wedding site User Edit Blog processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Paths */
$assets_url = $modx->getOption('assets_url');
$wsJQueryPath = $modx->getOption('ws_jQuery.assets_url',null,$modx->getOption('assets_url').'components/ws_jQuery/');
$javascriptPath = $modx->getOption('ws_javascript.assets_url',null,$modx->getOption('assets_url').'components/ws_javascript/');

/* Common Scripts */
$websiteLinks = $modx->runSnippet('userAdminWebPageLinksCommonJS');
$modx->regClientStartupScript($websiteLinks, true);

/* Get the current logged in user details */
$user = $modx->user;
$userId = $user->get('id');
if ( $userId == "" ) return;
$weddingUser = $modx->getObject('weddingUser', $userId);
if ( $weddingUser == null ) return;
$weddingUser->attributes = $weddingUser->getOne('Attributes');
$attributes = $weddingUser->attributes->toArray();
$website = $attributes['website'];
$website = '"' . $website . '"';

/* Blog */
$blog = $javascriptPath . "js/pages/user/edit/blog/blog.js";
$modx->regClientStartupScript($blog);

$context = $modx->context->get('key');
$blogAJAXProcessorId = $modx->getOption('blogAJAXProcessorId');
$blogAJAXProcessorLink = $modx->makeURL($blogAJAXProcessorId, $context, "", "full");
$blogAJAXProcessorLink = '"' . $blogAJAXProcessorLink . '"';

/* TinyMCE */
$wsTinyMCEPath = $modx->getOption('ws_tinyMCE.assets_url',null,$modx->getOption('assets_url').'components/ws_tinyMCE/');
$jqueryTinyMCE = $wsTinyMCEPath . "js/jscripts/tiny_mce/jquery.tinymce.js";
$modx->regClientStartupScript($jqueryTinyMCE);

/* Pajinate */
$pajinate = $wsJQueryPath . "js/pajinate/jquery.pajinate.min.js";
$modx->regClientStartupScript($pajinate);

/* Setup */
$blogDisplayHTML = $modx->getChunk('userEditBlogBackToBlogs');
$blogDisplayHTML = '"' . $blogDisplayHTML . '"';
$tinyMCEURL = $wsTinyMCEPath . "js/jscripts/tiny_mce/tiny_mce.js";
$tinyMCEURL =  '"' . $tinyMCEURL . '"';
$ready = '<script>
    
        WS.tinymceURL = ' . $tinyMCEURL . ';
        WS.website = ' . $website . ';
        WS.blogAJAXProcessorLink = ' . $blogAJAXProcessorLink . '; 
        WS.blogDisplayHTML = ' . $blogDisplayHTML . ';
       
</script>';
$modx->regClientStartupScript($ready, true);

$tinyMCE = $javascriptPath . "js/pages/user/edit/blog/tinymce.js";
$modx->regClientStartupScript($tinyMCE);
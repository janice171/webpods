<?php
/**
 * Wedding site userGenerateFEGuestbookSignlinks
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Generates the links in the FE for the users guestbook signing form
 * 
 *  
 *  Parameters :- None
 * 
 * 
 * Returns - html for the generated links
 * 
 */

/* Get the users username */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Get the user details */
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:=' => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if (!$weddingUserAttributes) return;
$weddingUserArray = $weddingUserAttributes->toArray();
$weddingUserId = $weddingUserAttributes->get('user');
$modxUser = $modx->getObject('modUser', $weddingUserId);
if (!$modxUser) return;
$userArray = $modxUser->toArray();
$moderate = $weddingUserArray['moderateGuestbook'];

/* Run quip reply */
$output = $modx->runSnippet('QuipReply',  array('thread' =>  $userArray['username'],
                                                                     'tplAddComment' => 'userGuestbookFEMessage',
                                                                     'moderate' => $moderate,
                                                                     'requirePreview' => 0,
                                                                     'closeAfter' => 0));

/* If we are previewing leave the URLS alone, otherwise make them 
 * user domain relative.
 */        
$urlType = $modx->runSnippet('userGetURLType');
if ( $urlType != 'front') return $output;

/* Generate the FE URL link */
$alias = $modx->resource->get('alias');  
$domain = $modx->getOption('domain');  
$protocol = $modx->getOption('server_protocol');
$tld = $modx->getOption('tld');
$contentType = $modx->resource->get('contentType');
$contentTypeArray = explode('/', $contentType);
$link = $protocol . '://' . $website . '.' . $domain . '.' . $tld . '/' . $alias . '.' . $contentTypeArray[1];  
$search = "users/$website/sign-guestbook.html";
$output = str_replace($search, $link, $output);

return $output;


<?php

/**
 * Wedding site getGuestEventPageLinks
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters - noReturn,  if set to 1 does not return anything fropm the chunk
 *              sets placeholders only, defaults to 0
 * Returns :- an array of links to the various guest/event management pages
 *            and sets placeholders to these links. 
 */

$noReturn = $noReturn == 1 ? true : false;
$context = $modx->context->get('key');
$links = array();

/* Guests */
$guestPageId = $modx->getOption('guestPageId');
$link = $modx->makeURL($guestPageId, $context, "", 'full');
$links['guestPage'] = $link;
$modx->setPlaceholder('guestPageLink',$link);

$userGuestEditPage = $modx->getOption('userGuestEditPage');
$link = $modx->makeURL($userGuestEditPage, $context, "", 'full');
$links['guestAddEditPage'] = $link;
$modx->setPlaceholder('guestAddEditPageLink',$link);

$userGuestImport = $modx->getOption('userGuestImport');
$link = $modx->makeURL($userGuestImport, $context, "", 'full');
$links['guestImportPage'] = $link;
$modx->setPlaceholder('guestImportPageLink',$link);

$userGuestExport = $modx->getOption('userGuestExport');
$link = $modx->makeURL($userGuestExport, $context, "", 'full');
$links['guestExportPage'] = $link;
$modx->setPlaceholder('guestExportPageLink',$link);

/* Events */
$eventPageId = $modx->getOption('eventPageId');
$link = $modx->makeURL($eventPageId, $context, "", 'full');
$links['eventPage'] = $link;
$modx->setPlaceholder('eventPageLink',$link);

$userEventEditPage = $modx->getOption('userEditEventPage');
$link = $modx->makeURL($userEventEditPage, $context, "", 'full');
$links['eventAddEditPage'] = $link;
$modx->setPlaceholder('eventAddEditPageLink',$link);

/* Invites */
$invitePageId = $modx->getOption('invitePageId');
$link = $modx->makeURL($invitePageId, $context, "", 'full');
$links['invitePage'] = $link;
$modx->setPlaceholder('invitePageLink',$link);

/* Settings and sundry */
$settingPageId = $modx->getOption('settingPageId');
$link = $modx->makeURL($settingPageId, $context, "", 'full');
$links['settingPage'] = $link;
$modx->setPlaceholder('settingPageLink',$link);

$homePageId = $modx->getOption('userHomePage');
$link = $modx->makeURL($homePageId, $context, "", 'full');
$links['homePage'] = $link;
$modx->setPlaceholder('homePageLink',$link);

$loginPageId = $modx->getOption('userLoginPage');
$link = $modx->makeURL($loginPageId, $context, "", 'full');
$links['loginPage'] = $link;
$modx->setPlaceholder('loginPageLink',$link);

/* Format for return */
if ( $noReturn) return;

$output = json_encode($links);
return $output;
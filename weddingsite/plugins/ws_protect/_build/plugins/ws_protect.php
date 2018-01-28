<?php

/**
 * Wedding site FE page password protected checker plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2012 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */
$output = $modx->resource->_output;

/* Check if we are Front end, if not return */
$type = $modx->runSnippet('userGetURLType');

if ($type != 'front')
    return $output;

/* Get the website and the user details */
$website = $modx->runSnippet('userGetWebsiteFromURL');

/* Get the user details */
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:=' => $website));
$weddingUserAttributes = $modx->getObject('weddingUserAttribute', $c);
if (!$weddingUserAttributes)
    return $output;
$attributes = $weddingUserAttributes->toArray();
$weddingUserId = $weddingUserAttributes->get('user');
$weddingUser = $modx->getObject('weddingUser', $weddingUserId);
if (!$weddingUser)
    return $output;

/* Check for a time out or a log in response */
if (isset($_SESSION[$website])) {

    $now = time();
    if (($now - ($validFor * 60) ) <= $_SESSION[$website]) {

        /* Still logged in */
        return $output;
    } else {

        /* Log the user out */
        unset($_SESSION[$website]);
    }
} else {

    if ($_SERVER['PHP_AUTH_PW'] == $attributes['websitePassword']) {

        /* Always set after initial auth */
        $_SESSION[$website] = time();
        return $output;
    }
}

/* Check if the website or the page we are on is password protected */
$protected = false;
if ($attributes['passwordProtect'] == 1)
    $protected = true;
$resourceId = $modx->resource->get('id');
$pages = $weddingUser->getMany('Pages');
foreach ($pages as $page) {
    if ($page->get('pageId') == $resourceId) {
        $pageSelected = $page->toArray();
        if ($pageSelected['passwordProtect'] == 1)
            $protected = true;
        break;
    }
}


/* Do the protection if needed.
 * if we are now more than xx away from first auth fail the request 
 */

if ($protected) {

    /* Ok, we need to ask for the password */
    header('WWW-Authenticate: Basic realm="Please enter your user(visitor) and password to view this page"');
    header('HTTP/1.0 401 Unauthorized');
    echo "You must enter a valid password to view this page\n";
    return;
} else {

    /* Not protected or authorized */
    return $output;
}​
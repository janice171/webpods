<?php

/**
 * Wedding site URL Check plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2012 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */

/* Get the URL */
$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

/* If we are going to the login page, allow it all the time */
if (preg_match('/user-login/', $pageURL) != 0)
    return;

/* Initialise */
$redirectToLogin = false;

/* Get login status */
$context = $modx->context->get('key');
$loggedIn = $modx->user->isAuthenticated($context);

if (!$loggedIn) {

    /* If we are trying to access the user admin area we must be logged in */
    if (preg_match('/user-administration/', $pageURL) != 0)
        $redirectToLogin = true;

    /* Redirect to login if we have to ie the checks above fail */
    if ($redirectToLogin) {

        $userLoginPage = $modx->getOption('userLoginPage');
        $loginURL = $modx->makeURL($userLoginPage, $context, "", "full");
        $modx->sendRedirect($loginURL);
    }
}



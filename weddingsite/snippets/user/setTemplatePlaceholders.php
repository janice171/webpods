<?php
/**
 * Wedding site setTemplatePlaceholders
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Sets user attribute placeholders for use in the templates
 * 
 *  
 *  Parameters :-
 * 
 * None
 * 
 * Returns - nothing, sets placeholders only
 * 
 * Placeholders - prefixed with weddinguser e.g [[+weddinguser.firstName]]
 * 
 * id - The wedding users id
 * user - The wedding users user Id, i.e the MODX user id
 * firstName - The wedding users first name
 * lastName - The wedding users last name
 * partnerName1 - The wedding users partners first name
 * partnerName2 - The wedding users partners second name
 * date - Date of the wedding as a UNIX timestamp, can be formatted with 
 *        output modifiers
 * date1 - The date of the wedding in the form 'July 22nd 2011'
 * website - The wedding users website address
 * theme - The wedding users theme 
 * packageType - The wedding users package type
 * passwordProtect - Whether the site is password protected
 * countdown - time in days to the wedding date
 * 
 * Profile details can be accessed using the 'user' placeholder such as 
 * [[+user.email]], the fields available correspondong to the ones from
 * the MODX user profile itself.
 */

/* Get the current user details from the username part of the incoming URL */
$urlArray = explode('.', $_SERVER[HTTP_HOST]);
$website = $urlArray[0];
/* Check for how we've accessed the page */
if ( $website == 'www') {
    
    /* Internally, get the user from the query string */
    $queryStringArray = explode('/', $_REQUEST['q']);
    $website = $queryStringArray[1];
}
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('website:='  => $website));
$weddingUser = $modx->getObject('weddingUserAttribute', $c);
/* If we have no user here it may be a preview, use defaults */
if ( !$weddingUser ) {
    
    $modx->setPlaceholder('weddinguser.partnerName1', 'Someone');
    $modx->setPlaceholder('weddinguser.partnerName2', 'Somebody');
    $modx->setPlaceholder('weddinguser.countdown', 6);
    return;
}

/* Wedding user */
$attributes = $weddingUser->toArray();

/* Format the date */
$attributes['date1'] = date('F jS Y', $attributes['date']);

/* Days */
$attributes['dayleading'] = date('d', $attributes['date']);
$attributes['daynoleading'] = date('j', $attributes['date']);
$attributes['daythreeletter'] = date('D', $attributes['date']);
$attributes['dayfull'] = date('l', $attributes['date']);

/* Month */
$attributes['monthleading'] = date('m', $attributes['date']);
$attributes['monthnoleading'] = date('n', $attributes['date']);
$attributes['monththreeletter'] = date('M', $attributes['date']);
$attributes['monthfull'] = date('F', $attributes['date']);

/* Year */
$attributes['yeartwo'] = date('y', $attributes['date']);
$attributes['yearfour'] = date('Y', $attributes['date']);

/* Set the placeholders */
$modx->toPlaceholders($attributes, "weddinguser");

/* Get the countdown days */
$date = $attributes['date'];
$now = time();
$dateDiffString = $modx->runSnippet('getTimeDifference', 
                                    array('start' => $now,
                                          'end' => $date));
$dateArray = json_decode($dateDiffString, true);
$modx->toPlaceholder('countdownTotalDays', $dateArray['days'], 'weddinguser');

/* Calculate months/days, assume a month is 30 days */
$months = floor($dateArray['days'] / 30);
$days = $dateArray['days'] % 30;

$modx->toPlaceholder('countdownDays', $days, 'weddinguser');
$modx->toPlaceholder('countdownMonths', $months, 'weddinguser');

/* User Profile */
$user = $modx->getObject('modUser', $attributes['user']);
if ( !$user ) return;
$userProfile = $user->getOne('Profile');
$profile = $userProfile->toArray();

$modx->toPlaceholders($profile, "user");
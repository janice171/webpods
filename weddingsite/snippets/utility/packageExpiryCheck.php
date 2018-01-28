<?php

/**
 * Wedding site packageExpiryCheck
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Site package expiry check snippet
 * 
 * Called on a regular(cron) basis from the Dits Newsletter cron interface
 * 
 * Parameters :-
 * 
 *  None:
 */
/* Get all the 'trial users' who haven't been processed */
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('packageType:=' => 'Trial'));
$c->andCondition(array('expiryCount:<' => 3));
$weddingUsers = $modx->getCollection('weddingUserAttribute', $c);

if (count($weddingUsers) == 0) {

    $modx->log(modX::LOG_LEVEL_ERROR, 'WS - Package expiry check - no users found for expiry');
    return;
}
$number = count($weddingUsers);
$modx->log(modX::LOG_LEVEL_ERROR, "WS - Package expiry check - $number users found for expiry");

/* Check for test mode */
if ($testMode == 1) {

    $modx->log(modX::LOG_LEVEL_ERROR, 'WS - Package expiry check - test mode - exiting');
    return;
}

/* Process each user */
$now = time();
foreach ($weddingUsers as $weddingUser) {

    $registrationDate = $weddingUser->get('registrationDate');
    $userId = $weddingUser->get('user');
    $modxUser = $modx->getObject('modUser', $userId);
    if (!$modxUser)
        continue;
    
    /* Check for test couples */
    $testCouple = $weddingUser->get('hearAbout');
    if ( $testCouple == 'Yes') {
        
        $oneYear = strtotime("+1 year", $registrationDate);
        if ($now >= $oneYear) {
            
            $weddingUser->set('hearAbout', '');
            
        } else {
            
            continue;
            
        }
        
    }
    
    $expiryCount = $weddingUser->get('expiryCount');
    $userName = $modxUser->get('username');
    $modx->log(modX::LOG_LEVEL_ERROR,"WS - Package expiry check - user - $userName");

    switch ($expiryCount) {

        /* 2 days before */
        case 0:

            $fiveDays = strtotime("+5 day", $registrationDate);
            if ($now >= $fiveDays) {

                $weddingUser->set('expiryCount', 1);
                $weddingUser->save();
                $userName = $modxUser->get('username');
                $firstName = $weddingUser->get('firstName');
                $message = $modx->getChunk('userPackageDaysLeft', array('daysLeft' => 2,
                    'firstName' => $firstName));
                $modx->runSnippet('mailMan', array('to' => $userName,
                    'subject' => $subject,
                    'message' => $message));
            }

            break;

        /* 1 day before */
        case 1:

            $sixDays = strtotime("+6 day", $registrationDate);
            if ($now >= $sixDays) {

                $weddingUser->set('expiryCount', 2);
                $weddingUser->save();
                $userName = $modxUser->get('username');
                $firstName = $weddingUser->get('firstName');
                $message = $modx->getChunk('userPackageDaysLeft', array('daysLeft' => 1,
                    'firstName' => $firstName));
                $modx->runSnippet('mailMan', array('to' => $userName,
                    'subject' => $subject,
                    'message' => $message));
            }

            break;

        /* Block check */
        case 2:

            $sevenDays = strtotime("+7 day", $registrationDate);
            if ($now >= $sevenDays) {

                $weddingUser->set('expiryCount', 3);
                $weddingUser->save();
                $userProfile = $modxUser->getOne('Profile');
                $userProfile->set('blocked', 1);
                $userProfile->save();
            }

            break;

        default:
    }
}

return;
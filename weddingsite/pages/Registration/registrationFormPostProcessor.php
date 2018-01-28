<?php

/**
 * Wedding site Registration processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */
/*  The parameters from the registration form are already valid at this point, we only need
 * to create the wedding user 
 */

$packageType = "None";

if ($_REQUEST['registrationType'] != "") {

    $packageType = 'Trial';
}

/*if ($_REQUEST['registrationSubmitPaid'] != "") {

    $packageType = 'Paid';
}*/

/* If we have a package type we have a submit operation */
if ($packageType != "None") {

    /* Check recaptcha 
    $recaptchaPath = $modx->getOption('ws_recaptcha.core_path', null, $modx->getOption('core_path') . 'components/ws_recaptcha/');
    include_once $recaptchaPath . "recaptchalib.php";

    $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_REQUEST["recaptcha_challenge_field"], $_REQUEST["recaptcha_response_field"]);

    if ($resp->error != "") {

        /* Set the error code and return 
        $_SESSION['recaptcha_error'] = $resp->error;
        return;
    }

    $_SESSION['recaptcha_error'] = "";
*/

    /* Create the MODX user */
    $user = $modx->newObject('modUser');
    $profile = $modx->newObject('modUserProfile');
    $user->set('username', $_REQUEST['registrationEmail']);
    $user->set('active', 1);
    $user->set('password', md5($_REQUEST['userPassword']));
    $profile->set('email', $_REQUEST['registrationEmail']);
    $profile->set('logincount', 0);
    $profile->set('blocked', 1);
    $user->addOne($profile, 'Profile');

    if (!$user->save()) {
        $modx->setPlaceholder('registrationError', "Error - Failed to create this system user");
    }

    /* Now add the wedding user details */
    $id = $user->get('id');
    $weddingUser = $modx->newObject('weddingUserAttribute', array('user' => $id));
    $weddingUser->set('packageType', $packageType);
    if (!$weddingUser->save()) {
        $modx->setPlaceholder('registrationError', "Error - Failed to create this wedding user");
    }

    /* Convert into a wedding user */
    $user->set('class_key', 'weddingUser');
    $user->save();
    
    /* Personal/Website details */
    $weddingUser->set('firstName', $_REQUEST['userSettingsFirstName']);
    $weddingUser->set('lastName', $_REQUEST['userSettingsLastName']);  
    $weddingUser->set('website', $_REQUEST['userRegistrationWebsiteName']);  
    $weddingUser->set('partnerName1',$_REQUEST['userSettingsPartner1']);
    $weddingUser->set('partnerName2',$_REQUEST['userSettingsPartner2']);
    /* Force guest book moderation on until we update to the new schema */
    $weddingUser->set('moderateGuestbook', 1);
    $dateText = $_REQUEST['userSettingsDatepicker'];
    $dateArray = explode('/', $dateText);
    $temp = $dateArray[2];
    $dateArray[2] = $dateArray[0];
    $dateArray[0] = $temp;
    $dateText = implode('-', $dateArray);
    $date = strtotime($dateText);
    $weddingUser->set('date', $date);
    include $modx->getOption('core_path').'lexicon/country/en.inc.php';
    $country = $_country_lang[$_REQUEST['userSettingsCountry']];
    $profile->set('country', $country);
    $profile->set('city', $_REQUEST['userSettingsCity']);
    $profile->set('address', $_REQUEST['userSettingsAddressStreet']);
    $profile->set('phone', $_REQUEST['userSettingsTel']);
    
    /* Use this a first login marker now */
    $weddingUser->set('personalDetails', 1);
    
    /* Registration type */
    if ( $_REQUEST['registrationType'] == 'testCouple') $weddingUser->set('hearAbout', 'Yes');
    
    /* Save the info */
    $weddingUser->save();
    $profile->save();

    /* Send the registration emails to the site admin and the user */
    $messageParameters = array();
    $messageParameters['username'] = $_REQUEST['registrationEmail'];
    $messageParameters['password'] = $_REQUEST['userPassword'];
    $messageParameters['type'] = $packageType;
    $messageParameters['adminemail'] = $modx->getOption('emailsender');
    /* Create the validation link */
    $context = $modx->context->get('key');
    $params = array('uid' => $id);
    $registrationValidate = $modx->getOption('registrationValidate');
    $validationLink = $modx->makeURL($registrationValidate, $context, $params, 'full');
    $messageParameters['registrationValidateLink'] = $validationLink;
    
    /* Admin */
    $subject = "New Registration";
    $message = $modx->getChunk('registrationAdminMail', $messageParameters);
    $modx->runSnippet('mailMan', array('subject' => $subject,
        'message' => $message,
        'to' => $messageParameters['adminemail']));

    /* User */
    $subject = "The Wedding Websites Group - New Registration";
    $message = $modx->getChunk('registrationUserMail', $messageParameters);
    $to = $_REQUEST['registrationEmail'];
    $modx->runSnippet('mailMan', array('subject' => $subject,
        'message' => $message,
        'to' => $to));

    /* Go to the Thank You page */
    $context = $modx->context->get('key');
    $registrationThankYouPage = $modx->getOption('registrationThankYouPage');
    $pageURL = $modx->makeURL($registrationThankYouPage, $context, "", "full");
    header("Location: {$pageURL}");
}




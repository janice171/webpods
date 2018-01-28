<?php
/**
 * Wedding site Registration processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

/* Get the recaptcah library */
$recaptchaPath = $modx->getOption('ws_recaptcha.core_path',null,$modx->getOption('core_path').'components/ws_recaptcha/');
include_once $recaptchaPath . "recaptchalib.php";

/* Set the recaptcha placeholder */
$output = recaptcha_get_html($publickey, $_SESSION['recaptcha_error']);
$modx->setPlaceholder('registration.recaptcha', $output);

/* Country processing */
$country = strlen($profile['country']) != 0  ? $profile['country'] : 'None';
include_once $modx->getOption('core_path').'lexicon/country/en.inc.php';
$countriesOutput = "";

if ( $country == "None" ) {
    
    /* Nothing set, autodetect */
    $modxCountryKey =  array_search('United Kingdom', $_country_lang );
    $countryInfoString = $modx->runSnippet('locator', array('ipAddress'=> 'remote', 'toJSON' => 1));
    $countryInfo = json_decode( $countryInfoString, true);
    if ( $countryInfo['error'] == 'None') {
        
        $modxCountryKey = $countryInfo['modxCountryCode'];
      
        
    }
    
} else {
    
    $modxCountryKey = array_search($country, $_country_lang );
}



foreach ( $_country_lang as $countryKey => $countryName ) {
    
    $countrySelected = "";
    if (   $modxCountryKey  == $countryKey ) $countrySelected = ' selected="yes" ';
    $countriesOutput .= $modx->getChunk('userSettingsCountry', array( 
                                          'countrySelected' => $countrySelected,
                                          'countryValue' => $countryKey,
                                          'countryName' => $countryName));
}
$modx->setPlaceholder('userSettingsCountries', $countriesOutput);

/* Registration type */
$registrationType = "normal";
if ( (isset($_REQUEST['type']) ) && ($_SESSION['registrationTestCouple'] == 'valid') ) {
    
    $registrationType = "testCouple";
    $_SESSION['registrationTestCouple'] = '';
}

$modx->setPlaceholder('registrationType', $registrationType);
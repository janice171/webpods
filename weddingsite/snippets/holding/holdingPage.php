<?php

/**
 * Wedding site holdingPage
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Parameters - none
 * Returns :- the relevant chunk to display on the holding page or
 *            a redirect to the registration page
 */

/* Get the number of test couples */
$c = $modx->newQuery('weddingUserAttribute');
$c->where(array('hearAbout:=' => 'Yes'));
$testerCount = $modx->getCount('weddingUserAttribute', $c);

/* Get the registration page and context */
$registrationPage = $modx->getOption('registrationPage');
$context = $modx->context->get('key');

/* Check for the limit reached */
if ( $testerCount == $maxTestCouples ) {
    
    $registrationURL = $modx->makeURL($registrationPage, $context, '', 'full');
    header("Location: {$registrationURL}");
}

/* Set the test couple valid session variable */
$_SESSION['registrationTestCouple'] = 'valid';

/* Output the register chunk */
$registrationURL = $modx->makeURL($registrationPage, $context, array('type' => 'testcouple'), 'full');
$output = $modx->getChunk('holdingFreeSlots', array('registrationLink' => $registrationURL ));
return $output;

<?php
/**
 * Wedding site mailMan
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Site mail management snippet
 * 
 * Parameters :-
 * 
 *  to : Recipient e-mail address, default is site admin
 *  from : Sender e-mail address, default is site admin
 *  fromName : Sender name, default site administrator
 *  message : The message text
 *  subject : Subject line
 *  html : true/false indicates mail should be sent as html
 */

$adminEmail = $modx->getOption('emailsender');
$to = isset($to) ? $to : $adminEmail;
$from = isset($from) ? $from : $adminEmail;
$fromName = isset($fromName) ? $fromName : "The Wedding Websites Site Administrator";
$html = isset($html) ? true : false;

/* Get the mail service if we have to */
if ( !$modx->mail ) $modx->getService('mail', 'mail.modPHPMailer');

/* Set the mail parameters */        
$modx->mail->set(modMail::MAIL_FROM, $from);
$modx->mail->set(modMail::MAIL_FROM_NAME, $fromName);
$modx->mail->address('to', $to);
$modx->mail->set(modMail::MAIL_SUBJECT, $subject);
$modx->mail->set(modMail::MAIL_BODY, $message);
if ( $html ) $modx->mail->setHTML(true);

/* Send the mail */
$modx->mail->send();
$modx->mail->reset();
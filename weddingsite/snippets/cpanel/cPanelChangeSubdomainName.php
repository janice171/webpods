<?php
/**
 * Wedding site CPanelChangeSubDomain
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Interfaces with the Cpanel XML-API to allow sub domain updates.
 * 
 * Parameters :- 
 * 
 * $to - The name of the subdomain to change to, e.g. janiceswedding 
 * $from - The name of the existing subdomain.
 */

/* Add the cPanel API path and include the class */
$cPanelPath = $modx->getOption('ws_cpanelapi.core_path',null,$modx->getOption('core_path').'components/ws_cpanelapi/');
include_once $cPanelPath . "xmlapi.php";

/* Construct the class, this also auths us */
$cPanelApi = new xmlapi($host, $user, $password);

/* Add the new sub domain */
$domain = $modx->getOption('domain');
$tld = $modx->getOption('tld');
$cpanelDomain = $domain . '.' . $tld;
$cPanelApi->api1_query($user,'SubDomain','addsubdomain',
                       array($to, $cpanelDomain, 0, 0, '/public_html'));

/* Delete the existing sub domain */
$cPanelApi->api1_query($user,'SubDomain','delsubdomain',
                       array($from));

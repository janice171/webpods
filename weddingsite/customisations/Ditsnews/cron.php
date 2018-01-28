<?php
require_once dirname(__FILE__).'/../../../../config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';

require_once MODX_CORE_PATH . "model/modx/modx.class.php";

require_once MODX_CORE_PATH.'/components/ditsnews/model/ditsnews/ditsnews.class.php';

$modx= new modX();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

$modx->initialize('web');

$ditsnews = new Ditsnews($modx);

$ditsnews->processQueue();

/* Run the Trial package expiry check */
$modx->log(modX::LOG_LEVEL_ERROR,'WS - Package expiry check - called from CRON');
$modx->runSnippet('packageExpiryCheck');

?>
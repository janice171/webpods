<?php

/**
 * Get users designs processor
 *
 * @category  Wedding Site
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2012 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 *
 * @package   ws_sitemanager
 * @subpackage processors
 **/
require_once dirname(dirname(__FILE__)).'/index.php';

/* Check the incoming parameters */

/* Check parameters */
if (empty($_REQUEST['userId'])) return $modx->error->failure($modx->lexicon('user_err_ns'));

$errorstring = "";

/* Pass the parameters to the Wedding Site class method */
$userId = $_REQUEST['userId'];
    
$result = $wssm->getUsersDesigns($userId, $errorstring, $nodes);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('getusersdesignsfailed')." - ".$errorstring);
}

return $nodes;

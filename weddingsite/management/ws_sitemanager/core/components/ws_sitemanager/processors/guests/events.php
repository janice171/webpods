<?php

/**
 * Get guests events processor
 *
 * @category  Wedding Site
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 *
 * @package   ws_sitemanager
 * @subpackage processors
 **/
require_once dirname(dirname(__FILE__)).'/index.php';

/* Check the incoming parameters */

/* Check parameters */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));

$errorstring = "";

/* Pass the parameters to the Wedding Site class method */
$id = $_REQUEST['id'];
$userId = $_REQUEST['userId'];

/* Select link or remove dependent on the type parameter */
$type = "link";
if ( $_REQUEST['type'] == "remove") $type = "remove";
    
$result = $wssm->getGuestsEvents($id, $userId, $type, $errorstring, $nodes);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('getguestseventsfailed')." - ".$errorstring);
}

return $nodes;

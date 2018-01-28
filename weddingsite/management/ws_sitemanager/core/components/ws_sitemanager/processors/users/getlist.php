<?php
/**
 * Get users processor
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

$limit = 20;
$start = 0;
$username = 'none';

if ($_REQUEST['limit'] != '') {
	
	$limit = $_REQUEST['limit'];
}

if ($_REQUEST['start'] != '') {
	
	$start = $_REQUEST['start'];
}

if ($_REQUEST['username'] != '') {
	
	$username = $_REQUEST['username'];
}

$errorstring = "";

/* Pass the parameters to the Wedding Site class method */
$result = $wssm->getUsers($start, $limit, $username, $errorstring, $nodes);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('getusersfailed')." - ".$errorstring);
}

return $nodes;


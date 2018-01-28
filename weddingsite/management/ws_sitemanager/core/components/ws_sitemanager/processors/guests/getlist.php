<?php
/**
 * Get guests processor
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
$result = $wssm->getGuests($id, $errorstring, $nodes);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('getguestsfailed')." - ".$errorstring);
}

return $nodes;


<?php
/**
 * Get a user processor
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

/* Check permissions */
if (!$modx->hasPermission('view_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* Check parameters */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));

/* Pass the parameters to the Wedding Site class method */
$id = $_REQUEST['id'];
$result = $wssm->getUser($id, $userArray, $errorstring);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('getuserfailed')." - ".$errorstring);
}

return $modx->error->success('',$userArray);

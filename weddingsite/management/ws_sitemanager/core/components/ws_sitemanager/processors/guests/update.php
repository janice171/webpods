<?php
/**
 * Update a guest processor
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


/* Get the user */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));

$id = $_REQUEST['id'];

/* Assemble the update parameters */
$guestArray = array ( 
            'name' => $_REQUEST['name'],
            'email' => $_REQUEST['email'],
            'address1' => $_REQUEST['address1'],
            'address2' => $_REQUEST['address2'],
            'city' => $_REQUEST['city'],
            'postCode' => $_REQUEST['postCode'],
            'telephone' => $_REQUEST['telephone'],
            'guestOf' => $_REQUEST['guestOf'],
            'active' => $_REQUEST['active'],
            'notes' => $_REQUEST['notes'],
            'party' => $_REQUEST['party']
    
        );
        
if ( $guestArray['active'] != 1 ) $guestArray['active'] = 0;

$result = $wssm->updateGuest($id, $guestArray, $errorstring);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('updateuserfailed')." - ".$errorstring);
}

return $modx->error->success('',$user);


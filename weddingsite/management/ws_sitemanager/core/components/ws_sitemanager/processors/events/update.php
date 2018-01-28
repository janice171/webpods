<?php
/**
 * Update an event processor
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
$eventArray = array ( 
            'name' => $_REQUEST['name'],
            'user' => $_REQUEST['user'],
            'date' => $_REQUEST['date'],
            'location' => $_REQUEST['location'],
            'address2' => $_REQUEST['address2'],
            'address3' => $_REQUEST['address3'],
            'address4' => $_REQUEST['address4'],
            'startTime' => $_REQUEST['startTime'],
            'endTime' => $_REQUEST['endTime'],
            'totalGuests' => $_REQUEST['totalGuests'],
            'maxGuests' => $_REQUEST['maxGuests'],
            'active' => $_REQUEST['active'],
            'notes' => $_REQUEST['notes'],
        );

if ( $eventArray['active'] != 1 ) $eventArray['active'] = 0;

$result = $wssm->updateEvent($id, $eventArray, $errorstring);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('updateuserfailed')." - ".$errorstring);
}

return $modx->error->success('',$user);



<?php
/**
 * Add an album processor
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
require_once dirname(dirname(dirname(__FILE__))).'/index.php';


/* Assemble the update parameters */
$albumArray = array ( 
            'albumName' => $_REQUEST['albumName'],
            'albumDescription' => $_REQUEST['albumDescription'],
            'user' => $_REQUEST['user'],
            'active' => $_REQUEST['active']
        );

if ( $albumArray['active'] != 1 ) $albumArray['active'] = 0;

$result = $wssm->addAlbum($albumArray, $errorstring);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('updateuserfailed')." - ".$errorstring);
}

return $modx->error->success('');

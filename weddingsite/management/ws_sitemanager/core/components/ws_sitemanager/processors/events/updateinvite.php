<?php
/**
 * Update an invite processor
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
$inviteArray = array ( 
            'willAttend' => $_REQUEST['willAttend'],
            'InviteSent' => $_REQUEST['InviteSent'],
            'RSVPDate' => $_REQUEST['RSVPDate'],
            'lastReminderDate' => $_REQUEST['lastReminderDate'],
            'RSVPdManual' => 0,
            'RSVPdOnline' => 0
        );

if ( $inviteArray['willAttend'] != 1 ) $inviteArray['willAttend'] = 0;
if ( $inviteArray['InviteSent'] != 1 ) $inviteArray['InviteSent'] = 0;
if ( $_REQUEST['rsvpMethod'] == 'RSVPdManual') $inviteArray['RSVPdManual'] = 1;
if ( $_REQUEST['rsvpMethod'] == 'RSVPdOnline') $inviteArray['RSVPdOnline'] = 1;

$result = $wssm->updateInvite($id, $inviteArray, $errorstring);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('updateinvitefailed')." - ".$errorstring);
}

return $modx->error->success('',$user);



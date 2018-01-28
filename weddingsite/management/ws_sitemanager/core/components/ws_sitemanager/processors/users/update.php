<?php
/**
 * Update a user processor
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
if (!$modx->hasPermission('save_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* Get the user */
if (empty($_REQUEST['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));

$id = $_REQUEST['user'];
$user = $modx->getObject('modUser',$id);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* Assemble the update parameters */
$userArray = array ( 
            'firstName' => $_REQUEST['firstName'],
            'lastName' => $_REQUEST['lastName'],
            'partnerName1' => $_REQUEST['partnerName1'],
            'partnerName2' => $_REQUEST['partnerName2'],
            'date' => $_REQUEST['date'],
            'website' => $_REQUEST['website'],
            'theme' => $_REQUEST['theme'],
            'hearAbout' => $_REQUEST['hearAbout'],
            'packageType' => $_REQUEST['packageType'],
            'passwordProtect' => $_REQUEST['passwordProtect'],
            'websiteActive' => $_REQUEST['websiteActive'],
            'email' => $_REQUEST['email'],
            'websiteDetails' =>  $_REQUEST['websiteDetails'],
            'personalDetails' =>  $_REQUEST['personalDetails'],
            'moderateGuestbook' =>  $_REQUEST['moderateGuestbook'],
            'websiteSearchable' =>  $_REQUEST['websiteSearchable'],
            'displayCountdown' =>  $_REQUEST['displayCountdown'],
            'socialFacebook' =>  $_REQUEST['socialFacebook'],
            'socialGoogle' =>  $_REQUEST['socialGoogle'],
            'socialTwitter' =>  $_REQUEST['socialTwitter'],
            'socialOther1' =>  $_REQUEST['socialOther1'],
            'socialOther2' =>  $_REQUEST['socialOther2'],
            'registrationDate' =>  $_REQUEST['registrationDate'],
            'domain' =>  $_REQUEST['domain'],
        );
        
if ( $userArray['passwordProtect'] != 1 ) $userArray['passwordProtect'] = 0;
if ( $userArray['websiteDetails'] != 1 ) $userArray['websiteDetails'] = 0;
if ( $userArray['websiteActive'] != 1 ) $userArray['websiteActive'] = 0;
if ( $userArray['personalDetails'] != 1 ) $userArray['personalDetails'] = 0;
if ( $userArray['moderateGuestbook'] != 1 ) $userArray['moderateGuestbook'] = 0;
if ( $userArray['websiteSearchable'] != 1 ) $userArray['websiteSearchable'] = 0;
if ( $userArray['displayCountdown'] != 1 ) $userArray['displayCountdown'] = 0;

$result = $wssm->updateUser($id, $userArray, $errorstring);

/* Check the result for error */
if ($result !== true) {
	return $modx->error->failure($modx->lexicon('updateuserfailed')." - ".$errorstring);
}

return $modx->error->success('');

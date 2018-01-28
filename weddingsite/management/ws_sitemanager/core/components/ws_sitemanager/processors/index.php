<?php
/**
 * Common processor
 *
 * @category  Wedding Site
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 *
 * @package ws_sitemanager
 * @subpackage processors
 */
require_once dirname(dirname(__FILE__)).'/model/ws_sitemanager/ws_sitemanager.class.php';

/* Load our main class */
$wssm = new WS_SiteManager($modx);

return $wssm->initialize('connector');


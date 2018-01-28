<?php
  /**
 * Wedding site Clear User Data plugin resolver
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */

$success = false;
$modx =& $object->xpdo;

switch($options[xPDOTransport::PACKAGE_ACTION]) {


    case xPDOTransport::ACTION_INSTALL;
			xPDOTransport::ACTION_UPGRADE;
					
            /* Resolve the plugin to its event */
            $modx->log(xPDO::LOG_LEVEL_INFO,'Attempting to attach plugin to event OnUserBeforeRemove');
            
            /* Get the plugin id */
            $plugin = $modx->getObject('modPlugin', array('name' => 'Clear User Data'));
            if ( !$plugin ) {
				$modx->log(xPDO::LOG_LEVEL_ERROR,'Cant get plugin ');
				$success = false;
				break;
			}
			$pluginId = $plugin->get('id');
								
			/* Create and connect the event */
		    $pluginEvent= $modx->newObject('modPluginEvent');
			$pluginEvent->set('pluginid', $pluginId);
			$pluginEvent->set('event', 'OnUserBeforeRemove');
			$pluginEvent->set('priority', 0);
			$pluginEvent->set('propertyset', 0);
			$success= $pluginEvent->save();
			if ( $success === false ) {
				$modx->log(xPDO::LOG_LEVEL_ERROR,'Cannot link plugin to event');
				$success = false;
				break;
			}  
				
            /* Ok, all is well */
            $success = true;
			break;

        
        case xPDOTransport::ACTION_UNINSTALL:
            /* Do nothing for now */
            $success = true;
            break;

}
return $success;
?>

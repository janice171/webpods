<?php
/**
 * Settings data script
 *
 * @category  Wedding Site
 * @package   WS_SiteManager
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 **/

/* Category */

$category = $modx->newObject('modCategory');
$category->fromArray(array(
    			'category' => 'WS_SiteManager'
				));
				
/* System Settings 

$datasetting = $modx->newObject('modSystemSetting');
$datasetting->fromArray(array(
				'key' => 'status',
				'value' => 2,
				'xtype' => 'textfield',
				'namespace' => 'provisioner',
				'area' => 'Provisioner'
				), '', true, true);
$settings[] = $datasetting;
unset($datasetting); */



				

<?php
/**
 * 
 * Wedding site User Extensions(ws_userextensions)
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 *
 * ws_userextensions is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * ws_userextensions is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ws_userextensions; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package ws_userextensions
 */
/**
 * Creates the tables on install
 *
 * @package ws_userextensions
 * @subpackage build
 */

$success = true;
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        /* ensure setting is correct on install and upgrade */
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('ws_userextensions.core_path',null,$modx->getOption('core_path').'components/ws_userextensions/').'model/';
			$modx->addPackage('ws_userextensions',$modelPath);
			 
            $setting = $modx->getObject('modSystemSetting',array(
                'key' => 'extension_packages',
            ));
            if (empty($setting)) {
                $setting = $modx->newObject('modSystemSetting');
                $setting->set('key','extension_packages');
                $setting->set('namespace','core');
                $setting->set('xtype','textfield');
                $setting->set('area','system');
            }
            $value = $setting->get('value');
            $value = $modx->fromJSON($value);
            if (empty($value)) {
                $value = array();
                $value['ws_userextensions'] = array(
                    'path' => '[[++core_path]]components/ws_userextensions/model/',
                );
                $value = '['.$modx->toJSON($value).']';
            } else {
                $found = false;
                foreach ($value as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        if ($kk == 'ws_userextensions') {
                            $found = true;
                        }
                    }
                }
                if (!$found) {
                    $value[]['ws_userextensions'] = array(
                        'path' => '[[++core_path]]components/ws_userextensions/model/',
                    );
                }
                $value = $modx->toJSON($value);
            }
            $value = str_replace('\\','',$value);
            $setting->set('value',$value);
            $setting->save();

            break;
        /* remove on uninstall */
        case xPDOTransport::ACTION_UNINSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('ws_userextensions.core_path',null,$modx->getOption('core_path').'components/ws_userextensions/').'model/';

            $setting = $modx->getObject('modSystemSetting',array(
                'key' => 'extension_packages',
            ));
            $value = $setting->get('value');
            $value = $modx->fromJSON($value);
            unset($value['ws_userextensions']);
            $value = $modx->toJSON($value);
            $setting->set('value',$value);
            $setting->save();
            break;
    }
}

return $success;

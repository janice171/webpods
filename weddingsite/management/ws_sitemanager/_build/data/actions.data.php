<?php
/**
 * Actions build script
 *
 * @category  Wedding Site
 * @package   WS_SiteManager
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 **/

/* Actions */
$action= $modx->newObject('modAction');
$action->fromArray(array(
    'id' => 1,
    'namespace' => 'ws_sitemanager',
    'parent' => '0',
    'controller' => 'index',
    'haslayout' => '1',
    'lang_topics' => 'ws_sitemanager:default',
    'assets' => '',
), '', true, true);

/* load menu into action */
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'ws_sitemanager',
    'parent' => 'components',
    'description' => 'ws_sitemanager.desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => '0',
    'params' => '',
    'handler' => '',
), '', true, true);
$menu->addOne($action);

return $menu;

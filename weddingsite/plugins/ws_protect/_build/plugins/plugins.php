<?php
/**
 * Wedding site FE page password protected checker plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */


$plugins = array();
$p = $modx->newObject('modPlugin');
$p->set('name', 'Protect');
$p->set('description', 'Wedding Site FE page password protected checker plugin');
$p->set('plugincode', file_get_contents($sources['plugins'] . 'ws_protect.php'));
$properties = include $sources['properties'] . 'properties.ws_protect.php';
if ( !empty($properties)) $p->setProperties($properties);
unset($properties);

include $sources['events'].'events.ws_protect.php';
$p->addMany($events);
unset($events);

$plugins[] = $p;
unset($p);





<?php
/**
 * Wedding site Contextualiser plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */


$plugins = array();
$p = $modx->newObject('modPlugin');
$p->set('name', 'Clear User Data');
$p->set('description', 'Wedding Site user data clear up plugin');
$p->set('plugincode', file_get_contents($sources['plugins'] . 'ws_clearuserdata.php'));
$properties = include $sources['properties'] . 'properties.ws_clearuserdata.php';
$p->setProperties($properties);
unset($properties);

include $sources['events'].'events.ws_clearuserdata.php';
$p->addMany($events);
unset($events);

$plugins[] = $p;
unset($p);





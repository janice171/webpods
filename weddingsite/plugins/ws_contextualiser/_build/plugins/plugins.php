<?php
/**
 * Wedding site Contextualiser plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2010 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */


$plugins = array();
$p = $modx->newObject('modPlugin');
$p->set('name', 'Contextualiser');
$p->set('description', 'Wedding Site Context Switcher');
$p->set('plugincode', file_get_contents($sources['plugins'] . 'ws_contextualiser.txt'));
$properties = include $sources['properties'] . 'properties.ws_contextualiser.php';
$p->setProperties($properties);
unset($properties);

include $sources['events'].'events.ws_contextualiser.php';
$p->addMany($events);
unset($events);

$plugins[] = $p;
unset($p);





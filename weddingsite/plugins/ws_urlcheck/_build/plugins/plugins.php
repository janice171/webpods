<?php
/**
 * Wedding site URL Check plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */


$plugins = array();
$p = $modx->newObject('modPlugin');
$p->set('name', 'URL Check');
$p->set('description', 'Wedding Site URL Checker plugin');
$p->set('plugincode', file_get_contents($sources['plugins'] . 'ws_urlcheck.php'));
$properties = include $sources['properties'] . 'properties.ws_urlcheck.php';
$p->setProperties($properties);
unset($properties);

include $sources['events'].'events.ws_urlcheck.php';
$p->addMany($events);
unset($events);

$plugins[] = $p;
unset($p);





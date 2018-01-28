<?php
/**
 * Wedding site FE page password protected checker plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */


$events = array();

$event = $modx->newObject('modPluginEvent');
$event->set('event', 'OnWebPagePrerender');
$event->set('priority', 0);
$event->set('propertyset', 0);

$events[] = $event;
unset($event);

return $events;

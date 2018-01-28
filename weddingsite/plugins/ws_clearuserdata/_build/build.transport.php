<?php
/**
 * Wedding site Clear User Data (ws_clearuserdata')
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * @package ws_clearuserdata'
 */
/**
 * ws_clearuserdata build script
 *
 * @package ws_clearuserdata
 * @subpackage build
 */

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$base = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $base . '/',
    'assets' => 'assets/components/ws_clearuserdata',
    'core' => 'core/components/ws_clearuserdata',
	'docs' => $base . '/assets/components/ws_clearuserdata/docs/',
	'chunks' => 'chunks/',
	'snippets' => 'snippets/',
    'templates' => 'templates/',
	'plugins' => 'plugins/',
    'events' => 'plugins/events/',
    'properties' => 'properties/',
	'resolvers' => 'resolvers/',
    'settings' => 'settings/',
    'resources' => 'resources/',
    'source_core' => $base . '/core/components/ws_clearuserdata',
    'source_assets' => $base . '/assets/components/ws_clearuserdata',
    'lexicon' => $base . 'core/components/ws_clearuserdata/lexicon/',
    'model' => $base . 'core/components/ws_clearuserdata/model/',
);
unset($base);

require_once dirname(__FILE__).'/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$name = 'ws_clearuserdata';
$version = '1.1.0';
$release = 'pl';

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage($name, $version, $release);
$builder->registerNamespace('ws_clearuserdata',false,true,'{core_path}components/ws_clearuserdata/');

$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'modPlugin' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name'),
            xPDOTransport::RELATED_OBJECTS => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
                'modPluginEvent' => array(
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => false,
                xPDOTransport::UNIQUE_KEY => array('pluginid','event')
        )))
    );

$category= $modx->newObject('modCategory');
$category->set('category','ws_clearuserdata');

/* Get plugin */
include_once($sources['plugins'] . 'plugins.php');

/* Add category items */
$category->addMany($plugins);


/* create a transport vehicle for the category data object */
$vehicle = $builder->createVehicle($category, $attr);
$vehicles[] = $vehicle;

/* Settings */
require_once $sources['settings'].'settings.data.php';

$attr = array(
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'key');

if ( !empty($settings) ) {
	foreach ($settings as $setting ) {

		$vehicle = $builder->createVehicle($setting, $attr);
		$vehicles[] = $vehicle;
	}
}

/* Resolvers, both php and file on the last vehicle*/
$vehicle = end($vehicles);

$vehicle->resolve('php',array(
            'type' => 'php',
            'source' => $sources['resolvers'] . 'resolver.php'));           
$vehicle->resolve('file', array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';"));


/* Add all the vehicles */
foreach ( $vehicles as $vehicle ) {
	$builder->putVehicle($vehicle);
}

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
));

/* zip up the package */
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO,"<br />\nPackage Built.<br />\nExecution time: {$totalTime}\n");

exit ();

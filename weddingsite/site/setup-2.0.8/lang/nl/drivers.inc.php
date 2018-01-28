<?php
/**
 * Dutch Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 * @author Bert Oost, <bertoost85@gmail.com>
 */
$_lang['mysql_err_ext'] = 'MODx eist de mysql extentie voor PHP en het lijkt niet geladen te zijn.';
$_lang['mysql_err_pdo'] = 'MODx eist de pdo_mysql driver wanneer native PDO gebruikt wordt en het lijkt niet geladen te zijn.';
$_lang['mysql_version_5051'] = 'MODx heeft problemen met jouw MySQL versie ([[+version]]), vanwege de vele fouten gerelateerd aan de PDO driver in deze versie. Upgrade MySQL om deze problemen te verhelpen. Ook wanneer je niet kiest voor MODx, is het aanbevolen dat je upgrade naar een nieuwere versie voor de veiligheid en stabiliteit van jouw website.';
$_lang['mysql_version_client_nf'] = 'MODx kon jouw MySQL client versie niet detecteren via mysql_get_client_info(). Controleer zelf of jouw MySQL client versie minimaal 4.1.20 is voordat je doorgaat.';
$_lang['mysql_version_client_start'] = 'Controleert jouw MySQL versie:';
$_lang['mysql_version_client_old'] = 'MODx ondervindt wellicht problemen omdat je een hele oude MySQL versie gebruikt ([[+version]]). MODx zal de installatie toestaan om deze MySQL versie te gebruiken, maar we kunnen niet garanderen dat alle functionaliteit beschikbaar is of naar behoren werkt zolang je oudere versies gebruikt van de MySQL client libraries.';
$_lang['mysql_version_fail'] = 'Je draait MySQL versie [[+version]], en MODx Revolution eist MySQL 4.1.20 of hoger. Upgrade MySQL naar minimaal versie 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODx kan jouw MySQL versie niet detecteren via mysql_get_server_info(). Controleer zelf of jouw MySQL client versie minimaal 4.1.20 is voordat je doorgaat.';
$_lang['mysql_version_server_start'] = 'Controleert MySQL server versie:';
$_lang['mysql_version_success'] = 'OKE! Draait: [[+version]]';


<?php

/**
 * Base controller file
 *
 * @category  Wedding Site
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 *
 * @package ws_sitemanager
 * @subpackage controllers
 */
require_once dirname(dirname(__FILE__)) . '/model/ws_sitemanager/ws_sitemanager.class.php';

/* Load our main class */
$wssm = new WS_SiteManager($modx);
$wssm->initialize('mgr');
$assetsUrl = $modx->getOption('ws_sitemanager.assets_url', null, $modx->getOption('assets_url') . 'components/ws_sitemanager/');

// Common js
$modx->regClientStartupScript($assetsUrl . 'js/ws_sitemanager.js');

// Check the action code and process accordingly
$action = $_REQUEST['action'];

switch ($action) {

    /* Users */
    case 'updateUser':

        // Update user
        if (!$modx->hasPermission('edit_user'))
            return $modx->error->failure($modx->lexicon('access_denied'));

        /* get user */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $user = $modx->getObject('weddingUser', $_REQUEST['id']);
        if ($user == null)
            return $modx->error->failure($modx->lexicon('user_err_nf'));
        $userId = $_REQUEST['id'];
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/users/update.js');
        $modx->regClientStartupScript($assetsUrl . 'js/users/wssm.user.update.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-user-update"
                ,user: "' . $userId . '"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    /* Guests */
    case 'getGuestList':

        // Get the guest list for a user
        if (!$modx->hasPermission('edit_user'))
            return $modx->error->failure($modx->lexicon('access_denied'));

        /* get user */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $user = $modx->getObject('weddingUser', $_REQUEST['id']);
        if ($user == null)
            return $modx->error->failure($modx->lexicon('user_err_nf'));
        $userId = $_REQUEST['id'];
        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/guests/update.js');
        $modx->regClientStartupScript($assetsUrl . 'js/guests/wssm.list.grid.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-panel-guests-list"
                ,user: "' . $userId . '"
            });
        });
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'updateGuest':

        // Update a specific guest

        /* get guest */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $guestId = $_REQUEST['id'];
        /* Get the user id */
        $guestObject = $modx->getObject('weddingUserGuest', array('id' => $guestId));
        $userId = $guestObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/guests/update-guest.js');
        $modx->regClientStartupScript($assetsUrl . 'js/guests/wssm.guest.update.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-guest-update"
                ,guest: "' . $guestId . '"
            });
            
        });
        
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'linkEvent':

        // Update a specific guest's link to an event

        /* get guest */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $guestId = $_REQUEST['id'];
        /* Get the user id */
        $guestObject = $modx->getObject('weddingUserGuest', array('id' => $guestId));
        $userId = $guestObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/guests/link-event.js');
        $modx->regClientStartupScript($assetsUrl . 'js/guests/wssm.event.link.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
         Ext.onReady(function() {
            
             MODx.load({
                xtype: "wssm-page-event-link"
                ,guest: "' . $guestId . '"
            });
        });
        
        WSSM.saved.User = "' . $userId . '"
        WSSM.saved.Guest = "' . $guestId . '"
            
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'removeEvent':

        // Remove a specific guest's link to an event

        /* get guest */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $guestId = $_REQUEST['id'];
        /* Get the user id */
        $guestObject = $modx->getObject('weddingUserGuest', array('id' => $guestId));
        $userId = $guestObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/guests/remove-event.js');
        $modx->regClientStartupScript($assetsUrl . 'js/guests/wssm.event.remove.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
         Ext.onReady(function() {
            
             MODx.load({
                xtype: "wssm-page-event-remove"
                ,guest: "' . $guestId . '"
            });
        });
        
        WSSM.saved.User = "' . $userId . '"
        WSSM.saved.Guest = "' . $guestId . '"
            
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'removeGuest':

        // Remove a specific guest

        /* get guest */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $guestId = $_REQUEST['id'];

        /* Get the user id before we remove the guest */
        $guestObject = $modx->getObject('weddingUserGuest', array('id' => $guestId));
        $userId = $guestObject->get('user');

        /* Remove the guest */
        $wssm->removeGuest($guestId);

        /* Redraw the guest list */
        parse_str($_SERVER['QUERY_STRING'], $queryArray);
        $newUrl = "index.php?a=" . $queryArray['a'] . "&action=getGuestList&id=" . $userId;
        header("Location: $newUrl");

        break;

    case 'addGuest':

        // Add a new guest
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $userId = $_REQUEST['id'];

        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/guests/add-guest.js');
        $modx->regClientStartupScript($assetsUrl . 'js/guests/wssm.guest.add.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-guest-add"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    /* Events */
    case 'getEventList':

        // Get the event list for a user
        if (!$modx->hasPermission('edit_user'))
            return $modx->error->failure($modx->lexicon('access_denied'));

        /* get user */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $user = $modx->getObject('weddingUser', $_REQUEST['id']);
        if ($user == null)
            return $modx->error->failure($modx->lexicon('user_err_nf'));
        $userId = $_REQUEST['id'];
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/update.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.list.grid.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-panel-events-list"
                ,user: "' . $userId . '"
            });
        });
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'invitesEvent':

        /* Get the event */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $eventId = $_REQUEST['id'];
        /* Get the user id */
        $eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
        $userId = $eventObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/update-invite.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.grid.event.invite.list.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-panel-events-invites-list"
                ,event: "' . $eventId . '"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'updateEvent':

        // Update a specific event

        /* get event */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $eventId = $_REQUEST['id'];
        /* Get the user id */
        $eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
        $userId = $eventObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/update-event.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.event.update.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-event-update"
                ,event: "' . $eventId . '"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'updateInvite':

        // Update a specific invite

        /* Get the invite */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $inviteId = $_REQUEST['id'];
        /* Get the invite data */
        $inviteObject = $this->modx->getObject('guestEvents', array('id' => $inviteId));
        if (!$inviteObject)
            return;
        /* Get the user id and event id */
        $eventId = $inviteObject->get('eventId');
        $eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
        $userId = $eventObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/update-invites-form.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.event.invite.update.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-invite-update"
                ,event: "' . $eventId . '"
                ,invite: "' . $inviteId . '"    
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'removeEvent':

        // Remove a specific event

        /* get event */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $eventId = $_REQUEST['id'];

        /* Get the user id before we remove the event */
        $eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
        $userId = $eventObject->get('user');

        /* Remove the evenr */
        $wssm->removeEvent($eventId);

        /* Redraw the event list */
        parse_str($_SERVER['QUERY_STRING'], $queryArray);
        $newUrl = "index.php?a=" . $queryArray['a'] . "&action=getEventList&id=" . $userId;
        header("Location: $newUrl");

        break;

    case 'addEvent':

        // Add a new event
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $userId = $_REQUEST['id'];

        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/add-event.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.event.add.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-event-add"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    /* Gallery */
    case 'getAlbumList':

        if (!$modx->hasPermission('edit_user'))
            return $modx->error->failure($modx->lexicon('access_denied'));
        
        /* Get the user */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $user = $modx->getObject('weddingUser', $_REQUEST['id']);
        if ($user == null)
            return $modx->error->failure($modx->lexicon('user_err_nf'));
        $userId = $_REQUEST['id'];
        
        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/update.js');
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/wssm.album.list.grid.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-panel-albums-list"
                ,user: "' . $userId . '"
            });
        });
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'itemsAlbum':

        /* Get the event */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $eventId = $_REQUEST['id'];
        /* Get the user id */
        $eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
        $userId = $eventObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/update-invite.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.grid.event.invite.list.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-panel-events-invites-list"
                ,event: "' . $eventId . '"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'updateAlbum':

        // Update a specific album

        /* Get the album  */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $albumId = $_REQUEST['id'];
        /* Get the user id */
        $albumObject = $modx->getObject('weddingUserGalleryAlbum', array('id' => $albumId));
        $userId = $albumObject->get('user');
        /* register JS scripts */   
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/update-album.js');
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/wssm.album.update.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-album-update"
                ,album: "' . $albumId . '"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'updateItem':

        // Update a specific invite

        /* Get the invite */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $inviteId = $_REQUEST['id'];
        /* Get the invite data */
        $inviteObject = $this->modx->getObject('guestEvents', array('id' => $inviteId));
        if (!$inviteObject)
            return;
        /* Get the user id and event id */
        $eventId = $inviteObject->get('eventId');
        $eventObject = $modx->getObject('weddingUserEvent', array('id' => $eventId));
        $userId = $eventObject->get('user');
        /* register JS scripts */
        $modx->regClientStartupScript($modx->getOption('manager_url') . 'assets/modext/util/datetime.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/update-invites-form.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.event.invite.update.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-invite-update"
                ,event: "' . $eventId . '"
                ,invite: "' . $inviteId . '"    
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    case 'removeAlbum':

        // Remove a specific album

        /* Get the album */
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $albumId = $_REQUEST['id'];

        /* Get the user id before we remove the event */
        $albumObject = $modx->getObject('weddingUserGalleryAlbum', array('id' => $albumId));
        $userId = $albumObject->get('user');

        /* Remove the evenr */
        $wssm->removeAlbum($albumId);

        /* Redraw the event list */
        parse_str($_SERVER['QUERY_STRING'], $queryArray);
        $newUrl = "index.php?a=" . $queryArray['a'] . "&action=getAlbumList&id=" . $userId;
        header("Location: $newUrl");

        break;

    case 'addAlbum':

        // Add a new album
        if (empty($_REQUEST['id']))
            return $modx->error->failure($modx->lexicon('user_err_ns'));
        $userId = $_REQUEST['id'];
        
        /* Check the number of albums */
        $c = $modx->newQuery('weddingUser');
        $c->rightJoin('weddingUserGalleryAlbum', 'GalleryAlbum');
        $c->where(array('id' => $userId));
        $albumCount = $modx->getCount('weddingUser', $c);
        if ( $albumCount == 6 ) {
             return $modx->error->failure($modx->lexicon('albumlimitreached'));
            
        }
        
        /* register JS scripts */
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/add-album.js');
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/wssm.album.add.js');
        $modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "wssm-page-album-add"
            });
        });
        WSSM.saved.User = "' . $userId . '"
        // ]]>
        </script>');

        return $modx->smarty->fetch('wssmupdate.tpl');

    default:

        // Default is home
        $modx->regClientStartupScript($assetsUrl . 'js/ws_home.js');
        /* Users tab */
        $modx->regClientStartupScript($assetsUrl . 'js/users/users.js');
        $modx->regClientStartupScript($assetsUrl . 'js/users/wssm.user.grid.js');
        /* Guests tab */
        $modx->regClientStartupScript($assetsUrl . 'js/guests/guests.js');
        $modx->regClientStartupScript($assetsUrl . 'js/guests/wssm.guests.grid.js');
        /* Events tab */
        $modx->regClientStartupScript($assetsUrl . 'js/events/events.js');
        $modx->regClientStartupScript($assetsUrl . 'js/events/wssm.events.grid.js');
        /* Gallery  tab */
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/gallery.js');      
        $modx->regClientStartupScript($assetsUrl . 'js/gallery/wssm.gallery.grid.js');

        return $modx->smarty->fetch('wssmindex.tpl');
}

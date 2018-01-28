<?php

/**
 * Main Wedding Site Management class
 *
 * @category  WEdding Site
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 * @link      none
 *
 * @package ws_sitemanager
 */
class WS_SiteManager {

    /**
     * @var config local configuration settings
     * @access public
     */
    var $config = array();

    /*     * #@+
     * Constructor
     *
     * @param object &$modx class we are using.
     *
     * @return WS_SiteManager A unique WS_SiteManager instance.
     */

    function WS_SiteManager(&$modx) {
        $this->modx = & $modx;
    }

    /**
     * Initalize the class
     *
     * @access public
     * @param string $ctx context we are using.
     *
     * @return void
     */
    function initialize($ctx = 'mgr') {

        /* MODx provides us with the 'namespace_path' config setting
         * when loading custom manager pages. Set our base and core paths */
        $this->config['base_path'] = $this->modx->getOption('ws_sitemanager.core_path', null, $this->modx->getOption('core_path') . 'components/ws_sitemanager/');
        $this->config['core_path'] = $this->config['base_path'];

        /* add the WS_SiteManager model into MODx */
        $this->modx->addPackage('ws_sitemanager', $this->config['core_path'] . 'model/');

        /* add the WS_UserExtensions model into MODx */
        $basePath = $this->modx->getOption('ws_userextensions.core_path', null, $this->modx->getOption('core_path') . 'components/ws_userextensions/');
        $this->modx->addPackage('ws_userextensions', $basePath . 'model/');

        /* Load the 'default' lang foci, which is default.inc.php. */
        $this->modx->lexicon->load('ws_sitemanager:default');

        /* Load core user lexicon */
        $this->modx->lexicon->load('core:user');

        /* Mgr context only */
        switch ($ctx) {
            case 'mgr': /* we only want this stuff to really happen in mgr context anyway */
                $this->config['template_path'] = $this->config['core_path'] . 'templates/';
                $this->modx->smarty->setTemplatePath($this->config['template_path']);

                /* Refresh the smarty config and lexicon so it loads newly loaded custom data */
                $this->modx->smarty->assign('_config', $this->modx->config);
                $this->modx->smarty->assign('_lang', $this->modx->lexicon->fetch());
                break;
        }
    }

    /**
     * Get Users function
     *
     * @access public
     * @param $start the start record, usually 0
     * @param $limit the amount to get
     * @param $username an optional user name filter
     * @param $errorstring a returned error string.
     * @param $nodes the user rows.
     * @return boolean
     */
    function getUsers($start, $limit, $username, &$errorstring, &$nodes) {

        /* Convert any awaiting normal users */
        $this->_ConvertUsers();

        /* Get the users */
        $c = $this->modx->newQuery('weddingUser');
        $c->leftJoin('modUserProfile', 'Profile');
        $c->rightJoin('weddingUserAttribute', 'Attributes');
        if ($username != 'none') {
            $c->where(array('Attributes.lastName:LIKE' => '%' . $username . '%'));
        }
        $count = $this->modx->getCount('weddingUser', $c);
        $c->select(array(
            'weddingUser.*',
            'Profile.fullname',
            'Profile.email',
            'Profile.blocked',
            'Attributes.firstName',
            'Attributes.lastName',
            'Attributes.website'
        ));
        $sort = $this->modx->getOption('sort', $scriptProperties, 'username');
        if ($sort == 'username_link')
            $sort = $modx->getOption('sort', $scriptProperties, 'username'); $sort = 'username';
        if ($sort == 'id')
            $sort = 'weddingUser.id';
        $c->sortby($sort, $dir);
        if ($isLimit)
            $c->limit($limit, $start);

        $users = $this->modx->getCollection('weddingUser', $c);

        /* iterate through users */
        $list = array();
        foreach ($users as $user) {
            $userArray = $user->toArray();
            $userArray['blocked'] = $user->get('blocked') ? true : false;
            $userArray['cls'] = 'pupdate premove';
            unset($userArray['password'], $userArray['cachepwd']);
            $list[] = $userArray;
        }

        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }

    /**
     * Get Guests function
     *
     * @access public
     * @param $id the user id
     * @param $errorstring a returned error string.
     * @param $nodes the user guest rows.
     * @return boolean
     */
    function getGuests($id, &$errorstring, &$nodes) {

        /* Assemble the criteria */
        $c = $this->modx->newQuery('weddingUser');
        $c->rightJoin('weddingUserGuest', 'Guests');
        $c->where(array('id' => $id));
        $c->select(array(
            'Guests.*'
        ));

        $guests = $this->modx->getCollection('weddingUser', $c);

        /* iterate through the guests */
        $list = array();
        foreach ($guests as $guest) {
            $guestArray = $guest->toArray();
            $guestArray['active'] = $guest->get('active') ? true : false;
            $guestArray['guid'] = $this->modx->runSnippet('getGuestsGuid', array('guestId' => $guestArray['id']));
            $guestArray['cls'] = 'pupdate premove';
            
            $list[] = $guestArray;
        }

        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }

    /**
     * Convert Users function
     *
     * @access private
     * 
     */
    private function _ConvertUsers() {

        /* Get a list of all users awaiting conversion */
        $c = $this->modx->newQuery('modUser');
        $c->leftJoin('modUserProfile', 'Profile');
        $c->where(array('Profile.comment:LIKE' => '%' . 'wedding user' . '%'));

        $users = $this->modx->getCollection('modUser', $c);

        /* Convert them */
        foreach ($users as $user) {

            $id = $user->get('id');
            $userExists = $this->modx->getObject('weddingUserAttribute', array('user' => $id));
            if ($userExists == null) {

                $weddingUser = $this->modx->newObject('weddingUserAttribute', array('user' => $id));
                $weddingUser->save();
            }
            $user->profile = $user->getOne('Profile');
            $user->profile->set('comment', '');
            $user->profile->save();
            $user->set('class_key', 'weddingUser');
            $user->save();
        }
    }

    /**
     * Format Dates function
     *
     * @access private
     * 
     * @param $dateString the date string from the component form
     * @return int unix timestamp
     */
    private function _FormatDates($dateText) {

        $dateArray = explode('-', $dateText);
        $tempArray = explode(' ', $dateArray[2]);
        $temp = $tempArray[0];
        $dateArray[2] = $dateArray[0];
        $dateArray[0] = $temp;
        $returnDate = implode('-', $dateArray);
        return strtotime($returnDate);
    }
    
     /**
     * Check website unique function
     *
     * @access private
     * 
     * @param $website the website name to check
     * @param $id users id
     * @return boolean true if unique
     */
    private function _CheckWebsiteUnique($website, $id) {

    $c = $this->modx->newQuery('weddingUser');
    $c->leftJoin('modUserProfile', 'Profile');
    $c->rightJoin('weddingUserAttribute', 'Attributes');
    $c->where(array('Attributes.website:=' => $webSite));
    $c->andCondition(array('Profile.internalKey:!=' => $id));
    $users = $this->modx->getCollection('weddingUser', $c);

    if (empty($users)) {
        return 'true';
    } else {
        return 'false';
    }
    
    }

    /* Get User function
     *
     * @access public
     * 
     * @param $id the user id
     * @param $userArray the user details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function getUser($id, &$userArray, &$errorstring) {

        /* Assemble the criteria */
        $c = $this->modx->newQuery('weddingUser');
        $c->leftJoin('modUserProfile', 'Profile');
        $c->rightJoin('weddingUserAttribute', 'Attributes');
        $c->where(array('id' => $id));
        $c->select(array(
            'Attributes.*',
            'Profile.email'
        ));
        /* Get the user */
        $weddingUser = $this->modx->getObject('weddingUser', $c);
        if ($weddingUser == null) {
            return false;
        }

        $userArray = $weddingUser->toArray();

        /* Format for return as unix timestamp * 1000 for millisecond value */
        $userArray['date'] = (int) $userArray['date'] * 1000;
        $userArray['registrationDate'] = (int) $userArray['registrationDate'] * 1000;

        return true;
    }

    /* Get Guest function
     *
     * @access public
     * 
     * @param $id the guest id
     * @param $guestArray the guest details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function getGuest($id, &$guestArray, &$errorstring) {

        /* Get the guest */
        $weddingGuest = $this->modx->getObject('weddingUserGuest', array('id' => $id));
        if ($weddingGuest == null) {
            return false;
        }

        $guestArray = $weddingGuest->toArray();
        /* GUID */
        $guestArray['guid'] = $this->modx->runSnippet('getGuestsGuid', array('guestId' => $id));

        return true;
    }

    /* Update User function
     *
     * @access public
     * 
     * @param $id the user id
     * @param $userArray the user details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function updateUser($id, $userArray, &$errorstring) {


        /* Check the user */
        $user = $this->modx->getObject('modUser', array('id' => $id));
        if ($user == null)
            return false;

        /* Update the email */
        $user->profile = $user->getOne('Profile');
        $user->profile->set('email', $userArray['email']);
        if ($user->profile->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        /* Update the wedding user attributes */
        $weddingUser = $this->modx->getObject('weddingUser', $id);
        if ($weddingUser == null)
            return false;
         $weddingUser->attributes = $weddingUser->getOne('Attributes');
         
        /* Wedding date */
        $userArray['date'] = $this->_FormatDates($userArray['date']);

        /* Registration date */
        $userArray['registrationDate'] = $this->_FormatDates($userArray['registrationDate']);

        /* Website creation or name change */
        $existingWebsite = $weddingUser->attributes->get('website');
        $userArray['website'] = trim($userArray['website']);
        if ($existingWebsite != $userArray['website']) {
        
            /* Check uniqueness */
            $unique =  $this->_CheckWebsiteUnique($userArray['website'], $id);
            if ( !$unique) {
                
                $errorstring = $this->modx->lexicon('website_not_unique');
                return false;
            }
            
            /* Create */
            if ( $existingWebsite == "") {
                
                $this->modx->runSnippet('userCreateWebsiteManager', array('website' => $userArray['website'],
                    'id' => $id));
                
            } else {

            /* Rename */
            $this->modx->runSnippet('userRenameWebsite', array('website' => $userArray['website'],
                'existingWebsite' => $existingWebsite));
            
            }
            
        }
        
        /* Check for a change of package type */
        $existingPackage = $weddingUser->attributes->get('packageType');
        if ( $userArray['packageType'] != $existingPackage ) $userArray['expiryCount'] = 0;
        
        /* Update the attributes */
        $weddingUser->attributes->fromArray($userArray);
        if ($weddingUser->attributes->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }

    /* Update Guest function
     *
     * @access public
     * 
     * @param $id the guest id
     * @param $guestArray the guest details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function updateGuest($id, $guestArray, &$errorstring) {


        /* Update the guest attributes */
        $guestUser = $this->modx->getObject('weddingUserGuest', $id);
        if ($guestUser == null)
            return false;
        $guestUser->fromArray($guestArray);
        if ($guestUser->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }

    /* Remove Guest function
     *
     * @access public
     * 
     * @param $id the guest id
     * @return boolean
     */

    function removeGuest($id) {


        /* Remove the guest attributes */
        $guestUser = $this->modx->getObject('weddingUserGuest', $id);
        if ($guestUser == null)
            return false;
        $guestUser->remove();

        /* Nothing we can do here if this fails */
        return true;
    }

    /* Add Guest function
     *
     * @access public
     * 
     * @param $guestArray the guest details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function addGuest($guestArray, &$errorstring) {

        /* Get the wedding user for this guest */
        $userId = $guestArray['user'];
        $weddingUserObject = $this->modx->getObject('weddingUser', $userId);
        if ($weddingUserObject == null)
            return false;

        $newGuest = $this->modx->newObject('weddingUserGuest', $guestArray);
        if ($newGuest == null)
            return false;

        $weddingUserObject->addMany($newGuest, 'Guests');
        if ($weddingUserObject->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }

    /* Update Event function
     *
     * @access public
     * 
     * @param $id the event id
     * @param $eventArray the event details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function updateEvent($id, $eventArray, &$errorstring) {


        /* Update the event attributes */
        $eventUser = $this->modx->getObject('weddingUserEvent', $id);
        if ($eventUser == null)
            return false;
        $eventArray['date'] = $this->_FormatDates($eventArray['date']);
        $eventUser->fromArray($eventArray);
        if ($eventUser->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }
    
    /* Update Invite function
     *
     * @access public
     * 
     * @param $id the invite id
     * @param $inviteArray the invite details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function updateInvite($id, $inviteArray, &$errorstring) {


        /* Update the event attributes */
        $invite = $this->modx->getObject('guestEvents', $id);
        if ($invite == null)
            return false;
        $inviteArray['RSVPDate'] = $this->_FormatDates($inviteArray['RSVPDate']);
        $inviteArray['lastReminderDate'] = $this->_FormatDates($inviteArray['lastReminderDate']);
        $invite->fromArray($inviteArray);
        if ($invite->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }


    /* Remove Event function
     *
     * @access public
     * 
     * @param $id the event id
     * @return boolean
     */

    function removeEvent($id) {


        /* Remove the event attributes */
        $eventUser = $this->modx->getObject('weddingUserEvent', $id);
        if ($eventUser == null)
            return false;
        $eventUser->remove();

        /* Nothing we can do here if this fails */
        return true;
    }

    /* Add Event function
     *
     * @access public
     * 
     * @param $eventArray the guest details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function addEvent($eventArray, &$errorstring) {

        /* Get the wedding user for this event */
        $userId = $eventArray['user'];
        $weddingUserObject = $this->modx->getObject('weddingUser', $userId);
        if ($weddingUserObject == null)
            return false;

        $eventArray['date'] = strtotime($eventArray['date']);
        $newEvent = $this->modx->newObject('weddingUserEvent', $eventArray);
        if ($newEvent == null)
            return false;

        $weddingUserObject->addMany($newEvent, 'Events');
        if ($weddingUserObject->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }

    /**
     * Get Events function
     *
     * @access public
     * @param $id the user id
     * @param $errorstring a returned error string.
     * @param $nodes the user guest rows.
     * @return boolean
     */
    function getEvents($id, &$errorstring, &$nodes) {

        /* Assemble the criteria */
        $c = $this->modx->newQuery('weddingUser');
        $c->rightJoin('weddingUserEvent', 'Events');
        $c->where(array('id' => $id));
        $c->select(array(
            'Events.*'
        ));

        $events = $this->modx->getCollection('weddingUser', $c);

        /* iterate through the guests */
        $list = array();
        foreach ($events as $event) {
            $eventArray = $event->toArray();
            /* Format for return */
            $eventArray['date'] = strftime('%d-%m-%y', $eventArray['date']);
            $eventArray['cls'] = 'pupdate premove';
            $list[] = $eventArray;
        }
        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }
    
    /**
     * Get Invites function
     *
     * @access public
     * @param $id the event id
     * @param $errorstring a returned error string.
     * @param $nodes the user guest invite rows.
     * @return boolean
     */
    function getInvites($id, &$errorstring, &$nodes) {

        /* Assemble the criteria */
        $c = $this->modx->newQuery('guestEvents');
        $c->where(array('eventId' => $id));

        $invites = $this->modx->getCollection('guestEvents', $c);

        /* iterate through the invites */
        $list = array();
        foreach ($invites as $invite) {
            
            $inviteArray = $invite->toArray();
           
            /* Format for return */
            if ( $inviteArray['RSVPDate'] == 0 ) {
                
                $inviteArray['RSVPDate'] = "None";
                
            } else {
                
                $inviteArray['RSVPDate'] = strftime('%d-%m-%y', $inviteArray['RSVPDate']);
            }
            
            if ( $inviteArray['lastReminderDate'] == 0 ) {
                
                $inviteArray['lastReminderDate'] = "None";
                
            } else {
                
                $inviteArray['lastReminderDate'] = strftime('%d-%m-%y', $inviteArray['lastReminderDate']);
            }
            
            $inviteArray['cls'] = 'pupdate premove';
            
            /* Get the guest name */
            $guest = $this->modx->getObject('weddingUserGuest', $inviteArray['guestId']);
            if ( !$guest ) return false;
            $inviteArray['guestName'] = $guest->get('name');
            $list[] = $inviteArray;
        }
        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }

    /* Get Event function
     *
     * @access public
     * 
     * @param $id the event id
     * @param $eventArray the event details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function getEvent($id, &$eventArray, &$errorstring) {

        /* Get the event */
        $weddingEvent = $this->modx->getObject('weddingUserEvent', array('id' => $id));
        if ($weddingEvent == null) {
            return false;
        }

        $eventArray = $weddingEvent->toArray();
        $eventArray['date'] = (int) $eventArray['date'] * 1000;

        return true;
    }
    
    /* Get Invite function
     *
     * @access public
     * 
     * @param $id the invite id
     * @param $inviteArray the event details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function getInvite($id, &$inviteArray, &$errorstring) {

        /* Get the invite */
        $weddingInvite = $this->modx->getObject('guestEvents', array('id' => $id));
        if ($weddingInvite == null) {
            return false;
        }
        
        $inviteArray = $weddingInvite->toArray();
        $inviteArray['RSVPDate'] = (int) $inviteArray['RSVPDate'] * 1000;
        $inviteArray['lastReminderDate'] = (int) $inviteArray['lastReminderDate'] * 1000;
        $inviteArray['RSVPdNone'] = true;
        if ( $inviteArray['RSVPdOnline'] ||  $inviteArray['RSVPdManual'] ) $inviteArray['RSVPdNone'] = false;

        /* Get the event and guest name */
        $weddingEvent = $this->modx->getObject('weddingUserEvent', array('id' => $inviteArray['eventId']));
        $inviteArray['eventName'] = $weddingEvent->get('name');
        $weddingGuest = $this->modx->getObject('weddingUserGuest', array('id' => $inviteArray['guestId']));
        $inviteArray['guestName'] = $weddingGuest->get('name');
        
        return true;
    }

    /**
     * Get Events function
     *
     * @access public
     * @param $id the guest id
     * @param $type link or remove indicator
     * @param $errorstring a returned error string.
     * @param $nodes the user guest events rows.
     * @return boolean
     */
    function getGuestsEvents($id, $userId, $type, &$errorstring, &$nodes) {

        $guestObject = $this->modx->getObject('weddingUserGuest', array('id' => $id));

        /*
         * Get the events associated with this guest. 
         */
        $guestEvents = $guestObject->getMany('guestEvents');
        $guestList = array();
        foreach ($guestEvents as $eventPtr) {

            $event = $eventPtr->getOne('weddingUserEvent');
            $eventArray = $event->toArray();
            $guestList[] = $eventArray['name'];
        }

        /* Check for link or remove */
        switch ($type) {

            case "link" :

                /* Get all the events for this user and remove from thi set 
                  the ones we are already linked to
                 */

                /* Assemble the criteria */
                $c = $this->modx->newQuery('weddingUser');
                $c->rightJoin('weddingUserEvent', 'Events');
                $c->where(array('id' => $userId));
                $c->select(array(
                    'Events.*'
                ));

                $userEvents = $this->modx->getCollection('weddingUser', $c);
                $userList = array();
                foreach ($userEvents as $event) {

                    $eventArray = $event->toArray();
                    $outArray['name'] = $eventArray['name'];
                    $userList[] = $eventArray['name'];
                }

                /* Differ the arrays */
                if (count($guestList) != 0) {

                    $list = array();
                    $index = 0;
                    foreach ($userList as $userEvent) {

                        if ($userEvent != $guestList[$index]) {

                            $outArray['name'] = $userEvent;
                            $list[] = $outArray;
                        }
                        $index++;
                    }
                } else {

                    foreach ($userList as $name) {

                        $outArray['name'] = $name;
                        $list[] = $outArray;
                    }
                }

                break;

            case "remove" :

                /* We can only remove already linked events so just return the events */
                foreach ($guestList as $name) {

                    $outArray['name'] = $name;
                    $list[] = $outArray;
                }

                break;
        }

        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }

    /* Update Guest With an Event function
     *
     * @access public
     * 
     * @param $id the guest id
     * @param $updateArray the guest and event details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function updateGuestWithEvent($id, $updateArray, &$errorstring) {

        $user = $updateArray['user'];
        $name = $updateArray['name'];

        /* Get all the events for this user with this name */

        /* Assemble the criteria */
        $c = $this->modx->newQuery('weddingUserEvent');
        $c->where(array('user' => $user));
        $c->andCondition(array('name:=' => $name));
        $events = $this->modx->getCollection('weddingUserEvent', $c);

        /* Link the event to the guest */
        foreach ($events as $event) {

            $eventId = $event->get('id');
            $guestEventObject = $this->modx->newObject('guestEvents');
            $guestEventObject->set('guestId', $id);
            $guestEventObject->set('eventId', $eventId);
            $guestEventObject->save();
        }

        return true;
    }

    /* Remove an event from a guest function
     *
     * @access public
     * 
     * @param $id the guest id
     * @param $updateArray the guest and event details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function removeEventFromGuest($id, $updateArray, &$errorstring) {

        $user = $updateArray['user'];
        $name = $updateArray['name'];

        /* Get all the events for this user with this name */

        /* Assemble the criteria */
        $c = $this->modx->newQuery('weddingUserEvent');
        $c->where(array('user' => $user));
        $c->andCondition(array('name:=' => $name));
        $events = $this->modx->getCollection('weddingUserEvent', $c);

        /* Remove the event from the guest */
        foreach ($events as $event) {

            $eventId = $event->get('id');
            $guestEventObject = $this->modx->getObject('guestEvents', array(
                'guestId' => $id,
                'eventId' => $eventId
                    ));
            $guestEventObject->remove();
        }

        return true;
    }

    /**
     * Get User Designs function
     *
     * @access public
     * @param $userId user id
     * @param $errorstring a returned error string.
     * @param $nodes the user themes
     * @return boolean
     */
    function getUsersDesigns($userId, &$errorstring, &$nodes) {

        /* Get the user */
        $weddingUser = $this->modx->getObject('weddingUser', $userId);
        if ($weddingUser == null) {
            return false;
        }

        $userArray = $weddingUser->toArray();
        $designs = array();
        $list = array();
        
        /* Get the themes */
        $themesString = $this->modx->runSnippet('getThemeTemplates');
        $themeArray = json_decode($themesString, true);
        $themeData = $themeArray[0];
        $themeNames = $themeArray[1];
        
        foreach ( $themeData as $theme ) {
            
            $name = ucfirst($theme[0]) . " " . ucfirst($theme[1]) . " " . ucfirst($theme[2]);
            $designs['name'] = $name;
            $list[] = $designs;
            
        }
        
        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }
    
    /**
     * Get User Package function
     *
     * @access public
     * @param $userId user id
     * @param $errorstring a returned error string.
     * @param $nodes the user package types
     * @return boolean
     */
    function getUsersPackage($userId, &$errorstring, &$nodes) {

        /* Get the user */
        $weddingUser = $this->modx->getObject('weddingUser', $userId);
        if ($weddingUser == null) {
            return false;
        }

        $userArray = $weddingUser->toArray();
        $types = array();
        $list = array();
        
        /* Get the package types*/
        $packageString = $this->modx->runSnippet('getPackageTypes');
        $packageArray = json_decode($packageString, true);
        
        foreach ( $packageArray as $package ) {
            
            $types['type'] = $package;
            $list[] = $types;
            
        }
        
        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }
    
    /**
     * Get Albums function
     *
     * @access public
     * @param $id the user id
     * @param $errorstring a returned error string.
     * @param $nodes the user album rows.
     * @return boolean
     */
    function getAlbums($id, &$errorstring, &$nodes) {

        /* Assemble the criteria */
        $c = $this->modx->newQuery('weddingUser');
        $c->rightJoin('weddingUserGalleryAlbum', 'GalleryAlbum');
        $c->where(array('id' => $id));
        $c->select(array(
            'GalleryAlbum.*'
        ));

        $albums = $this->modx->getCollection('weddingUser', $c);

        /* iterate through the albums */
        $list = array();
        foreach ($albums as $album) {
            $albumArray = $album->toArray();
            /* Format for return */
            $albumArray['cls'] = 'pupdate premove';
            $list[] = $albumArray;
        }
        $count = count($list);
        $nodes = $this->modx->response->outputArray($list, $count);

        return true;
    }
    
    /* Remove Album function
     *
     * @access public
     * 
     * @param $id the album id
     * @return boolean
     */

    function removeAlbum($id) {


        /* Remove the album attributes */
        $album= $this->modx->getObject('weddingUserGalleryAlbum', $id);
        if ($album == null)
            return false;
        
        /* Get the website */
        $weddingUser = $album->get('user');
        $weddingUserAttributes = $this->modx->getObject('weddingUserAttribute', array('user' => $weddingUser));
        if ($weddingUserAttributes == null)
            return false;
        $website = $weddingUserAttributes->get('website');
        
        /* Remove the pictures from the user area and the item itself */
        $albumItems = $this->modx->getCollection('albumItems', array('albumId' => $id));
        foreach ( $albumItems as $albumItem ) {
            
          $itemId = $albumItem->get('itemId');
          $galleryItemObject = $this->modx->getObject('weddingUserGalleryItem', $itemId);
          if ( !$galleryItemObject ) return false;
          
         /* Delete the file */
         $galleryDirectory = $this->modx->runSnippet('userGetDirectory', array('directory' => "gallery",
                                                                         'website' => $website)); 
         $itemFilename = $galleryItemObject->get('itemFileName');
         @unlink($galleryDirectory . DIRECTORY_SEPARATOR .  $itemFilename);
         
         $galleryItemObject->remove();
         
        }
        
        /* Remove the album and its items */
        $album->remove();

        /* Nothing we can do here if this fails */
        return true;
        
    }
    
    /* Get Album function
     *
     * @access public
     * 
     * @param $id the album id
     * @param $albumArray the album details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function getAlbum($id, &$albumArray, &$errorstring) {

        /* Get the album */
        $weddingAlbum = $this->modx->getObject('weddingUserGalleryAlbum', array('id' => $id));
        if ($weddingAlbum == null) {
            return false;
        }

        $albumArray = $weddingAlbum->toArray();

        return true;
    }
    
    /* Add Album function
     *
     * @access public
     * 
     * @param $albumArray the album details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function addAlbum($albumArray, &$errorstring) {

        /* Get the wedding user for this album */
        $userId = $albumArray['user'];
        $weddingUserObject = $this->modx->getObject('weddingUser', $userId);
        if ($weddingUserObject == null)
            return false;

        $newAlbum = $this->modx->newObject('weddingUserGalleryAlbum', $albumArray);
        if ($newAlbum == null)
            return false;
        /* Get the existing count and add the position */
        $c = $this->modx->newQuery('weddingUser');
        $c->rightJoin('weddingUserGalleryAlbum', 'GalleryAlbum');
        $c->where(array('id' => $userId));
        $albumCount = $this->modx->getCount('weddingUser', $c);
        $newAlbum->set('albumPosition', $albumCount + 1);
        $newAlbum->save();
        
        $weddingUserObject->addMany($newAlbum, 'GalleryAlbum');
        if ($weddingUserObject->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }
    
    /* Update Album function
     *
     * @access public
     * 
     * @param $id the album id
     * @param $albumArray the event details
     * @param $errorstring a returned error string.
     * @return boolean
     */

    function updateAlbum($id, $albumArray, &$errorstring) {


        /* Update the album attributes */
        $albumUser = $this->modx->getObject('weddingUserGalleryAlbum', $id);
        if ($albumUser == null)
            return false;
        $albumUser->fromArray($albumArray);
        if ($albumUser->save() == false) {

            $errorstring = $this->modx->lexicon('user_err_save');
            return false;
        }

        return true;
    }

       

}

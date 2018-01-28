<?php

/**
 * Wedding site Clear User data plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */
/* Clear the user data now the user is being removed */
if ($reallyClear == 1) {

    /* Get the wedding user details we need */
    $userId = $user->get('id');
    if ($userId == "")
        return;
    $weddingUser = $modx->getObject('weddingUser', $userId);
    if (!$weddingUser)
        return;
    $weddingUser->attributes = $weddingUser->getOne('Attributes');
    $attributes = $weddingUser->attributes->toArray();
    $website = $attributes['website'];
    if ($website == "")
        return;

    /* Remove the users page set */
    $c = $modx->newQuery('modResource');
    $c->where(array('alias:=' => $website));
    $userContainer = $modx->getObject('modResource', $c);
    if (!$userContainer)
        return;
    $id = $userContainer->get('id');
    $modx->runProcessor('resource/delete', array('id' => $id));

    /* Remove the users subdomain mapping */
    $modx->runSnippet('cPanelDeleteSubDomain', array('subDomainName' => $website));

    /* Remove the users directories */
    $modx->runSnippet('removeUserDirectories', array('archive' => false, 'website' => $website));

    /* Remove the users RSVP messages */
    $c = $modx->newQuery('modUserMessage');
    $c->where(array(
        'recipient' => $userId
    ));
    $messages = $modx->getCollection('modUserMessage', $c);
    foreach ($messages as $message)
        $message->remove();

    /* Remove the users guestbook signings */

    /* Include Quip */
    $corePath = $modx->getOption('quip.core_path', null, $modx->getOption('core_path') . 'components/quip/');
    $modx->addPackage('quip', $corePath . 'model/');
    
    /* Remove the users  thread */
    $userName = $user->get('username');
    $thread = $modx->getObject('quipThread', $userName);
    if ( $thread ) $thread->remove();
    
}

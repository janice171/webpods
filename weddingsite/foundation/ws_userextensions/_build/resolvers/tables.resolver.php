<?php
/**
 * 
 * Wedding site User Extensions(ws_userextensions)
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 *
 * ws_userextensions is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * ws_userextensions is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ws_userextensions; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package ws_userextensions
 */
/**
 * Creates the tables on install
 *
 * @package ws_userextensions
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('ws_userextensions.core_path',null,$modx->getOption('core_path').'components/ws_userextensions/').'model/';
            $modx->addPackage('ws_userextensions',$modelPath);

            $manager = $modx->getManager();
            $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
            $manager->createObjectContainer('weddingUserAttribute');
            $manager->createObjectContainer('weddingUserGuest');
            $manager->createObjectContainer('weddingUserEvent');
            $manager->createObjectContainer('guestEvents');
            $manager->createObjectContainer('weddingUserGalleryAlbum');
            $manager->createObjectContainer('weddingUserGalleryItem');
            $manager->createObjectContainer('albumItems');
            $manager->createObjectContainer('weddingUserGalleryTags');
            $manager->createObjectContainer('galleryItemTags');
            $manager->createObjectContainer('weddingUserPage');
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
            break;
    }
}
return true;

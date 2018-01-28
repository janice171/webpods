<?php
/**
 * Wedding site User Extensions(ws_userextensions)
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
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
 * @package ws_userextensions
 */
$xpdo_meta_map['weddingUser']= array (
  'package' => 'ws_userextensions',
  'table' => 'users',
  'composites' => 
  array (
    'Attributes' => 
    array (
      'class' => 'weddingUserAttribute',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'Guests' => 
    array (
      'class' => 'weddingUserGuest',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Events' => 
    array (
      'class' => 'weddingUserEvent',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Pages' => 
    array (
      'class' => 'weddingUserPage',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'GalleryAlbum' => 
    array (
      'class' => 'weddingUserGalleryAlbum',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'GalleryItem' => 
    array (
      'class' => 'weddingUserGalleryItem',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'GalleryTags' => 
    array (
      'class' => 'weddingUserGalleryTags',
      'local' => 'id',
      'foreign' => 'user',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

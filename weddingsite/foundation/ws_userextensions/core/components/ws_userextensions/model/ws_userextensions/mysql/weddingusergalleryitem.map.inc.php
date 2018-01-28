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
$xpdo_meta_map['weddingUserGalleryItem']= array (
  'package' => 'ws_userextensions',
  'table' => 'ws_usergalleryitem',
  'fields' => 
  array (
    'user' => 0,
    'itemFileName' => '',
    'itemDisplayName' => '',
    'itemDescription' => '',
    'itemPosition' => 1,
    'active' => 0,
    'useForAlbum' => 0,
  ),
  'fieldMeta' => 
  array (
    'user' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
    'itemFileName' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '512',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'itemDisplayName' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'itemDescription' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '512',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'itemPosition' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'default' => 0,
    ),
    'useForAlbum' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'weddingUser' => 
    array (
      'class' => 'weddingUser',
      'local' => 'user',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'albumItems' => 
    array (
      'class' => 'albumItems',
      'local' => 'id',
      'foreign' => 'itemId',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'galleryItemTags' => 
    array (
      'class' => 'galleryItemTags',
      'local' => 'id',
      'foreign' => 'itemId',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

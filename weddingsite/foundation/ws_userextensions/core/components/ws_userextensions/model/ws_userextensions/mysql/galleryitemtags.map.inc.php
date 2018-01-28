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
$xpdo_meta_map['galleryItemTags']= array (
  'package' => 'ws_userextensions',
  'table' => 'ws_galleryitemtag',
  'fields' => 
  array (
    'galleryId' => NULL,
    'tagId' => NULL,
  ),
  'fieldMeta' => 
  array (
    'galleryId' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'tagId' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'weddingUserGallery' => 
    array (
      'class' => 'weddingUserGallery',
      'local' => 'galleryId',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'weddingUserTags' => 
    array (
      'class' => 'weddingUserTags',
      'local' => 'tagId',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);

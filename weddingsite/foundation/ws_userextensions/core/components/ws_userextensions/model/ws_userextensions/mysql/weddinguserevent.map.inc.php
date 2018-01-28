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
$xpdo_meta_map['weddingUserEvent']= array (
  'package' => 'ws_userextensions',
  'table' => 'ws_userevent',
  'fields' => 
  array (
    'user' => 0,
    'name' => '',
    'date' => NULL,
    'location' => '',
    'address2' => '',
    'address3' => '',
    'address4' => '',
    'startTime' => '',
    'endTime' => '',
    'totalGuests' => 0,
    'maxGuests' => 0,
    'active' => 0,
    'notes' => '',
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
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'date' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'location' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '512',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'address2' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'default' => '',
    ),
    'address3' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'default' => '',
    ),
    'address4' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'default' => '',
    ),
    'startTime' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '16',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'endTime' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '16',
      'phptype' => 'string',
      'default' => '',
    ),
    'totalGuests' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'default' => 0,
    ),
    'maxGuests' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'default' => 0,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'default' => 0,
    ),
    'notes' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'default' => '',
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
    'guestEvents' => 
    array (
      'class' => 'guestEvents',
      'local' => 'id',
      'foreign' => 'eventId',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

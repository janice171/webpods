<?php
/**
 * Wedding site renameUserDirectory
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Renames the users directory 
 * 
 * Parameters :- 
 * 
 * from - old name
 * to - new name
 *  
*/

$assetsPath = $modx->getOption('assets_path');
$fromDir = $assetsPath . "users" .  DIRECTORY_SEPARATOR  .  $from;
$toDir = $assetsPath . "users" .  DIRECTORY_SEPARATOR  .  $to;
@rename($fromDir, $toDir);
​

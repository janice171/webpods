<?php
/**
 * Wedding site clearUserDirectories
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Removes or archives the users directory set
 * 
 * Parameters :-
 *
 * archive - if true the directory contents are archived rather than being removed 
 * website - the website(directory) name
 * 
 * 
 * Returns :-
 * 
 * None
 *
 * 
 */
/* Helper function */
if (!function_exists('recursiveDelete')) {

    function recursiveDelete($dir) {

        $files = scandir($dir);

        foreach ($files as $file) {

            if ($file == '.' OR $file == '..')
                continue;

            $file = "$dir/$file";
            if (is_dir($file)) {
                recursiveDelete($file);
                @rmdir($file);
            } else {
                @unlink($file);
            }
        }
        @rmdir($dir);
    }

}

/* Sanity check on the website name */
if ( $website == "" ) return;

/* Get the directory path */
$assetsPath = $modx->getOption('assets_path');
$userDirectory = $assetsPath . "users" . DIRECTORY_SEPARATOR . $website;
if ( !file_exists($userDirectory)) return;

/* Remove or archive */
if (!$archive)
    recursiveDelete($userDirectory);

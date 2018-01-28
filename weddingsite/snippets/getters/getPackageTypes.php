<?php
/**
 * Wedding site getPackageTypes
 *
 * Copyright 2012 by S. Hamblett <steve.hamblett@linux.com>
 *
 * 
 * Returns :- an array of available package types
 *
 */

$packageArray = explode(',', $packageTypes);
return json_encode($packageArray);


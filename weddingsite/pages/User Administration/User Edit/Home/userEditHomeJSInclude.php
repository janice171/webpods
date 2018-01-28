<?php

/**
 * Wedding site User Edit Home processing
 *
 * Copyright 2011 by S. Hamblett<steve.hamblett@linux.com>
 *
 */
/* Paths */
$assets_url = $modx->getOption('assets_url');

/* Scripts */
$websiteLinks = $modx->runSnippet('userAdminWebPageLinksCommonJS');
$modx->regClientStartupScript($websiteLinks, true);


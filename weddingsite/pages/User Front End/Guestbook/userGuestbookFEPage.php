<?php
/**
 * Wedding site User FE Guestbook Page  processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 * 
 */

//* Check for a submission, if not spam proof return */
if ( isset($_REQUEST['parent'])) {
    
    if ( $_REQUEST['userGuestbookFEnospam'] != 1 ) return "SPAM!";
 
}

/* Set the page placeholders */
  $modx->setPlaceholder('post-action', 'quip-post');


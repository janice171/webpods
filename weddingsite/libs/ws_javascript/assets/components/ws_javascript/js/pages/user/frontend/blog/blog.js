/**
 * Wedding site external javascript files (ws_javascript)
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * A library package to support the functionality of the
 * wedding sites project
 *
 *
 * @package ws_javascript
 */
WS = new Object();

/* Page processing start */
$(document).ready(function() {

    /* Pagination */
    $('#page_container').pajinate({
        items_per_page: 5,
        nav_label_first: ' << ',
        nav_label_last: ' >> ',
        nav_label_prev: ' < ',
        nav_label_next: ' > ' 
    });
});

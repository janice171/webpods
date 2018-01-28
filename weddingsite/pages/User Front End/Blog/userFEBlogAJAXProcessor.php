<?php

/**
 * Wedding site User Front End Blog processing
 *
 * Copyright 2012 by S. Hamblett<steve.hamblett@linux.com>
 *
 */

/* Allow requests from our users front end origin */
$origin = $_SERVER['HTTP_ORIGIN'];

/* Get the link and return the title and contents */
if (!isset($_REQUEST['blog'])) {

    $response['error'] = "No command specified";
    $output = json_encode($response);
    header("Access-Control-Allow-Origin: $origin");
    return $output;
}

$blogLink = $_REQUEST['blog'];

/* Get the alias part and the resource id */
$blogString = strstr($blogLink, 'blog/');
$blogArray = explode('/', $blogString);
$resourceStringArray = explode('-', $blogArray[1]);
$resourceArray = explode('.', end($resourceStringArray));
$blogId = $resourceArray[0]; 

/* Get the blog itself */
$blog = $modx->getObject('modResource', $blogId);
if ( !$blog ) {
    
    $response['error'] = "No blog for id $blogId";
    $output = json_encode($response);
    header("Access-Control-Allow-Origin: $origin");
    return $output;
}

/* Get and return the data */
$response = array();
$response['title'] = $blog->get('pagetitle');
$response['content'] = $blog->getContent();
$response['error'] = "none";
$output = json_encode($response);
header("Access-Control-Allow-Origin: $origin");
return $output;





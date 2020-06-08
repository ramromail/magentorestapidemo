<?php
/**
 * turn error log on/off
 */
// ini_set('html_errors', 0);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// ini_set('error_logging', 1);
// ini_set('error_reporting', 'E_ALL');
// ini_set('session.cookie_httponly', 1);


/*
 * define the api credentials and url
 */

if(!empty(getenv('RestAPIUSER')) && 
    !empty(getenv('RestAPIPASS')) && 
    !empty(getenv('RestAPIHOST'))) {
    $APIBaseUrl = getenv('RestAPIHOST');
    $APIUserName = getenv('RestAPIUSER');
    $APIUserPass = getenv('RestAPIPASS');
}
else {
    $APIBaseUrl = 'http://burgur_magento_1';
    $APIUserName = 'user';
    $APIUserPass = 'bitnami1';
}

/**
 * Set the values for Visible drop down list
 */
$productVisibIdValues = array(
    1 => "Not Visible Individually",
    2 => "Catalog",
    3 => "Search",
    4 => "Catalog, Search",
);

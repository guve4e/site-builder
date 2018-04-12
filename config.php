<?php
/*
 * Configuration file
 */

$config = [
    "debug" => true,
    "auth" => [
        "session_key" => "some_website_user",
        "session_time" => 0
    ],
    "cookie_time" => 7,
    "services" => [
        "url-domain" => "http://localhost",
        "base" => "http://webapi.ddns.net/index.php",
        "base-local" => "http://localhost/crystalpure/mock-services"
    ],
    "production" => false,
    "bower_url"  => 'vendor'
];

/* define some constant paths (server dependent) */
defined("ROOT_PATH")
    or define("ROOT_PATH", realpath(dirname(__FILE__) ));
  
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("UTILITY_PATH")
    or define("UTILITY_PATH", realpath(dirname(__FILE__) . '/library/utility'));

defined("JSON_LOADER_PATH")
    or define("JSON_LOADER_PATH", realpath(dirname(__FILE__) . '/library/build-page/json'));

defined("MENU_PATH")
    or define("MENU_PATH", realpath(dirname(__FILE__) . '/template/menu'));

defined("NAVBAR_PATH")
    or define("NAVBAR_PATH", realpath(dirname(__FILE__) . '/template/navbar'));

defined("BUILD_PATH")
    or define("BUILD_PATH", realpath(dirname(__FILE__) . '/library/build-page'));

defined("USER_SESSION_PATH")
    or define("USER_SESSION_PATH", realpath(dirname(__FILE__) . '/library/user-session'));

defined("HTTP_PATH")
    or define("HTTP_PATH", realpath(dirname(__FILE__) . '/library/http'));

defined("TEMPLATE_PATH")
    or define("TEMPLATE_PATH", realpath(dirname(__FILE__) . '/template'));

defined("VIEW_PATH")
    or define("VIEW_PATH", realpath(dirname(__FILE__) . '/template/views'));

defined("LOG_PATH")
    or define("LOG_PATH", realpath(dirname(__FILE__) . '/logs'));


/**
* Configure php.ini
* Sets the value of the given configuration option. 
* The configuration option will keep this new value 
* during the script's execution, and will be restored 
* at the script's ending.
* @param value to be set
* @param new value for the option
* @return old value on success, FALSE on failure.s
*/
if(!ini_set("error_reporting", "true")){
    die("PHP not installed!");
}
error_reporting(E_ALL);

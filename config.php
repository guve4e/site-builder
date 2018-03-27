<?php
/*
 * Configuration file
 */


/* define some constant paths (server dependent) */
defined("ROOT_PATH")
    or define("ROOT_PATH", realpath(dirname(__FILE__) ));
  
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("MENU_PATH")
    or define("MENU_PATH", realpath(dirname(__FILE__) . '/template/menu'));

defined("NAVBAR_PATH")
    or define("NAVBAR_PATH", realpath(dirname(__FILE__) . '/template/navbar'));

defined("BUILD_PATH")
    or define("BUILD_PATH", realpath(dirname(__FILE__) . '/library/build_page'));

defined("USER_SESSION_PATH")
    or define("USER_SESSION_PATH", realpath(dirname(__FILE__) . '/library/user_session'));

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
* @return old value on success, FALSE on failure.
*/
if(!ini_set("error_reporting", "true")){
    Logger::logMsg("EXCEPTION","Couldn't open php.ini!");
}
error_reporting(E_ALL);

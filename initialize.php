<?php

// (\ for windows, / for Unix )
// defined('DB')?null:define('DB',DIRECTORY_SEPARATOR);

// defined('SITE_ROOT')?null:
// define('SITE_ROOT',DB.'xampp'.DB.'htdocs'.DB.'php-backend');

// defined('LIB_PATH')?null:
// define('LIB_PATH',SITE_ROOT.DB.'includes');

// load config file first 
require_once('./config.php');
// load basic function next so that everything after cab use them
// require_once('./functions.php');

// load core objects
// require_once(LIB_PATH.DB.'session.php');
require_once('./database.php');
require_once('./DatabaseObject.php');
// require_once('./user.php');
require_once('./product.php');
// require_once(LIB_PATH.DB.'pagination.php');
//load database related classess
// require_once(LIB_PATH.DB.'user.php');
// require_once(LIB_PATH.DB.'photograph.php');
// require_once(LIB_PATH.DB.'comment.php');

?>

<?php
/**
 * Created by PhpStorm.
 * User: Sébastien BAZAUD (alias Dacendi)
 * Date: 30/12/2015
 * Time: 15:57
 * Project: timelab
 */


define("URL_SEPARATOR", '/');
define("LIB_PATH", __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR);
define("ENTITIES_PATH", LIB_PATH . "Entities" . DIRECTORY_SEPARATOR );
define("FORM_MANAGER_PATH", ENTITIES_PATH . "FormManagers" . DIRECTORY_SEPARATOR);

define( "UTILS_CLASS", LIB_PATH . 'Utils' . DIRECTORY_SEPARATOR );

/**
 * Project global vars
 */

define( 'TIMELAB_VERSION', '0.0.20151226' );
define( 'TIMELAB_DB_VERSION', '0.1');
define( 'TIMELAB_MINIMUM_WP_VERSION', '3.2' );
define( 'TIMELAB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TIMELAB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TIMELAB_PLUGIN_WEB_JS_URL', TIMELAB_PLUGIN_URL . "web/js/" );
define( 'TIMELAB_DELETE_LIMIT', 100000 );
<?php
/**
 * Package: timelab
 * Creator: dacendi
 * Date: 26/12/15
 */

/**
 * @package fr\funlab\Timelab
 */

/*
Plugin Name: Timelab
Plugin URI: https://github.com/Dacendi/timelab/
Description: Wordpress Plugin for engines management in fablabs
Version: 0.0.20151226
Author: Dacendi
Author URI: https://www.dacendi.net
License: GPLv2 or later
Text Domain: Timelab
*/

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'TIMELAB_VERSION', '0.0.20151226' );
define( 'TIMELAB_DB_VERSION', '0.1');
define( 'TIMELAB_MINIMUM_WP_VERSION', '3.2' );
define( 'TIMELAB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TIMELAB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TIMELAB_DELETE_LIMIT', 100000 );

require_once( TIMELAB_PLUGIN_DIR . 'class-timelab.php' );

register_activation_hook( __FILE__, array( 'Timelab', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Timelab', 'plugin_deactivation' ) );

add_action( 'init', array( 'Timelab', 'init' ) );
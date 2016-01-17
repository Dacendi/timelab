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
Description: Wordpress Plugin for machines management in fablabs
Version: 0.0.20151226
Author: Dacendi
Author URI: https://www.dacendi.net
License: GPLv2 or later
Text Domain: timelab
Domain Path: /languages
*/

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

require_once("tml_global_vars.php");

require_once( TIMELAB_PLUGIN_DIR . 'class-timelab.php' );

register_activation_hook( __FILE__, array( 'Timelab', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Timelab', 'plugin_deactivation' ) );

add_action( 'init', array( 'Timelab', 'init' ) );

// register ajax Handlers
if (is_admin() === true )
{
    add_action('wp_ajax_machine_name_check', array("TimelabAdmin", 'tml_machine_name_handler'));
}
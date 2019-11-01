<?php
/*
Plugin Name: WP Random Post Inside
Plugin URI: https://anisbd.com/wp-random-post-inside-plugin-informations/
Description: This plugin will show random posts inside a post. It will help you to seo & reduce bounce rate.
Version: 1.6.3
Author: MD. Anisur Rahman Bhuyan
Author URI: https://anisbd.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-random-post-inside
Domain Path: /languages
*/

/* Exit if accessed directly */
defined( 'ABSPATH' ) || exit;

/* plugin informations */
define( 'WPRPI_VERSION', '1.6.3' );

/* basic setup files */
function wprpi_initial_setup() {
    $plugin_url = plugin_dir_url( __FILE__ );

    /* basic styles */
    wp_enqueue_style( 'wprpi_css', $plugin_url . '/css/style.css' );

    /* WordPress Default dashicons */
    wp_enqueue_style( 'dashicons' );
}
add_action( 'init', 'wprpi_initial_setup' );

/* load plugin text domain */
function wprpi_load_textdomain() {
    load_plugin_textdomain( 'wp-random-post-inside', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wprpi_load_textdomain' );

/* include required files */
require( dirname(__FILE__)."/wprpi_functions.php" );
require( dirname(__FILE__)."/wprpi_settings.php" );
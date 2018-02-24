<?php
/*
Plugin Name: Servebolt Optimizer
Version: 1.3.4
Author: Servebolt
Author URI: https://servebolt.com
Description: A plugin that checks and implements Servebolt Performance best practises for WordPress.
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: servebolt-wp
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

register_activation_hook(__FILE__, 'servebolt_transient_cron');

require_once 'admin/optimize-db/transients-cleaner.php';

define( 'SERVEBOLT_PATH', plugin_dir_url( __FILE__ ) );

// Disable CONCATENATE_SCRIPTS to get rid of some ddos attacks
if(! defined( 'CONCATENATE_SCRIPTS')) {
	define( 'CONCATENATE_SCRIPTS', false);
}

// hide the meta tag generator from head and rss
function servebolt_optimizer_disable_version() {
	return '';
}
add_filter('the_generator','servebolt_optimizer_disable_version');
remove_action('wp_head', 'wp_generator');

$nginx_switch = get_option('servebolt_fpc_switch');

/**
 * Loads the class that sets the correct cache headers for NGINX cache
 */
if(!class_exists('Servebolt_Nginx_Fpc') && $nginx_switch === 'on'){
	require_once 'class/servebolt-nginx-fpc.class.php';
	Servebolt_Nginx_Fpc::setup();
}

/**
 * If the admin is loaded, load this plugins interface
 */
if(is_admin()){
	require_once 'admin/admin-interface.php';
}

/**
 * We need weekly cron scheduling, so we're adding it!
 * See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
 */
add_filter( 'cron_schedules', 'servebolt_add_weekly_cron_schedule' );
function servebolt_add_weekly_cron_schedule( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => 604800, // 1 week in seconds
		'display'  => __( 'Once Weekly' ),
	);

	return $schedules;
}

/**
 * Run Servebolt Optimizer.
 *
 * Add database indexes and convert database tables to modern table types.
 *
 * ## EXAMPLES
 *
 *     $ wp servebolt optimize
 *     Success: Successfully optimized.
 */
$servebolt_optimize_cmd = function( $args ) {
	list( $key ) = $args;

	require_once 'admin/optimize-db/optimize-db.php';

	if ( ! servebolt_optimize_db(TRUE) ) {
		WP_CLI::error( "Optimization failed." );
	} else {
		WP_CLI::success( "Everything OK." );
	}
};

$servebolt_delete_transients = function( $args ) {
	list( $key ) = $args;

	require_once 'admin/optimize-db/transients-cleaner.php';

	if ( ! servebolt_optimize_db(TRUE) ) {
		WP_CLI::error( "Could not delete transients." );
	} else {
		WP_CLI::success( "Deleted transients." );
	}
};

if ( class_exists( 'WP_CLI' ) ) {
	WP_CLI::add_command( 'servebolt optimize db', $servebolt_optimize_cmd );
	WP_CLI::add_command( 'servebolt optimize transients', $servebolt_delete_transients );
}



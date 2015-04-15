<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 *
 * @link              http://masterdigm.com
 * @since             1.0.0
 * @package           Masterdigm_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Masterdigm API
 * Plugin URI:        https://wordpress.org/plugins/wp-real-estate-property-listing-crm/
 * Description:       This plugin use to fetch data from masterdigm crm, thru API fetch CRM property, or MLS property. Also this plugin save leads
 * Version:           1.0.0
 * Author:            Masterdigm
 * Author URI:        http://masterdigm.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       masterdigm-api
 * Domain Path:       /languages
 * Bitbucket Plugin URI: https://bitbucket.org/allan_paul_casilum/masterdigm
 * Bitbucket Branch:     master
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('MASTERDIGM_API',true);
/* Masterdigm */
/**
 * load the config variables
 * */
require_once( plugin_dir_path( __FILE__ ) . 'config.php' );

/* Masterdigm */
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-masterdigm-api-activator.php';
	Masterdigm_API_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-masterdigm-api-deactivator.php';
	Masterdigm_API_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );
/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-masterdigm-api.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_masterdigm() {

	$plugin = new Masterdigm_API();
	$plugin->run();
}
run_masterdigm();

function has_mls_key(){
	if( get_option('mls_api_key') && get_option('mls_api_token') ){
		return true;
	}
	return false;
}
function has_crm_key(){
	if( get_option('api_key') && get_option('api_token') ){
		return true;
	}
	return false;
}

// include core classes
require_once( plugin_dir_path( __FILE__ ) . 'include-core-class.php' );
// Admin / Dashboard
require_once( plugin_dir_path( __FILE__ ) . 'init-admin-component.php' );
// function for easy access
require_once( plugin_dir_path( __FILE__ ) . 'inc-functions.php' );
// components
require_once( plugin_dir_path( __FILE__ ) . 'init-component.php' );
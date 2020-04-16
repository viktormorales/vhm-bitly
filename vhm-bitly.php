<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://viktormorales.com
 * @since             1.0.0
 * @package           Vhm_Bitly
 *
 * @wordpress-plugin
 * Plugin Name:       VHM Bitly
 * Plugin URI:        https://viktormorales.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Viktor H. Morales
 * Author URI:        https://viktormorales.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vhm-bitly
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VHM_BITLY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vhm-bitly-activator.php
 */
function activate_vhm_bitly() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vhm-bitly-activator.php';
	Vhm_Bitly_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vhm-bitly-deactivator.php
 */
function deactivate_vhm_bitly() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vhm-bitly-deactivator.php';
	Vhm_Bitly_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vhm_bitly' );
register_deactivation_hook( __FILE__, 'deactivate_vhm_bitly' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vhm-bitly.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vhm_bitly() {

	$plugin = new Vhm_Bitly();
	$plugin->run();

}
run_vhm_bitly();

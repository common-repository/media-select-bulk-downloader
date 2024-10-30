<?php
/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.webwave.ch
 * @since             1.0.0
 * @package           Wp_Msbd
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Media Select Bulk Downloader
 * Plugin URI:        https://www.webwave.ch/wp-msbd
 * Description:       This great plugin allows you to select media files from wordpress and to bulk download it with one click!
 * Version:           1.0.0
 * Author:            webwave GmbH
 * Author URI:        https://www.webwave.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-msbd
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-msbd-activator.php
 */
function activate_wp_msbd() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-msbd-activator.php';
	Wp_Msbd_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-msbd-deactivator.php
 */
function deactivate_wp_msbd() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-msbd-deactivator.php';
	Wp_Msbd_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_msbd' );
register_deactivation_hook( __FILE__, 'deactivate_wp_msbd' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-msbd.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_msbd() {

	$plugin = new Wp_Msbd();
	$plugin->run();

}
run_wp_msbd();

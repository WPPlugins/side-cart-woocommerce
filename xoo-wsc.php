<?php

/**
 * The plugin bootstrap file
 *
 * @since             1.0.0
 * @package    Side Cart WooCommerce
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Side Cart
 * Plugin URI:        http://xotix.com
 * Description:       Woo Side Cart shows all the items added to cart in a side popup.The plugin is ajax based.
 * Version:           1.0.0
 * Author:            XootiX
 * Author URI:        http://xootix.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xoo-wsc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xoo-sc-activator.php
 */
function activate_xoo_wsc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xoo-wsc-activator.php';
	xoo_wsc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_xoo_wsc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xoo-wsc-deactivator.php';
	xoo_wsc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_xoo_wsc' );
register_deactivation_hook( __FILE__, 'deactivate_xoo_wsc' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_xoo_wsc() {

	$plugin = new xoo_wsc();
	$plugin->run();

}

/**
 * Check if WooCommerce is activated
 *
 * @since    1.0.0
 */
function xoo_wsc_init(){
	if ( function_exists( 'WC' ) ) {

		/*The core plugin class that is used to define internationalization,
		admin-specific hooks, and public-facing site hooks.*/

		require plugin_dir_path( __FILE__ ) . 'includes/class-xoo-wsc.php';
		run_xoo_wsc();
	}
	else{
		add_action( 'admin_notices', 'xoo_wsc_install_wc_notice' );
	}
}
add_action('plugins_loaded','xoo_wsc_init');


/**
 * WooCommerce not activated admin notice
 *
 * @since    1.0.0
 */
function xoo_wsc_install_wc_notice(){
	?>
	<div class="error">
		<p><?php _e( 'Side Cart WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', XOO_WSC_DOMAIN ); ?></p>
	</div>
	<?php
}

//Domain name
if ( ! defined( 'XOO_WSC_DOMAIN' ) ){
	define( 'XOO_WSC_DOMAIN', 'xoo-wsc' );
}

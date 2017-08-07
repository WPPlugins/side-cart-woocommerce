<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Side Cart WooCommerce
 */
class xoo_wsc_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), XOO_WSC_DOMAIN );
		load_textdomain( XOO_WSC_DOMAIN, trailingslashit( WP_LANG_DIR ) .'/'.XOO_WSC_DOMAIN . '-'. $locale . '.mo' );
	}
}

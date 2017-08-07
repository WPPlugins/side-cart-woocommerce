<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Side Cart WooCommerce
 */
class xoo_wsc_Deactivator {

	/**
	 * Reset option "Enable ajax add to cart" option to default user choice
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$user_atc = get_option('xoo_wsc_ajax_atc_default');
		update_option('woocommerce_enable_ajax_add_to_cart',$user_atc);
	}

}

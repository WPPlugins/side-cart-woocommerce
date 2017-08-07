<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce Side Cart
 */
class xoo_wsc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $xoo_wsc    The ID of this plugin.
	 */
	private $xoo_wsc;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $plugin_settings_tabs = array();
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $xoo_wsc       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $xoo_wsc, $version ) {

		$this->xoo_wsc = $xoo_wsc;
		$this->version = $version;

		$this->plugin_settings_tabs['general'] 	= 'General';
		$this->plugin_settings_tabs['style'] 	= 'Style';
		$this->plugin_settings_tabs['advanced'] = 'Advanced';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		//Enqueue Styles only on plugin settings page
		if($hook != 'toplevel_page_xoo-wsc'){
			return;
		}
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( $this->xoo_wsc, plugin_dir_url( __FILE__ ) . 'css/xoo-wsc-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		//Enqueue Script only on plugin settings page
		if($hook != 'toplevel_page_xoo-wsc'){
			return;
		}
		wp_enqueue_script( $this->xoo_wsc, plugin_dir_url( __FILE__ ) . 'js/xoo-wsc-admin.js', array( 'jquery','wp-color-picker'), $this->version, false );

	}

	/**
	 * Register the Settings page.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {

		 add_menu_page( __('Woo Side Cart', $this->xoo_wsc), __('Woo Side Cart', $this->xoo_wsc), 'manage_options', $this->xoo_wsc, array($this, 'display_plugin_admin_page'),'dashicons-cart', 61);

	}

	/**
	 * Settings - Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function settings_sanitize( $input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		if(isset($input)) {
			// Loop through the input and sanitize each of the values
			foreach ( $input as $key => $val ) {

				$new_input[ $key ] = sanitize_text_field( $val );
				
			}

		}

		return $new_input;

	} // sanitize()


	/**
	 * Renders Settings Tabs
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function render_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->xoo_wsc . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}

	/**
	 * Plugin Settings Link on plugin page
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function add_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=wsc' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}


	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page(){	

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/xoo-wsc-admin-display.php';
	}

	/**
	 * Section function markup
	 *
	 * @since    1.0.0
	 */
	public function get_section_markup($title){	
		echo '<span class="section-title">'.$title.'</span>';
		
	}


}

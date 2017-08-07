<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    WooCommerce Side Cart
 */
class xoo_wsc_Advanced_Settings extends xoo_wsc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $xoo_wsc    The ID of this plugin.
	 */
	private $xoo_wsc;

	/**
	 * The ID of Advanced Settings.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $group    The ID of Advanced Settings.
	 */
	private $group;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $xoo_wsc     The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $xoo_wsc ) {

		$this->xoo_wsc = $xoo_wsc;
		$this->group = $xoo_wsc.'-av';
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){
		
		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->group . '-options',
			$this->group . '-options',
			array( $this, 'settings_sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->group . '-options', // section
			'',
			array( $this, 'options_section' ),
			$this->group // Advanced Section
		);


		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'custom-css',
			 __( 'Custom CSS',XOO_WSC_DOMAIN ),
			array( $this, 'custom_css' ),
			$this->group,
			$this->group . '-options' // Custom CSS
		);

		add_settings_field(
			'shop-btn-class',
			 __( 'Shop Button Class',XOO_WSC_DOMAIN ),
			array( $this, 'shop_btn_class' ),
			$this->group,
			$this->group . '-options' // Shop Button Class
		);

	}

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @return 		mixed 						The settings section
	 */
	public function options_section() {} 

	/**
	 * Custom CSS
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function custom_css() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['custom-css']) ? $options['custom-css'] : '';

		?>
		<textarea name="<?php echo $this->group; ?>-options[custom-css]" cols="80" rows="20" placeholder="/* Place your custom CSS here */"><?php echo $option; ?></textarea>
		<?php
	}


	/**
	 * Shop Button Class
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function shop_btn_class() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['shop-btn-class']) ? $options['shop-btn-class'] : '';

		?>
		<input type="text" name="<?php echo $this->group; ?>-options[shop-btn-class]" value="<?php echo $option; ?> ">
		<?php
	}
}
<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    xoo_wsc
 * @subpackage xoo_wsc/admin
 */
class xoo_wsc_General_Settings extends xoo_wsc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $xoo_wsc    The ID of this plugin.
	 */
	private $xoo_wsc;

	/**
	 * The ID of General Settings.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $group    The ID of General Settings.
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
		$this->group = $xoo_wsc.'_gl';
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){
		
		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->group . '_options',
			$this->group . '_options',
			array( $this, 'settings_sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->group . '-sc-options', // section
			'',
			array( $this, 'sc_options_section' ),
			$this->group
		);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'auto-open-sc',
			 __( 'Auto Open', $this->xoo_wsc ),
			array( $this, 'auto_open_sc' ),
			$this->group,
			$this->group . '-sc-options' // section to add to
		);

		add_settings_field(
			'head-txt-sc',
			 __( 'Head Title', $this->xoo_wsc ),
			array( $this, 'head_txt_sc' ),
			$this->group,
			$this->group . '-sc-options' // Cart Text
		);


	}

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function sc_options_section() {
		$this->get_section_markup('Side Cart');

	} // display_options_section()


	/**
	 * Enable Bar Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function auto_open_sc() {
		$options 	= get_option( $this->group . '_options' );
		$option 	= 0;

		if ( ! empty( $options['auto-open-sc'] ) ) {

			$option = $options['auto-open-sc'];

		}

		?><input type="checkbox" id="<?php echo $this->group; ?>_options[auto-open-sc]" name="<?php echo $this->group; ?>_options[auto-open-sc]" value="1" <?php checked( $option, 1 , true ); ?> />
		<p class="description">Disabling bar is also disabling front end loading of scripts css/js.</p> <?php
	} 


	/**
	 * Head Title
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function head_txt_sc() {
		$options 	= get_option( $this->group . '_options' );
		$option 	= 0;

		if ( ! empty( $options['head-txt-sc'] ) ) {

			$option = $options['head-txt-sc'];

		}

		?><input type="checkbox" id="<?php echo $this->group; ?>_options[head-txt-sc]" name="<?php echo $this->group; ?>_options[head-txt-sc]" value="1" <?php checked( $option, 1 , true ); ?> />
		<p class="description">Disabling bar is also disabling front end loading of scripts css/js.</p> <?php
	}
}
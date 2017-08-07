<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    WooCommerce Side Cart
 */
class xoo_wsc_Style_Settings extends xoo_wsc_Admin {

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
		$this->group = $xoo_wsc.'-sy';
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
			$this->group . '-sc-options', // section
			'',
			array( $this, 'sc_options_section' ),
			$this->group // Side Cart - Head Section
		);


		add_settings_section(
			$this->group . '-bk-options', // section
			'',
			array( $this, 'bk_options_section' ),
			$this->group // Cart Basket Section
		);

		/*
		 =============================================
		 ============= Side Cart - Head ==============
		 =============================================
		*/

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'sch-fs',
			 __( 'Head Font Size', XOO_WSC_DOMAIN ),
			array( $this, 'sch_fs' ),
			$this->group,
			$this->group . '-sc-options' // Font Size
		);

		/*
		 =============================================
		 ============= Side Cart - Body ==============
		 =============================================
		*/

		 add_settings_field(
			'scb-cw',
			 __( 'Container Width', XOO_WSC_DOMAIN ),
			array( $this, 'scb_cw' ),
			$this->group,
			$this->group . '-sc-options' // Container Width 
		);

		add_settings_field(
			'scb-fs',
			 __( 'Body Font Size', XOO_WSC_DOMAIN ),
			array( $this, 'scb_fs' ),
			$this->group,
			$this->group . '-sc-options' // Font Size
		);

		add_settings_field(
			'scb-imgw',
			 __( 'Product Image Width', XOO_WSC_DOMAIN ),
			array( $this, 'scb_imgw' ),
			$this->group,
			$this->group . '-sc-options' // Image Width
		);


		/*
		 =============================================
		 ============ Side Cart - Footer =============
		 =============================================
		*/

		add_settings_field(
			'scf-bm',
			 __( 'Footer Buttons Margin', XOO_WSC_DOMAIN ),
			array( $this, 'scf_bm' ),
			$this->group,
			$this->group . '-sc-options' // Button Margin 
		);


		/*
		 =============================================
		 =============== Cart Basket =================
		 =============================================
		*/

		add_settings_field(
			'bk-pos',
			 __( 'Basket Position', XOO_WSC_DOMAIN ),
			array( $this, 'bk_pos' ),
			$this->group,
			$this->group . '-bk-options' // Basket Position
		);

		add_settings_field(
			'bk-bbgc',
			 __( 'Basket Background Color', XOO_WSC_DOMAIN ),
			array( $this, 'bk_bbgc' ),
			$this->group,
			$this->group . '-bk-options' // Basket Bg Color
		);

		add_settings_field(
			'bk-bfc',
			 __( 'Basket Icon Color', XOO_WSC_DOMAIN ),
			array( $this, 'bk_bfc' ),
			$this->group,
			$this->group . '-bk-options' // Basket Icon Color
		);

		add_settings_field(
			'bk-bfs',
			 __( 'Basket Icon Size', XOO_WSC_DOMAIN ),
			array( $this, 'bk_bfs' ),
			$this->group,
			$this->group . '-bk-options' // Basket Font Size
		);

		add_settings_field(
			'bk-cbgc',
			 __( 'Count Background Color', XOO_WSC_DOMAIN ),
			array( $this, 'bk_cbgc' ),
			$this->group,
			$this->group . '-bk-options' // Count background Color
		);

		add_settings_field(
			'bk-cfc',
			 __( 'Count Text Color', XOO_WSC_DOMAIN ),
			array( $this, 'bk_cfc' ),
			$this->group,
			$this->group . '-bk-options' // Count Text Color
		);

	}

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @return 		mixed 						The settings section
	 */
	public function sc_options_section() {
		$this->get_section_markup('Side Cart');

	}

	/**
	 * Creates a basket section
	 *
	 * @since 		1.0.0
	 * @return 		mixed 						The settings section
	 */
	public function bk_options_section() {
		$this->get_section_markup('Cart Basket');
	} 


	/*
	 =============================================
	 ========= Side Cart - Head Section ==========
	 =============================================
	*/

	/**
	 * Head- font Size
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function sch_fs() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['sch-fs']) ? $options['sch-fs'] : 25;
		$id 		= $this->group.'-options[sch-fs]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Size in px (Default: 25)</span>
		<?php
	}


	/**
	 * Head- Close Cart Icon Size
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function sch_cis() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['sch-cis']) ? $options['sch-cis'] : 20;
		$id 		= $this->group.'-options[sch-cis]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Size in px (Default: 20)</span>
		<?php
	}


	/*
	 =============================================
	 ========= Side Cart - Body Section ==========
	 =============================================
	*/

	 /**
	 * Container Width
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function scb_cw() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= !empty( $options['scb-cw']) ? $options['scb-cw'] : 300;
		$id 		= $this->group.'-options[scb-cw]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Size in px (Default: 300)</span>
		<?php
	}


	/**
	 * Body- font Size
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function scb_fs() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['scb-fs']) ? $options['scb-fs'] : 14;
		$id 		= $this->group.'-options[scb-fs]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Size in px (Default: 25)</span>
		<?php
	}


	/**
	 * Body- Product Images Width 
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function scb_imgw() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['scb-imgw']) ? $options['scb-imgw'] : 35;
		$id 		= $this->group.'-options[scb-imgw]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Width in percentage. (Default: 35)</span>
		<?php
	}

	/*
	 =============================================
	 ========= Side Cart - Footer Section ==========
	 =============================================
	*/


	/**
	 * Footer- Buttons Margin
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	*/
	public function scf_bm() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['scf-bm']) ? $options['scf-bm'] : 4;
		$id 		= $this->group.'-options[scf-bm]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Size in px (Default: 4)</span>
		<?php
	}

	/*
	 =============================================
	 ============= Cart Basket - Section =========
	 =============================================
	*/

	/**
	 * Basket Position
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function bk_pos() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['bk-pos']) ? $options['bk-pos'] : 'bottom';
		$id 		= $this->group.'-options[bk-pos]';
		?>
		<select name="<?php echo $id; ?>">
			<option value="top" <?php selected($option,'top'); ?>>Top</option>
			<option value="bottom" <?php selected($option,'bottom'); ?>>Bottom</option>
		<?php
	}


	/**
	 * Basket Background Color
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function bk_bbgc() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['bk-bbgc']) ? $options['bk-bbgc'] : '#ffffff';
		$id 		= $this->group.'-options[bk-bbgc]';
		?>
		<input type="text" class="color-field" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<?php
	}


	/**
	 * Basket Icon Color
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function bk_bfc() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['bk-bfc']) ? $options['bk-bfc'] : '#000000';
		$id 		= $this->group.'-options[bk-bfc]';
		?>
		<input type="text" class="color-field" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<?php
	}

	/**
	 * Basket Icon Size
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function bk_bfs() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['bk-bfs']) ? $options['bk-bfs'] : 35;
		$id 		= $this->group.'-options[bk-bfs]';
		?>
		<input type="number" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<span class="description">Size in px (Default: 35)</span>
		<?php
	}

	/**
	 * Count BG Color
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function bk_cbgc() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['bk-cbgc']) ? $options['bk-cbgc'] : '#cc0086';
		$id 		= $this->group.'-options[bk-cbgc]';
		?>
		<input type="text" class="color-field" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<?php
	}


	/**
	 * Count Text Color
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function bk_cfc() {

		$options 	= get_option( $this->group . '-options' );
		$option 	= isset( $options['bk-cfc']) ? $options['bk-cfc'] : '#ffffff';
		$id 		= $this->group.'-options[bk-cfc]';
		?>
		<input type="text" class="color-field" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $option; ?>" />
		<?php
	}

}
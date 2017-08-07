<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 *
 * @package    Side Cart WooCommerce
 */

class xoo_wsc_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $xoo_wsc    The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $xoo_wsc, $version ) {

		$this->xoo_wsc = $xoo_wsc;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->xoo_wsc, plugin_dir_url( __FILE__ ) . 'css/xoo-wsc-public.css', array(), $this->version, 'all' );

		$options = get_option('xoo-wsc-sy-options');

		/*
		* User Style Options
		*/

		//Head
		$sch_fs 	= isset( $options['sch-fs']) ? $options['sch-fs'] : 25; // Font Size

		//Body
		$scb_cw		= !empty( $options['scb-cw']) ? $options['scb-cw'] : 300; // Container Width
		$scb_fs 	= isset( $options['scb-fs']) ? $options['scb-fs'] : 14; // Font Size
		$scb_imgw 	= isset( $options['scb-imgw']) ? $options['scb-imgw'] : 35; // Product Images width
		$scb_sumw   = 100-($scb_imgw+5);

		//Footer
		$scf_bm 	= isset( $options['scf-bm']) ? $options['scf-bm'] : 4; // buttons margin

		//Basket
		$bk_pos 	= isset( $options['bk-pos']) ? $options['bk-pos'] : 'bottom'; // Basket Position
		$bk_bbgc 	= isset( $options['bk-bbgc']) ? $options['bk-bbgc'] : '#ffffff'; // Basket Background Color
		$bk_bfc 	= isset( $options['bk-bfc']) ? $options['bk-bfc'] : '#000000'; // basket Icon Color
		$bk_bfs 	= isset( $options['bk-bfs']) ? $options['bk-bfs'] : 35; // Basket Icon size
		$bk_cbgc 	= isset( $options['bk-cbgc']) ? $options['bk-cbgc'] : '#cc0086'; // Count background Color
		$bk_cfc 	= isset( $options['bk-cfc']) ? $options['bk-cfc'] : '#ffffff'; // Count font color

		$inline_style = "
			.xoo-wsc-ctxt{
				font-size: {$sch_fs}px;
			}

			.xoo-wsc-container{
				right: -{$scb_cw}px;
				width: {$scb_cw}px;
			}
			.xoo-wsc-body{
				font-size: {$scb_fs}px;
			}
			.xoo-wsc-img-col{
				width: {$scb_imgw}%;
			}
			.xoo-wsc-sum-col{
				width: {$scb_sumw}%;
			}
			.xoo-wsc-basket{
				right: {$scb_cw}px;
				background-color: {$bk_bbgc};
				{$bk_pos}: 0;
			}
			.xoo-wsc-bki{
				color: {$bk_bfc};
				font-size: {$bk_bfs}px;
			}
			.xoo-wsc-items-count{
				background-color: {$bk_cbgc};
				color: {$bk_cfc};
			}
			.xoo-wsc-footer a.button{
				margin: {$scf_bm}px 0;
			}
		";
		
		wp_add_inline_style($this->xoo_wsc,$inline_style);

		//Custom CSS from user settings
		$av_options = get_option('xoo-wsc-av-options');
		if(isset($av_options['custom-css']) && !empty($av_options['custom-css'])){
			wp_add_inline_style($this->xoo_wsc,$av_options['custom-css']);
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//User Options
		$gl_options = get_option('xoo-wsc-gl-options');
		$av_options = get_option('xoo-wsc-av-options');

		$ajax_atc = isset( $gl_options['sc-ajax-atc']) ? $gl_options['sc-ajax-atc'] : 1;

		//Check if item added to cart
		if($ajax_atc != 1 && isset($_POST['add-to-cart'])){
			$added_to_cart = true;
		}
		else{
			$added_to_cart = false;
		}

		$shop_btn_class = isset( $av_options['shop-btn-class']) ? $av_options['shop-btn-class'] : '';

		wp_enqueue_script( $this->xoo_wsc, plugin_dir_url( __FILE__ ) . 'js/xoo-wsc-public.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->xoo_wsc,'localize',array(
			'adminurl'		=> admin_url().'admin-ajax.php',
			'shop_btn'		=> $shop_btn_class,
			'ajax_atc'		=> $ajax_atc,
			'added_to_cart' => $added_to_cart
			)
		);
		wp_dequeue_script('wc-add-to-cart');
	}

	

}

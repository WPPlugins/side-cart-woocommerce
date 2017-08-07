<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 */
class xoo_wsc {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $xoo_wsc    The string used to uniquely identify this plugin.
	 */
	protected $xoo_wsc;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->xoo_wsc = 'xoo-wsc';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - xoo_wsc_Loader. Orchestrates the hooks of the plugin.
	 * - xoo_wsc_i18n. Defines internationalization functionality.
	 * - xoo_wsc_Admin. Defines all hooks for the admin area.
	 * - xoo_wsc_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xoo-wsc-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xoo-wsc-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-xoo-wsc-admin.php';

		/**
		 * The class responsible for defining all General Settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-xoo-wsc-general-settings.php';


		/**
		 * The class responsible for defining all Style Settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-xoo-wsc-style-settings.php';

		/**
		 * The class responsible for defining all Advanced Settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-xoo-wsc-advanced-settings.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-xoo-wsc-public.php';

		/**
		 * The class responsible for showing side cart data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-xoo-wsc-cart-data.php';

		$this->loader = new xoo_wsc_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the xoo_wsc_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new xoo_wsc_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new xoo_wsc_Admin( $this->get_xoo_wsc(), $this->get_version() );
		$settings_init_general = new xoo_wsc_General_Settings( $this->get_xoo_wsc() ); // Initialize General Settings
		$settings_init_style = new xoo_wsc_Style_Settings( $this->get_xoo_wsc() ); // Initialize Style Settings
		$settings_init_advanced = new xoo_wsc_Advanced_Settings( $this->get_xoo_wsc() ); // Initialize Advanced Settings


		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_init', $settings_init_general, 'settings_api_init' );
		$this->loader->add_action( 'admin_init', $settings_init_style, 'settings_api_init' );
		$this->loader->add_action( 'admin_init', $settings_init_advanced, 'settings_api_init' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new xoo_wsc_Public( $this->get_xoo_wsc(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		if(!is_admin() || wp_doing_ajax()){
			$get_cart	   = new xoo_wsc_Cart_Data($this->get_xoo_wsc());
			$this->loader->add_action('wp_head',$get_cart,'get_cart_markup');
			$this->loader->add_action('xoo_wsc_cart_content',$get_cart,'get_cart_content');
			$this->loader->add_action('wp_ajax_add_to_cart',$get_cart,'add_to_cart');
			$this->loader->add_action('wp_ajax_nopriv_add_to_cart',$get_cart,'add_to_cart');
			$this->loader->add_action('wp_ajax_update_cart',$get_cart,'update_cart');
			$this->loader->add_action('wp_ajax_nopriv_update_cart',$get_cart,'update_cart');
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_xoo_wsc() {
		return $this->xoo_wsc;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    xoo_wsc_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

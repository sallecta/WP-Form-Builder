<?php
/**
 * Plugin Name: FrMk
 * Plugin URI: https://github.com/sallecta/frmk
 * Description: Experimental plugin to build forms. Create form in FrMk menu, then create a page and insert form's shortcode.
 * Version: 0.4
 * Author: Alexander Gribkov, James Collings
 * Author URI: https://github.com/sallecta/frmk
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Frmk_Plugin {

	protected			$version = '0.4';
	protected			$db_version = 3;
	public				$plugin_dir = false;
	public				$plugin_url = false;
	protected			$plugin_slug = false;
	protected			$settings = false;
	protected			$_forms = null;
	protected			$_form = null;
	private				$default_settings = null;
	public				$text = null;
	protected			$_modules = null;
	protected static	$_instance = null;
	
	public static function instance() 
	{
		if ( is_null( self::$_instance ) )
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct()
	{
		$this->plugin_dir = plugin_dir_path( __FILE__ );
		$this->plugin_url = plugins_url( '/', __FILE__ );
		$this->plugin_slug = basename( dirname( __FILE__ ) );
		$this->init();
		add_action( 'wp_loaded', array( $this, 'load_db_forms' ), 9 );
		add_action( 'wp_loaded', array( $this, 'process_form' ) );
		add_action( 'init', array( $this, 'load_modules' ) );
		register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
	
	public function includes() 
	{
		include_once 'libs/class-frmk-form.php';
		include_once 'libs/class-frmk-db-form.php';
		include_once 'libs/class-frmk-form-data.php';
		include_once 'libs/class-frmk-form-theme.php';
		include_once 'libs/class-frmk-text.php';
		include_once 'libs/class-frmk-form-field.php';
		include_once 'libs/fields/class-frmk-text-field.php';
		include_once 'libs/fields/class-frmk-textarea-field.php';
		include_once 'libs/fields/class-frmk-select-field.php';
		include_once 'libs/fields/class-frmk-radio-field.php';
		include_once 'libs/fields/class-frmk-checkbox-field.php';
		include_once 'libs/fields/class-frmk-file-field.php';
		include_once 'libs/fields/class-frmk-number-field.php';
		include_once 'libs/class-frmk-validation.php';
		include_once 'libs/class-frmk-notification.php';
		include_once 'libs/class-frmk-email-manager.php';
		include_once 'libs/class-frmk-database-manager.php';
		include_once 'libs/class-frmk-addon.php';
		include_once 'libs/frmk-functions.php';
		include_once 'libs/frmk-shortcodes.php';
		if ( is_admin() ) 
		{
			include_once 'libs/admin/frmk-functions.php';
			include_once 'libs/admin/frmk-admin.php';
		}
	} // end includes
	
	public function init() {

		$this->load_settings();
		$this->includes();

		// load text.
		$this->text = new FRMK_Text();

		// load preview.
		$preview = isset( $_GET['frmk_preview'] ) && !empty( $_GET['frmk_preview'] ) ? esc_attr($_GET['frmk_preview']) : false;
		if ( $preview ) 
		{
			include_once 'libs/class-frmk-preview.php';
			new FRMK_Preview( $preview );
		}
	} // end init
	
	public function load_modules()
	{
		if ( ! is_null( $this->_modules ) ) { return; }
		$this->_modules = array();
		$modules = apply_filters( 'frmk/list_modules', array() );
		foreach ( $modules as $module_id => $module )
		{
			// check if class exists.
			if ( ! class_exists( $module ) ) 
			{
				throw new Exception( 'FRMK Module could not be loaded: ' . $module );
			}
			$this->_modules[ $module_id ] = new $module;
		}
	} // end load_modules
	
	public function default_settings()
	{
		$this->default_settings = array();
	} // end default_settings
	
	public function load_settings()
	{
		$this->default_settings();
		$this->settings = array();
		// load settings from db.
		foreach ( $this->default_settings as $key => $default )
		{
			$data = get_option( 'frmk_' . $key, $default );
			$this->settings[ $key ] = is_serialized( $data ) ? unserialize( $data ) : $data;
		}
	} // end load_settings

	/**
	 * Get plugin setting
	 *
	 * @param string $key setting key.
	 * @param bool   $default load default value.
	 *
	 * @return bool|mixed
	 */
	public function get_settings( $key, $default = false ) {

		if ( $default ) {
			return isset( $this->default_settings[ $key ] ) ? $this->default_settings[ $key ] : false;
		}

		return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : false;
	}

	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get plugin slug
	 *
	 * @return bool|string
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Get plugin Url
	 *
	 * @return bool|string
	 */
	public function get_plugin_url() {
		return $this->plugin_url;
	}

	/**
	 * Get plugin path to directory
	 *
	 * @return bool|string
	 */
	public function get_plugin_dir() {
		return $this->plugin_dir;
	}

	/**
	 * Load plugin scripts and styles
	 */
	public function enqueue_scripts() {

		$ext = '.min';
		$version = $this->get_version();
		if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
			$version = time();
			$ext = '';
		}

		// todo: check if viewing form and that form has recaptcha enabled, then output scripts in head.
		wp_enqueue_script( 'frmk-recaptcha' , '//www.google.com/recaptcha/api.js' );

		wp_enqueue_script( 'frmk-main' , $this->get_plugin_url() . 'assets/public/js/main' . $ext . '.js', array( 'jquery', 'jquery-ui-slider' ), $version, true );
		wp_enqueue_style( 'frmk-main' , $this->get_plugin_url() . 'assets/public/css/main' . $ext . '.css', array(), $version );
	}

	/**
	 * On Plugin Activation
	 */
	public function on_activation() {

		$db = new FRMK_DatabaseManager();
		$db->install();
	}

	/**
	 * Register new form
	 *
	 * @param string $name Name of form.
	 * @param array  $args Form arguments.
	 *
	 * @return FRMK_Form
	 */
	public function register_form( $name, $args = array() ) {

		// register form with system.
		$form = new FRMK_Form( $name, $args );
		$this->_forms[ $name ] = $form;
		return $form;
	}

	/**
	 * Load form, and initialise it if required
	 *
	 * @param string $name Name of plugin.
	 *
	 * @return bool|FRMK_Form
	 */
	public function get_form( $name ) {

		if ( isset( $this->_forms[ $name ] ) ) {

			if ( is_array( $this->_forms[ $name ] ) ) {

				if ( isset( $this->_forms[ $name ]['form_id'] ) ) {
					// load db form.
					$this->_forms[ $name ] = new FRMK_DB_Form( $this->_forms[ $name ]['form_id'] );
				}
			}

			$form = $this->_forms[ $name ];
			return $form;
		}

		return false;
	}

	/**
	 * Check and process form submission requests
	 */
	public function process_form() {

		// load and process current form.
		if ( $this->get_current_form() ) {
			$this->_form->process();

			// display ajax response.
			if ( $this->_form->is_ajax() ) {
				$this->_form->render_ajax();
				die();
			}
		}
	}

	/**
	 * Get currently loaded form
	 *
	 * @return bool|FRMK_Form
	 */
	public function get_current_form() {

		if ( null !== $this->_form ) {
			return $this->_form;
		}

		// find active form, maybe a hidden form action field was submitted?
		// if so load that form.
		$form_id = isset($_POST['frmk_action']) ? esc_attr($_POST['frmk_action']) : false;
		if ( $form_id ) {

			$this->_form = $this->get_form( $form_id );
			return $this->_form;
		}

		return false;
	}

	/**
	 * Get list of forms
	 *
	 * @return FRMK_Form[]
	 */
	public function get_forms() {
		return $this->_forms;
	}

	/**
	 * Get database version
	 *
	 * @return int
	 */
	public function get_db_version() {
		return $this->db_version;
	}

	/**
	 * Get forms from database
	 */
	public function load_db_forms() {

		$query = new WP_Query(array(
			'post_type' => 'frmk_form',
			'posts_per_page' => -1,
			'fields' => 'ids',
		));

		if ( $query->have_posts() ) {
			foreach ( $query->posts as $id ) {
				$form_key = sprintf( 'FRMK_FORM_%d', $id );
				$this->_forms[ $form_key ] = array( 'form_id' => $id );
			}
		}
	}

	/**
	 * Get list of installed modules
	 *
	 * @return FRMK_Addon[]
	 */
	public function get_modules(){
		return $this->_modules;
	}
}

/**
 * Globally access Frmk_Plugin instance.
 *
 * @return Frmk_Plugin
 */
function FRMK() {
	return Frmk_Plugin::instance();
}

$GLOBALS['frmk'] = FRMK();

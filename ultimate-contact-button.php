<?php
/**
 * Plugin Name: Ultimate Contact Button
 * Plugin URI:  #
 * Description: A professional contact button manager for Desktop and Mobile.
 * Version:     1.4.1
 * Author:      Tuend Work
 * Author URI:  #
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ultimate-contact-button
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class
 */
final class Ultimate_Contact_Button {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const VERSION = '1.4.0';

	/**
	 * Instance of this class
	 *
	 * @var Ultimate_Contact_Button
	 */
	private static $instance;

	/**
	 * Get instance of this class
	 *
	 * @return Ultimate_Contact_Button
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Ultimate_Contact_Button ) ) {
			self::$instance = new Ultimate_Contact_Button();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Setup plugin
	 */
	private function setup() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		define( 'UCB_VERSION', self::VERSION );
		define( 'UCB_FILE', __FILE__ );
		define( 'UCB_PATH', plugin_dir_path( UCB_FILE ) );
		define( 'UCB_URL', plugin_dir_url( UCB_FILE ) );
		define( 'UCB_BASENAME', plugin_basename( UCB_FILE ) );
	}

	/**
	 * Include files
	 */
	private function includes() {
		require_once UCB_PATH . 'inc/helpers.php';
		require_once UCB_PATH . 'inc/class-assets.php';
		require_once UCB_PATH . 'inc/class-settings.php';
		require_once UCB_PATH . 'inc/class-frontend.php';
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		
		// Activation & Deactivation hooks
		register_activation_hook( UCB_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( UCB_FILE, array( $this, 'deactivate' ) );
	}

	/**
	 * Load text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'ultimate-contact-button', false, dirname( UCB_BASENAME ) . '/languages' );
	}

	/**
	 * Plugin activation logic
	 */
	public function activate() {
		// Initialize default settings if needed
	}

	/**
	 * Plugin deactivation logic
	 */
	public function deactivate() {
		// Cleanup if needed
	}
}

/**
 * Initialize Plugin
 */
function UCB() {
	return Ultimate_Contact_Button::get_instance();
}

// Start the plugin
UCB();

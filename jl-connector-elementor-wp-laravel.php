<?php

/*
* Plugin Name: Jl Connector Wp Elementor to Laravel
* Author: Jorge Luis Veliz
* Description: Permite tener una conexion entre El sistema Administrativo de Futbol (Laravel) y El formulario de registro construido en WP (con el plugin Elementor)
* Version: 1.0
* AUthor Uri: https://thejlmedia.com
*/

// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly.
// }

// define('JL_PLUGIN_DIR', plugin_dir_path(__FILE__));

// final class ElementorWPLaravelConnector {


//      /**
// 	 * Plugin Name
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @var string The plugin version.
// 	 */
//     const NAME = 'Jl Connector Wp Elementor to Laravel';


//     /**
// 	 * Plugin Version
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @var string The plugin version.
// 	 */
//     const VERSION = '1.0.0';
    

//     /**
// 	 * Minimum PHP Version
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @var string Minimum PHP version required to run the plugin.
// 	 */
//     const MINIMUM_PHP_VERSION = '7.0';


//     /**
// 	 * Minimum Elementor Version
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @var string Minimum Elementor version required to run the plugin.
// 	 */
// 	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    


//     /**
// 	 * Instance
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access private
// 	 * @static
// 	 *
// 	 * @var ElementorWPLaravelConnector The single instance of the class.
// 	 */
//     private static $_instance = null;
    

//     /**
// 	 * Instance
// 	 *
// 	 * Ensures only one instance of the class is loaded or can be loaded.
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 * @static
// 	 *
// 	 * @return ElementorWPLaravelConnector An instance of the class.
// 	 */
// 	public static function instance() {

//         if ( is_null( self::$_instance ) ) {
//             self::$_instance = new self();
// 		}
        
// 		return self::$_instance;

//     }
    

//     /**
// 	 * Constructor
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 */
// 	public function __construct() {

// 		add_action( 'init', [ $this, 'i18n' ] );
// 		add_action( 'plugins_loaded', [ $this, 'init' ] );

//     }
    

//     /**
// 	 * Load Textdomain
// 	 *
// 	 * Load plugin localization files.
// 	 *
// 	 * Fired by `init` action hook.
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 */
// 	public function i18n() {

// 		load_plugin_textdomain( 'jl-wp-laravel' );

//     }


//     /**
// 	 * Initialize the plugin
// 	 *
// 	 * Load the plugin only after Elementor (and other plugins) are loaded.
// 	 * Checks for basic plugin requirements, if one check fail don't continue,
// 	 * if all check have passed load the files required to run the plugin.
// 	 *
// 	 * Fired by `plugins_loaded` action hook.
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 */

//      public function init()
//      {
//          // Check if Elementor installed and activated
//          if ( ! did_action( 'elementor/loaded' ) ) {
//              add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
//              return false;
//          }

         
//         //  Check for required Elementor version
//          if ( ! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
//              add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
//              return false;
//          }
    
//          // Check for required PHP version
//          if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
//              add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
//              return false;
//          }


//          add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
// 	}

     


//      /**
// 	 * Admin notice
// 	 *
// 	 * Warning when the site doesn't have Elementor installed or activated.
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 */
// 	public function admin_notice_missing_main_plugin() {

// 		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

// 		$message = sprintf(
// 			/* translators: 1: Plugin name 2: Elementor */
// 			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'jl-wp-laravel' ),
// 			'<strong>' . esc_html__( self::NAME, 'jl-wp-laravel' ) . '</strong>',
// 			'<strong>' . esc_html__( 'Elementor', 'jl-wp-laravel' ) . '</strong>'
// 		);

// 		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

//     }
    

//     /**
// 	 * Admin notice
// 	 *
// 	 * Warning when the site doesn't have a minimum required Elementor version.
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 */
// 	public function admin_notice_minimum_elementor_version() {

// 		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

// 		$message = sprintf(
// 			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
// 			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'jl-wp-laravel' ),
// 			'<strong>' . esc_html__( self::NAME, 'jl-wp-laravel' ) . '</strong>',
// 			'<strong>' . esc_html__( 'Elementor', 'jl-wp-laravel' ) . '</strong>',
// 			 self::MINIMUM_ELEMENTOR_VERSION
// 		);

// 		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

// 	}

// 	/**
// 	 * Admin notice
// 	 *
// 	 * Warning when the site doesn't have a minimum required PHP version.
// 	 *
// 	 * @since 1.0.0
// 	 *
// 	 * @access public
// 	 */
// 	public function admin_notice_minimum_php_version() {

// 		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

// 		$message = sprintf(
// 			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
// 			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'jl-wp-laravel' ),
// 			'<strong>' . esc_html__( self::NAME, 'jl-wp-laravel' ) . '</strong>',
// 			'<strong>' . esc_html__( 'PHP', 'jl-wp-laravel' ) . '</strong>',
// 			 self::MINIMUM_PHP_VERSION
// 		);

// 		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

//     }
    


//     public function init_widgets() {
// 		// Include Widget files
// 		require  JL_PLUGIN_DIR . 'widgets/widget-fields.php' ;
		
// 		// $wid = new \Elementor\WidgetFields();

		
// 		// Register control
// 		// \Elementor\Plugin::$instance->widgets_manager->register_widget_type( new \Elementor\WidgetFields() );

//     }
    


// }


// //RUN AND FUN!
// ElementorWPLaravelConnector::instance();


add_action( 'elementor/element/form/section_form_fields/before_section_end', 'custom_select_field', 10, 2 );
/**
 * Adding button fields
 * @param \Elementor\Widget_Base $button
 * @param array                  $args
 */
function custom_select_field( $button, $args ) {

	

	$button->add_control( 'fields',
        [
        	'label' => __( 'Canchas', 'elementor' ),
        	'type' => \Elementor\Controls_Manager::SWITCHER,
        ]
	);
}
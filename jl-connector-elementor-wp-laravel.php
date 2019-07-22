<?php

/*
* Plugin Name: Jl Connector Wp Elementor to Laravel
* Author: Jorge Luis Veliz
* Description: Permite tener una conexion entre El sistema Administrativo de Futbol (Laravel) y El formulario de registro construido en WP (con el plugin Elementor)
* Version: 1.0
* AUthor Uri: https://thejlmedia.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('JL_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once JL_PLUGIN_DIR .'classes/field-handler.php';

class JLConnectorLaravel 
{
	
	const PLUGIN_NAME = 'jl-connector-laravel-elementor';
	
	const PLUGIN_TITLE = 'JL Elementor - Laravel Connector';

	private $error_message;

	public  function run_handlers() {
		new FieldHandler();
	}


	public function jl_admin_notice_error() { 

		$class = 'notice notice-error';
    	$message = __( $this->error_message, self::PLUGIN_NAME );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	
	}


	public function jl_admin_submit_elementor_laravel_connector() {
		if ('POST' == $_SERVER['REQUEST_METHOD']) {
			if(isset($_POST['jl_elementor_laravel_connector'])){
				
				if(!isset($_POST['jl_elementor_laravel_connector']['jl_field_endpoint']) || empty($_POST['jl_elementor_laravel_connector']['jl_field_endpoint'])) {
					$this->error_message = "Por favor ingrese la url Endpoint";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;
				}
			} 


			
		}
	}


	public function jl_connector_laravel_elmentor_basic_config_field_cb($args) {
		
		// get_option()
		$options = get_option('jl_connector_laravel_elementor');
	?>
		 
	<input type="<?php echo  $args['input_type']; ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="jl_elementor_laravel_connector[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php if(isset($args['width'])) : ?> style="width:<?php echo esc_attr($args['width']) ?>" <?php endif; ?>/>
 <?php
		
	}

	public function jl_connector_laravel_elrmentor_basic_config_cb($args) {
	?> 
		<p id="<?php  echo esc_attr($args['id']) ?>"><?php esc_html_e('Permite conectarme directamente con una Api de Laravel logoneado con un usuario y contraseña, Es necesario que este usuario sea de rol SuperAdmin') ?></p>
	<?php

	}


	public function jl_admin_register_config_elementor_laravel_connector() {

		register_setting(self::PLUGIN_NAME,'jl_connector_laravel_elementor');

		add_settings_section(
			'jl_basic_config',
			__('Datos de Conexión',self::PLUGIN_NAME),
			[$this,'jl_connector_laravel_elrmentor_basic_config_cb'],
			self::PLUGIN_NAME
		);

		add_settings_field(
			'jl_le_field_url_api' ,
			__('Endpoint',self::PLUGIN_NAME),
			[$this,'jl_connector_laravel_elmentor_basic_config_field_cb'],
			self::PLUGIN_NAME,
			'jl_basic_config',
			[
				'label_for' => 'jl_field_endpoint',
				'class' => 'jl_url_field_endpoint_row',
				'wporg_custom_data' => 'custom',
				'input_type'=>'text',
				'width' => '50%'
			]
		);
		
		add_settings_field(
			'jl_le_field_user_api' ,
			__('Username',self::PLUGIN_NAME),
			[$this,'jl_connector_laravel_elmentor_basic_config_field_cb'],
			self::PLUGIN_NAME,
			'jl_basic_config',
			[
				'label_for' => 'jl_field_username',
				'class' => 'jl_url_field_username_row',
				'wporg_custom_data' => 'custom',
				'input_type'=>'text'
			]
		);
		
		
		add_settings_field(
			'jl_le_field_password_api' ,
			__('Password',self::PLUGIN_NAME),
			[$this,'jl_connector_laravel_elmentor_basic_config_field_cb'],
			self::PLUGIN_NAME,
			'jl_basic_config',
			[
				'label_for' => 'jl_field_password',
				'class' => 'jl_url_field_password_row',
				'wporg_custom_data' => 'custom',
				'input_type'=>'password'
			]
		);

	}


	public function jl_admin_config_elementor_laravel_connector () {
		if(!current_user_can('manage_options')) return false; ?>

		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()) ?></h1>

			<form action="<?php menu_page_url(self::PLUGIN_NAME); ?>" method="POST">
				<?php 
					//output setting fields
					settings_fields( 'jl_connector_laravel_elementor' );

					do_settings_sections(self::PLUGIN_NAME);

					submit_button('Save Settings');
				?>
			</form>


		</div>

	<?php
		
	}


	public function jl_admin_config_submenu_page() {

		$hookname = add_submenu_page(
			'options-general.php',
			self::PLUGIN_TITLE,
			self::PLUGIN_TITLE,
			'manage_options',
			self::PLUGIN_NAME,
			[$this,'jl_admin_config_elementor_laravel_connector']
		);
		//submit form
		
		add_action('load-'.$hookname,[$this,'jl_admin_submit_elementor_laravel_connector']);

	}
	public function __construct() {
		add_action('init',[$this,'run_handlers']);
		add_action('admin_menu',[$this,'jl_admin_config_submenu_page']);
		add_action('admin_init',[$this,'jl_admin_register_config_elementor_laravel_connector']);
	}

	
	
}

new JLConnectorLaravel();



// add_action( 'elementor/element/form/section_form_fields/before_section_end', 'custom_select_field', 10, 2 );
// /**
//  * Adding button fields
//  * @param \Elementor\Widget_Base $button
//  * @param array                  $args
//  */
// function custom_select_field( $button, $args ) {

	

// 	$button->add_control( 'fields',
//         [
//         	'label' => __( 'Canchas', 'elementor' ),
//         	'type' => \Elementor\Controls_Manager::SWITCHER,
//         ]
// 	);
// }
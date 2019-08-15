<?php
use Mockery\CountValidator\Exception;

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

require_once JL_PLUGIN_DIR .'jl-connector-elementor-wp-base.php';
require_once JL_PLUGIN_DIR .'classes/age-handler.php';
require_once JL_PLUGIN_DIR .'classes/field-handler.php';
require_once JL_PLUGIN_DIR .'classes/day-handler.php';
require_once JL_PLUGIN_DIR .'classes/hour-handler.php';
require_once JL_PLUGIN_DIR .'classes/form-handler.php';

class JLConnectorLaravel extends jl_connector_elementor_wp_base
{
	

	private $error_message;

	private $success_message;

	public function jl_admin_notice_success() { 

		$class = 'notice notice-success';
    	$message = __( $this->success_message, self::PLUGIN_NAME );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	
	}
	
	public function jl_admin_notice_error() { 

		$class = 'notice notice-error';
    	$message = __( $this->error_message, self::PLUGIN_NAME );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	
	}


	public function jl_admin_submit_elementor_laravel_connector() {
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			$update = false ;

			if(array_key_exists('action',$_POST) && $_POST['action'] == 'update') $update = true;

			if(isset($_POST['jl_elementor_laravel_connector'])){
				
				if(!isset($_POST['jl_elementor_laravel_connector']['jl_field_endpoint']) || empty($_POST['jl_elementor_laravel_connector']['jl_field_endpoint'])) {
					$this->error_message = "Por favor ingrese la url Endpoint";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;
				}


				if(!isset($_POST['jl_elementor_laravel_connector']['jl_field_username']) || empty($_POST['jl_elementor_laravel_connector']['jl_field_username'])) {
					$this->error_message = "Por favor ingrese un usuario";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;
				}
				
				
				if(!isset($_POST['jl_elementor_laravel_connector']['jl_field_password']) || empty($_POST['jl_elementor_laravel_connector']['jl_field_password'])) {
					$this->error_message = "Por favor ingrese una Contraseña";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;

				}

				$sanitized_endpoint = sanitize_text_field($_POST['jl_elementor_laravel_connector']['jl_field_endpoint']);

				if($sanitized_endpoint) {
					if($update) {
						update_option('jl_field_endpoint',$sanitized_endpoint);
					} else {
						add_option('jl_field_endpoint',$sanitized_endpoint);
					}
				} else {
					$this->error_message = "Parámetro EndPoint mal ingresado";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;

				}

				$sanitized_username = sanitize_text_field($_POST['jl_elementor_laravel_connector']['jl_field_username']);

				if ($sanitized_username) {
					if($update) {
						update_option('jl_field_username',$sanitized_username);
					} else {
						add_option('jl_field_username',$sanitized_username);
					}
				} else {
					$this->error_message = "Parámetro username mal ingresado";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;
				}

				$sanitized_password = sanitize_text_field($_POST['jl_elementor_laravel_connector']['jl_field_password']);

				if ($sanitized_password) {
					
					if($update) {
						update_option('jl_field_password',$sanitized_password);
					} else {
						add_option('jl_field_password',$sanitized_password);
					}
				} else {
					$this->error_message = "Parámetro password mal ingresado";
					add_action('admin_notices', [$this,'jl_admin_notice_error']);
					return false;
				}


				//establece la conexion
					$connect = $this->connect();

					if (is_wp_error($connect)) {
						$this->error_message = $connect->get_error_code() .'-'.$connect->get_error_message()  ;
						add_action('admin_notices', [$this,'jl_admin_notice_error']);
					} else {
						$this->success_message = "Configuración realizada satisfactoriamente";
						add_action('admin_notices', [$this,'jl_admin_notice_success']);
					}

			} else {
				return false;
			}


			
		}
	}


	public function jl_connector_laravel_elmentor_basic_config_field_cb($args) {
		
		// get_option()
		$option = get_option($args['label_for']);
	?>
		 
	<input type="<?php echo  $args['input_type']; ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="jl_elementor_laravel_connector[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php if(isset($args['width'])) : ?> style="width:<?php echo esc_attr($args['width']) ?>" <?php endif; ?> value='<?php echo $option; ?>'/>
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
				'width' => '50%',
				
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
				'input_type'=>'text',
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
				'input_type'=>'password',
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
		add_action('admin_init',[$this,'jl_admin_register_config_elementor_laravel_connector']);
		add_action('admin_menu',[$this,'jl_admin_config_submenu_page']);
		add_action('wp_enqueue_scripts',[$this,'load_assets']);
	}

	
	
}

$jlConnector = new JLConnectorLaravel();

if ($jlConnector->connect()) {
	$token = $jlConnector->get_token();
	new AgeHandler($token);
	new FieldHandler($token);
	new DayHandler($token);
	new HourHandler($token);
	new FormHandler($token);
}
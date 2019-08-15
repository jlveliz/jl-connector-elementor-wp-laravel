<?php 

class jl_connector_elementor_wp_base {


    const PLUGIN_NAME = 'jl-connector-laravel-elementor';
	
    const PLUGIN_TITLE = 'JL Elementor - Laravel Connector';
    
    const SUCCESS_CODE = 200;

    private $error_message;

    protected $connected = false;

    private $token;

    private $route_login = '/login'; 

    
    public function load_assets() {
        wp_register_script('field-handler',plugin_dir_url(__FILE__).'/assets/js/handler-field.js',['jquery'],true);
        wp_register_style('field-handler', plugin_dir_url(__FILE__).'/assets/css/handler-field.css');
        wp_enqueue_script('field-handler');
        wp_enqueue_style('field-handler');
    }
    

    public function jl_admin_notice_error () {

        $class = 'notice notice-error';
    	$message = __( $this->error_message, self::PLUGIN_NAME );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	
					
    }

    public function connect()
    {
        
        $end_point = get_option('jl_field_endpoint');
        $username = get_option('jl_field_username');
        $password = get_option('jl_field_password');

        if(!$end_point  || !$username || !$password) {
            return false;
        }

        $params = [
            'email' => $username,
            'password' => $password
        ];

        

        $response = wp_remote_post($end_point . $this->route_login, ['body'=>$params]);

        if (wp_remote_retrieve_response_code($response) != self::SUCCESS_CODE) {
            return new WP_Error(wp_remote_retrieve_response_code($response),wp_remote_retrieve_response_message($response));
        } else {
            $body =  json_decode(wp_remote_retrieve_body( $response ), true) ;
            $this->set_token($body['token']);
            return true;
        }
        
    }

    public function has_connected()
    {
        return $this->connected;
    }


    public function set_token($token) {
        $this->token = 'Bearer '.$token;
    }

    public function get_token() {
        return $this->token;
    }


    


}
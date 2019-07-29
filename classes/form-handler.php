<?php 

    
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
    
    class FormHandler 
    {

        private $token;

        private $route = '/register';

        private function getHeaders() {
            return [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->token
                ]
            ];
        }


        public function set_data_to_laravel ($record, $handler) {
            
            var_dump("entra");
            $raw_fields = $record->get( 'fields' );
            
            $end_point = get_option('jl_field_endpoint');

            if (!$end_point || !$this->token) {
                return false;
            }

            $url = $end_point . $this->route;
            $args = $this->getHeaders();

            $args['body'] = $raw_fields['form_fields'];
            var_dump($args);
            die();
            $response = wp_remote_post($url, $args);
            $message = wp_remote_retrieve_body($response);

            var_dump($message);
            die();


        }


        public function __construct($token) {
            $this->token = $token;
            add_action( 'elementor_pro/forms/new_record',[$this,'set_data_to_laravel']);
        }
        
    }
    

?>
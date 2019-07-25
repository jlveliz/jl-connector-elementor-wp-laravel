<?php 

    
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
    
    class FormHandler 
    {

        private $token;


        public function set_data_to_laravel ($record, $handler) {

            var_dump($handler);

            die();

        }


        public function __construct($token) {
            $this->token = $token;
            add_action( 'elementor_pro/forms/new_record',[$this,'set_data_to_laravel']);
        }
        
    }
    

?>
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


        public function __construct($token) {
            $this->token = $token;
            add_action( 'elementor_pro/forms/new_record', function( $record, $handler ) {
                //make sure its our form
                $form_name = $record->get_form_settings( 'form_name' );
            
                $end_point = get_option('jl_field_endpoint');
                $raw_fields = $record->get( 'sent_data' );
                
                
                $url = $end_point . $this->route;
                $args = $this->getHeaders();

                
                /*
                    TOCAR CON CAUTELA 
                */ 
                //children
                $args['body'] = [
                    'name' => $raw_fields['nombre_hijo'],
                    'last_name' => $raw_fields['apellido_hijo'],
                    'age' => $raw_fields['age'],

                ];
                //representant
                $args['body']['representant'] = [
                    'name' => $raw_fields['nombre_representante'],
                    'last_name' => $raw_fields['apellido_representante'],
                    'email' => $raw_fields['email'],
                    'mobile' => $raw_fields['celular'],
                ];

                $args['body']['enrollment'] = [
                    'field_id' => $raw_fields['field'],
                    'day' => $raw_fields['day'],
                    'hour' => $raw_fields['hour']
                ];
               
                
                $response = wp_remote_post($url,$args);


                $message = wp_remote_retrieve_body( $response );

                
                if($response['response']['code'] == 401 || $response['response']['code'] == 500) {
                    $handler->add_error_message($message)->send();
                    
                }

            }, 10, 2 );




            
        }
        
    }
    

?>
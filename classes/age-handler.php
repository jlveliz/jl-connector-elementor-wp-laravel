<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Elementor\Widget_Base;
use ElementorPro\Plugin;
use ElementorPro\Modules\Forms\Fields\Field_Base;


class AgeHandler 
{
    
    private $ages;

    private $token;

    private $route = "/ages";
    


    private function getHeaders() {
        return [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $this->token
            ]
        ];
    }
    
    private function getAges() {

        $end_point = get_option('jl_field_endpoint');


        if (!$end_point || !$this->token) {
            return false;
        }

        $url = $end_point . $this->route;

        $args = $this->getHeaders();

        $response = wp_remote_get($url, $args);
        $ages = wp_remote_retrieve_body($response);
        $this->ages = $this->stringToArray($ages);
        
    }
    
    
    private function stringToArray ($string) {
        return json_decode($string,true);
    }

    
    private function get_type() {
        return 'age';
    }

    private function get_name() {
        return __("Age", "elementor-pro");
    }


    public function register_age_type( $field_types ) {
        $field_types[$this->get_type()] = $this->get_name();
       
		return $field_types;
    }
    
    public function render_age_select( $item, $item_index, $widget ) {

        $widget->add_render_attribute(
			[
				'select-wrapper'. $item_index => [
					'class' => [
						'elementor-field',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $item_index => [
					'name' =>$widget->get_attribute_name($item),
					'id' => $widget->get_attribute_id( $item ),
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
        );
        $this->getAges();
       
        ?>


        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper' . $item_index ); ?>>
            <select <?php echo $widget->get_render_attribute_string( 'select' . $item_index ); ?> data-element="jl-elementor-laravel-api-age">
              <option value="null">Selecciona la Edad del Niño(a)</option>
               <?php foreach($this->ages as $age):?>
                <option value="<?php echo $age ?>"><?php echo $age ?> Años</option>
               <?php endforeach;?>
            </select>
        </div>

        <input type="hidden" id="api-key-token" value="<?php echo $this->token; ?>">
        <input type="hidden" id="api-url" value="<?php echo get_option('jl_field_endpoint'); ?>"> 
    <?php

    }
    

    public function __construct ($token) {
        $this->token = $token;
        add_filter( 'elementor_pro/forms/field_types', [ $this, 'register_age_type' ] );
        $type = $this->get_type();
        $actionName = "elementor_pro/forms/render_field/{$type}";
        add_action($actionName, [ $this, 'render_age_select' ], 10, 3 );
    }
}

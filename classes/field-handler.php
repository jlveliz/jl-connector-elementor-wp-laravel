<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Elementor\Widget_Base;
use ElementorPro\Plugin;


class FieldHandler
{
    
    private $fields;

    private $token;

    private $route = "/fields";


    private function getHeaders() {
        return [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $this->token
            ]
        ];
    }
    
    private function getFields() {

        $end_point = get_option('jl_field_endpoint');


        if (!$end_point || !$this->token) {
            return false;
        }

        $url = $end_point . $this->route;

        $args = $this->getHeaders();

        $response = wp_remote_get($url, $args);
        $fields = wp_remote_retrieve_body($response);
        $this->fields = $this->stringToArray($fields);
    }

    private function stringToArray ($string) {
        return json_decode($string,true);
    }


    public function add_field_type( $field_types ) {
        
        $field_types['field'] = __( 'Field', 'elementor-pro' );

		return $field_types;
    }
    
    public function render_select( $item, $item_index, $widget ) {

        $widget->add_render_attribute(
			[
				'select-wrapper' . $item_index => [
					'class' => [
						'elementor-field',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $item_index => [
					'name' => $widget->get_attribute_name( $item ),
					'id' => $widget->get_attribute_id( $item ),
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
        );
        
        $this->getFields();
    ?>
        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper' . $item_index ); ?>>
            <select <?php echo $widget->get_render_attribute_string( 'select' . $item_index ); ?> data-element="jl-laravel-api">
              <option value="null">Selecciona la cancha más cercana</option>
               <?php for($i = 0; $i < count($this->fields); $i++  ):?>
                <option value="<?php echo $this->fields[$i]['id'] ?>"><?php echo $this->fields[$i]['name'] ?></option>
               <?php endfor;?>
            </select>
        </div>
    
    <?php

    }
    

    public function __construct ($token) {
        $this->token = $token;
        add_filter( 'elementor_pro/forms/field_types', [ $this, 'add_field_type' ] );
        add_action( 'elementor_pro/forms/render_field/field', [ $this, 'render_select' ], 10, 3 );
    }
}

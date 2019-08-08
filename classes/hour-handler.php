<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Elementor\Widget_Base;
use ElementorPro\Plugin;


class HourHandler
{
    
	private $token;

	
	private function get_type() {
        return 'hour';
    }

    private function get_name() {
        return __("Hour", "elementor-pro");
    }


    public function add_field_type( $field_types ) {
        
        $field_types[$this->get_type()] = $this->get_name();

		return $field_types;
    }
    
    public function render_select( $item, $item_index, $widget ) {

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
        
    ?>
        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper' . $item_index ); ?>>
            <select <?php echo $widget->get_render_attribute_string( 'select' . $item_index ); ?> data-element="jl-elementor-laravel-api-hour" disabled='disabled'>
              <option value="null">Seleccione la Hora</option>
            </select>
        </div>

   	<?php

    }
    

    public function __construct ($token) {
        $this->token = $token;
		add_filter( 'elementor_pro/forms/field_types', [ $this, 'add_field_type' ] );
		$type = $this->get_type();
        $actionName = "elementor_pro/forms/render_field/{$type}";
        add_action( $actionName, [ $this, 'render_select' ], 10, 3 );
    }
}

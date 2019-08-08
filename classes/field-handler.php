<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Elementor\Widget_Base;
use ElementorPro\Plugin;


class FieldHandler
{
    
    private $ages;

    private $token;

    public function add_field_type( $field_types ) {
        
        $field_types['field'] = __( 'Field', 'elementor-pro' );

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
            <select <?php echo $widget->get_render_attribute_string( 'select' . $item_index ); ?> style='margin-bottom:12px' data-element="jl-elementor-laravel-api-field" disabled='disabled'>
              <option value="null">Selecciona la cancha más cercana</option>
            </select>
        </div>

    <?php 
   
        // $widget->add_render_attribute(
		// 	[
		// 		'select-wrapper-day' => [
		// 			'class' => [
		// 				'elementor-day',
		// 				'elementor-select-wrapper',
		// 				esc_attr( $item['css_classes'] ),
		// 			],
		// 		],
		// 		'select-day' . $item_index => [
		// 			'name' =>"form_fields[day]",
		// 			'id' => $widget->get_attribute_id( $item ).'-day',
		// 			'class' => [
		// 				'elementor-field-textual',
		// 				'elementor-size-' . $item['input_size'],
		// 			],
		// 		],
		// 	]
        // );
    ?>
        <!-- <div <?php //echo $widget->get_render_attribute_string( 'select-wrapper-day'); ?> style='margin-bottom:12px'>
            <select <?php //echo $widget->get_render_attribute_string( 'select-day' . $item_index ); ?>  disabled='disabled' data-element="jl-elementor-laravel-api-day">
              <option value="null">Selecciona el día</option>
            </select>
        </div> -->
        
    <?php 
        //  $widget->add_render_attribute(
		// 	[
		// 		'select-wrapper-hour' => [
		// 			'class' => [
		// 				'elementor-hour',
		// 				'elementor-select-wrapper',
		// 				esc_attr( $item['css_classes'] ),
		// 			],
		// 		],
		// 		'select-hour' . $item_index => [
		// 			'name' =>"form_fields[hour]",
		// 			'id' => $widget->get_attribute_id( $item ).'-hour',
		// 			'class' => [
		// 				'elementor-field-textual',
		// 				'elementor-size-' . $item['input_size'],
		// 			],
		// 		],
		// 	]
        // );
    ?>
        <!-- <div <?php //echo $widget->get_render_attribute_string( 'select-wrapper-hour'); ?> style='margin-bottom:12px'>
            <select <?php //echo $widget->get_render_attribute_string( 'select-hour' . $item_index ); ?> data-element="jl-elementor-laravel-api-hour" disabled='disabled'>
              <option value="null">Selecciona la hora</option>
            </select>
        </div>-->
         
    <?php

    }
    

    public function __construct ($token) {
        $this->token = $token;
        add_filter( 'elementor_pro/forms/field_types', [ $this, 'add_field_type' ] );
        add_action( 'elementor_pro/forms/render_field/field', [ $this, 'render_select' ], 10, 3 );
    }
}

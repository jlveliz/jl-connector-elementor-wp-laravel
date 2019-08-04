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


    public function add_field_type( $field_types ) {
        
        $field_types['field'] = __( 'Field', 'elementor-pro' );

		return $field_types;
    }
    
    public function render_select( $item, $item_index, $widget ) {

        $widget->add_render_attribute(
			[
				'select-wrapper-age' => [
					'class' => [
						'elementor-age',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select-age' . $item_index => [
					'name' =>"form_fields[age]",
					'id' => $widget->get_attribute_id( $item ).'-age',
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
        );
        $this->getAges();

        ?>
        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper-age'); ?> style='margin-bottom:12px'>
            <select <?php echo $widget->get_render_attribute_string( 'select-age' . $item_index ); ?>  data-element="jl-elementor-laravel-api-age">
              <option value="null">Selecciona la edad</option>
              <?php foreach($this->ages as $keyAge => $age) :  ?>
              <option value="<?php echo $keyAge  ?>"><?php echo $age ?> Años</option>
              <?php endforeach; ?>
            </select>
        </div>
        <?php
        
        
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
        
        
    ?>
        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper' . $item_index ); ?> style='margin-bottom:12px'>
            <select <?php echo $widget->get_render_attribute_string( 'select' . $item_index ); ?> data-element="jl-elementor-laravel-api-field" disabled='disabled'>
              <option value="null">Selecciona la cancha más cercana</option>
               <?php for($i = 0; $i < count($this->fields); $i++  ):?>
                <option value="<?php echo $this->fields[$i]['id'] ?>"><?php echo $this->fields[$i]['name'] ?></option>
               <?php endfor;?>
            </select>
        </div>

    <?php 
   
        $widget->add_render_attribute(
			[
				'select-wrapper-day' => [
					'class' => [
						'elementor-day',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select-day' . $item_index => [
					'name' =>"form_fields[day]",
					'id' => $widget->get_attribute_id( $item ).'-day',
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
        );
    ?>
        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper-day'); ?> style='margin-bottom:12px'>
            <select <?php echo $widget->get_render_attribute_string( 'select-day' . $item_index ); ?>  disabled='disabled' data-element="jl-elementor-laravel-api-day">
              <option value="null">Selecciona el día</option>
            </select>
        </div>
        
    <?php 
         $widget->add_render_attribute(
			[
				'select-wrapper-hour' => [
					'class' => [
						'elementor-hour',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select-hour' . $item_index => [
					'name' =>"form_fields[hour]",
					'id' => $widget->get_attribute_id( $item ).'-hour',
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
        );
    ?>
        <div <?php echo $widget->get_render_attribute_string( 'select-wrapper-hour'); ?> style='margin-bottom:12px'>
            <select <?php echo $widget->get_render_attribute_string( 'select-hour' . $item_index ); ?> data-element="jl-elementor-laravel-api-hour" disabled='disabled'>
              <option value="null">Selecciona la hora</option>
            </select>
        </div>
        <input type="hidden" id="api-key-token" value="<?php echo $this->token; ?>">
        <input type="hidden" id="api-url" value="<?php echo get_option('jl_field_endpoint'); ?>">
    <?php

    }
    

    public function __construct ($token) {
        $this->token = $token;
        add_filter( 'elementor_pro/forms/field_types', [ $this, 'add_field_type' ] );
        add_action( 'elementor_pro/forms/render_field/field', [ $this, 'render_select' ], 10, 3 );
    }
}

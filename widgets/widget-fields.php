<?php 
// namespace Elementor;

// use ElementorPro\Modules\Forms\Widgets\Form;

// class WidgetFields  extends Form {

// 	protected function _register_controls() {

// 		$this->start_controls_section(
// 			'section_form_fields',
// 			[
// 				'label' => __( 'Content', 'plugin-name' ),
// 				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
// 			]
// 		);

// 		$this->add_control(
// 			'border_style',
// 			[
// 				'label' => __( 'Border Style', 'plugin-domain' ),
// 				'type' => \Elementor\Controls_Manager::SELECT,
// 				'default' => 'solid',
// 				'options' => [
// 					'solid'  => __( 'Solid', 'plugin-domain' ),
// 					'dashed' => __( 'Dashed', 'plugin-domain' ),
// 					'dotted' => __( 'Dotted', 'plugin-domain' ),
// 					'double' => __( 'Double', 'plugin-domain' ),
// 					'none' => __( 'None', 'plugin-domain' ),
// 				],
// 			]
// 		);

// 		$this->end_controls_section();

// 	}

// 	protected function render() {
// 		$settings = $this->get_settings_for_display();
// 		echo '<div style="border-style: ' . $settings['border_style'] . '"> .. </div>';
// 	}


add_action( 'elementor/element/button/section_style/before_section_end', function( $element, $args ) {
	var_dump($element);
	die();
	// $element->start_injection( [
	// 	'at' => 'before',
	// 	'of' => 'button_text_color',
	// ] );
	// // add a control
	// $element->add_control(
	// 	'btn_style',
	// 	[
	// 		'label' => 'Button Style',
	// 		'type' => \Elementor\Controls_Manager::SELECT,
	// 		'options' => [
	// 			'fancy' => 'Fancy',
	// 			'stylish' => 'Stylish',
	// 			'rounded' => 'Rounded',
	// 			'square' => 'Square',
	// 		],
	// 		'prefix_class' => 'btn-style-',
	// 	]
	// );

	// $element->end_injection();
}, 10, 2 );




}
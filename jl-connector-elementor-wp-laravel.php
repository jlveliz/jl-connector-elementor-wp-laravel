<?php

/*
* Plugin Name: Jl Connector Wp Elementor to Laravel
* Author: Jorge Luis Veliz
* Description: Permite tener una conexion entre El sistema Administrativo de Futbol (Laravel) y El formulario de registro construido en WP (con el plugin Elementor)
* Version: 1.0
* AUthor Uri: https://thejlmedia.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('JL_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once JL_PLUGIN_DIR .'classes/field-handler.php';

class JLConnectorLaravel 
{
	public function __construct() {
		
		new FieldHandler();
	}
}

new JLConnectorLaravel();



// add_action( 'elementor/element/form/section_form_fields/before_section_end', 'custom_select_field', 10, 2 );
// /**
//  * Adding button fields
//  * @param \Elementor\Widget_Base $button
//  * @param array                  $args
//  */
// function custom_select_field( $button, $args ) {

	

// 	$button->add_control( 'fields',
//         [
//         	'label' => __( 'Canchas', 'elementor' ),
//         	'type' => \Elementor\Controls_Manager::SWITCHER,
//         ]
// 	);
// }
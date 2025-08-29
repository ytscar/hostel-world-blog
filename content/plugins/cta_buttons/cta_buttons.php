<?php

/*
Plugin Name:  cta_buttons
Version: 1.0
Description: Output butttons from shortcodes
Author: RH
Author URI: https://www.hostelworld.com/blog
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: cta_buttons
*/
function shortcodes_init(){
	add_shortcode( 'efsbutton', 'buttons_cta' );
    add_shortcode( 'sc_button', 'buttons_cta' );

	function buttons_cta( $atts ) {
	 $a = shortcode_atts( array(
	 'link' => '#',
	 'id' => 'cta_buttons',
	 'color' => 'orange',
	 'size' => '',
	 'label' => '#',
	 'title' => 'title',
	 'target' => '_blank'
	 ), $atts );
	 $output = '<p><a href="' . esc_url( $a['link'] ) . '" target="_blank" id="' . esc_attr( $a['id'] ) . '" class="button ' . esc_attr( $a['color'] ) . ' ' . esc_attr( $a['size'] ) . '" >' . esc_attr( $a['title'] ) . '</a></p>';
	 return $output;
	}
}
add_action('init', 'shortcodes_init');

?>
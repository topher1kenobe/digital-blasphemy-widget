<?php
/*
Plugin Name: Digital Blasphemy Widget
Description: Creates w widget for rendering a variety of DB content
Author: Topher
Version: 1.0
*/

/**
 * include the file that gets one random freebie
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/random-freebie.class.php';

/**
 * include the file that gets the latest freebie
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/latest-freebie.class.php';

/**
 * include the file that has the widget class
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/make-widget.class.php';


// register Digital_Blasphemy_Widget widget
function register_digital_blasphemy_widget() {
	register_widget( 'Digital_Blasphemy_Widget' );
}
add_action( 'widgets_init', 'register_digital_blasphemy_widget' );

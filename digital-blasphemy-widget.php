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
include_once( 'includes/random_freebie.class.php' );

/**
 * include the file that gets the latest freebie
 */
include_once( 'includes/latest_freebie.class.php' );
$latest =  new Digital_Blasphemy_Latest_Freebie;

/**
 * include the file that has the widget class
 */
include_once( 'includes/make_widget.class.php' );


// register Digital_Blasphemy_Widget widget
function register_digital_blasphemy_widget() {
    register_widget( 'Digital_Blasphemy_Widget' );
}
add_action( 'widgets_init', 'register_digital_blasphemy_widget' );

?>

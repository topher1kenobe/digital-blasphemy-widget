<?php

/**
 * Makes available data for Digital Blasphemy Random Freebie
 */
class Digital_Blasphemy_Random_Freebie {

	public function __construct() {}

	/**
	 * Go get the JS file from Digital Blasphemy
	 *
	 * @return string JS file from DB
	 */
	private function get_db_js() {

		// don't bother storing in transient, we're doing that with output

		$db_js_url = "http://digitalblasphemy.com/dbfreebiesm.js";
		$db_js = wp_remote_get( $db_js_url );

		return $db_js;

	}

	/**
	 * Render a single random freebie
	 *
	 * @return string HTML of one random freebie
	 */
	public function render_random_freebie() {

		// create the transient name
		$transient_name = 'digitalblasphemy_js_output';
	 
		// try getting the transient.
		$output = get_transient( $transient_name );

		// if the get works properly, I should have an object in $featured_coaches.
		// If not, run the query.
		if ( !is_object( $output ) ) {

			$db_js = $this->get_db_js();

			$db_js_body = $db_js['body'];

			$db_js_body_array = preg_split( "/\r\n|\n|\r/", $db_js_body );

			$file_names = array();
			$image_names = array();

			foreach ( $db_js_body_array as $key => $string ) {

				$get_filename = $this->get_freebie_filename( $string );

				$get_imagename = $this->get_freebie_imagename( $string );

				if ( $get_filename != false ) {
					$file_names[] = $get_filename;
				}

				if ( $get_imagename != false ) {
					$image_names[] = $get_imagename;
				}
			}

			$final_array = array_combine( $file_names, $image_names );

			$image = array_rand( $final_array );

			$output = '<div class="digitalblasphemy_freebie">' . "\n";

			$output .= '<a href="http://digitalblasphemy.com/fshow.shtml?i=' . $image . '"><img src="http://digitalblasphemy.com/graphics/thumbs/' . $image . '_xthumb.jpg" title="' . $final_array[ $image ] . '" /></a>' . "\n";

			$output .= '<div class="db_title">';
			$output .= '<a href="http://digitalblasphemy.com/fshow.shtml?i=' . $image . '">';
			$output .= __( 'Enjoy a Free Wallpaper', 'digital-blasphemy-widget' );
			$output .= '</a></div>' . "\n";
			$output .= '<div class="db_from">';
			$output .= __( 'from', 'digital-blasphemy-widget' ) . ' <a href="http://digitalblasphemy.com">';
			$output .= 'digitalblasphemy.com';
			$output .= '</a></div>' . "\n";

			$output .= '</div>' . "\n";

			// save the results of the query with a 8 hour timeout
			set_transient( $transient_name, $output, 60*60*8 );

		}

		return $output;

	}

	/**
	 * Parse a string to get the file name of a random freebie
	 *
	 * @return string filename of a random freebie
	 */
	private function get_freebie_filename( $string ) {

		if ( strpos( $string, 'freebies[' ) === 0 ) {
			$data = explode( "'", $string );
			$output = $data[1];
		} else {
			$output = false;
		}

		return $output;

	}

	/**
	 * Parse a string to get the plain english name of a random freebie
	 *
	 * @return string english name of a random freebie
	 */
	private function get_freebie_imagename( $string ) {

		if ( strpos( $string, 'freebienames[' ) === 0 ) {
			$data = explode( "'", $string );
			return $data[1];
		} else {
			return false;
		}

	}


} // class Digital_Blasphemy_Random_Freebie

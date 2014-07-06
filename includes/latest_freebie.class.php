<?php

/**
 * Makes available data for Digital Blasphemy Latest Freebie
 */
class Digital_Blasphemy_Latest_Freebie {

	private $latest_db_content = NULL;
	private $latest_db_image_url = NULL;

	public function __construct() {
		$this->get_db_latest();
		$this->get_latest_db_freebie_url();
		$this->get_latest_db_image_url();
	}

	/**
	 * Go get the latest freebie file from Digital Blasphemy
	 *
	 * @return string JS file from DB
	 */
	private function get_db_latest() {

		// create the transient name
		$transient_name = 'digitalblasphemy_latest_freebie';
	 
		// try getting the transient.
		$db_latest = get_transient( $transient_name );

		// if the get works properly, I should have an object in $featured_coaches.
		// If not, run the query.
		if ( !is_object( $db_latest ) ) {

			$db_latest_url = "http://digitalblasphemy.com/cgi-bin/shownewfree.cgi";
			$db_latest = wp_remote_get( $db_latest_url );
			$db_latest_body = $db_latest['body'];

			// save the results of the query with a 8 hour timeout
			set_transient( $transient_name, wp_kses_post( $db_latest_body ), 60*60*8 );

		}

		$this->latest_db_content = wp_kses_post( $db_latest_body );

	}

	/**
	 * Extract Freebie URL from Latest data
	 *
	 * @return string URL of latest freebie
	 */
	private function get_latest_db_freebie_url() {

    	$doc = new DOMDocument();
    	$doc->loadHTML($this->latest_db_content);
    	$imageTags = $doc->getElementsByTagName('a');

    	foreach($imageTags as $tag) {
        	$output = $tag->getAttribute('href');
    	}

		$latest_db_freebie_url = $output;

	}

	/**
	 * Extract Image URL from Latest data
	 *
	 * @return string URL of latest image
	 */
	private function get_latest_db_image_url() {


    	$doc = new DOMDocument();
    	$doc->loadHTML($this->latest_db_content);
    	$imageTags = $doc->getElementsByTagName('img');

    	foreach($imageTags as $tag) {
        	$output = $tag->getAttribute('src');
    	}

		$this->latest_db_image_url = $output;

	}

	/**
	 * Render a single random freebie
	 *
	 * @return string HTML of one random freebie
	 */
	public function render_random_freebie() {

		$db_js = $this->latest_db_content;

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

		$output .= '<img src="http://digitalblasphemy.com/graphics/thumbs/' . $image . '_xthumb.jpg" title="' . $final_array[ $image ] . '" />' . "\n";

		$output .= '<div class="db_from">';
		$output .= __( 'Enjoy a Free Wallpaper from', 'db_freebie' );
		$output .= ' <a href="http://digitalblasphemy.com">';
		$output .= __( 'digitalblasphemy.com', 'db_freebie' );
		$output .= '</a></div>' . "\n";

		$output .= '</div>' . "\n";

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


} // class Digital_Blasphemy_Latest_Freebie

?>

<?php

/**
 * Makes available data for Digital Blasphemy Latest Freebie
 */
class Digital_Blasphemy_Latest_Freebie {

	private $latest_db_content = NULL;
	private $latest_db_freebie_url = NULL;
	private $latest_db_freebie_title = NULL;
	private $latest_db_image_url = NULL;
	private $db_url = 'http://digitalblasphemy.com';

	public function __construct() {
		$this->get_db_latest();
		$this->get_latest_db_freebie_url();
		$this->get_latest_db_freebie_title();
		$this->get_latest_db_image_url();
	}

	/**
	 * Go get the latest freebie file from Digital Blasphemy
	 *
	 * @return string JS file from DB
	 */
	private function get_db_latest() {

		// don't bother storing in transient, we're doing that with output later

		$db_latest_url = "http://digitalblasphemy.com/cgi-bin/shownewfree.cgi";
		$db_latest = wp_remote_get( $db_latest_url );
		$db_latest_body = $db_latest['body'];

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

		$this->latest_db_freebie_url = esc_url( $output );

	}

	/**
	 * Extract Freebie title from Latest data
	 *
	 * @return string title of latest freebie
	 */
	private function get_latest_db_freebie_title() {

		$doc = new DOMDocument();
		$doc->loadHTML($this->latest_db_content);
		$imageTags = $doc->getElementsByTagName( 'img' );

		foreach($imageTags as $tag) {
			$output = $tag->getAttribute( 'title' );
		}

		$this->latest_db_freebie_title = wp_kses_post( $output );

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

		$this->latest_db_image_url = esc_url( $output );

	}

	/**
	 * Render a single random freebie
	 *
	 * @return string HTML of one random freebie
	 */
	public function render_latest_freebie() {

		// create the transient name
		$transient_name = 'digitalblasphemy_latest_freebie_output';

		// try getting the transient.
		$output = get_transient( $transient_name );

		// if the get works properly, I should have an object in $featured_coaches.
		// If not, run the query.
		if ( !is_object( $output ) ) {

			$output = '<div class="digitalblasphemy digitalblasphemy_latest_freebie">' . "\n";

			$output .= '<a class="db_latest_freebie_link" href="' . $this->db_url . $this->latest_db_freebie_url . '" title="' . $this->latest_db_freebie_title . '">';
			$output .= '<img src="' . $this->db_url . $this->latest_db_image_url . '" alt="' . $this->latest_db_freebie_title . '" />';
			$output .= '</a>';

			$output .= '<div class="db_title"><a href="' . $this->db_url . $this->latest_db_freebie_url . '">' . $this->latest_db_freebie_title . '</a></div>' . "\n";
			$output .= '<div class="db_from">' . __( 'from', 'digital-blasphemy-widget' ) . ' <a href="' . $this->db_url . '">digitalblasphemy.com</a></div>' . "\n";

			$output .= '</div>' . "\n";

			// save the results of the query with a 8 hour timeout
			set_transient( $transient_name, wp_kses_post( $output ), 60*60*8 );

		}

		return $output;


	}

} // class Digital_Blasphemy_Latest_Freebie

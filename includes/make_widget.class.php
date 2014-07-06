<?php

/**
 * Adds Digital_Blasphemy_Widget widget.
 */
class Digital_Blasphemy_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'digitial-blasphemy-widget_widget', // Base ID
			__('Digital Blasphemy', 'digitial-blasphemy-widget'), // Name
			array( 'description' => __( 'Renders a variety of content from Digital Blasphemy.', 'digitial-blasphemy-widget' ), )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		echo $this->render_random_freebie();
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}


	/**
	 * Go get the JS file from Digital Blasphemy
	 *
	 * @return string JS file from DB
	 */
	private function get_db_js() {

		// create the transient name
		$transient_name = 'digitalblasphemy_js';
	 
		// try getting the transient.
		$db_js = get_transient( $transient_name );

		// if the get works properly, I should have an object in $featured_coaches.
		// If not, run the query.
		if ( !is_object( $db_js ) ) {

			$db_js_url = "http://digitalblasphemy.com/dbfreebiesm.js";
			$db_js = wp_remote_get( $db_js_url );

			// save the results of the query with a 8 hour timeout
			set_transient( $transient_name, $db_js, 60*60*8 );

		}

		return $db_js;

	}

	/**
	 * Render a single random freebie
	 *
	 * @return string HTML of one random freebie
	 */
	private function render_random_freebie() {

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

		$output .= '<img src="http://digitalblasphemy.com/graphics/thumbs/' . $image . '_xthumb.jpg" title="' . $final_array[ $image ] . '" />' . "\n";

		$output .= '<div class="db_from">';
		$output .= __( 'Enjoy a Free Wallpaper from', 'digitial-blasphemy-widget' );
		$output .= ' <a href="http://digitalblasphemy.com">';
		$output .= __( 'digitalblasphemy.com', 'digitial-blasphemy-widget' );
		$output .= '</a></div>' . "\n";

		$output .= '</div>' . "\n";

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


} // class Digital_Blasphemy_Widget

?>

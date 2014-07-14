<?php

/**
 * Adds Digital_Blasphemy_Widget widget.
 */
class Digital_Blasphemy_Widget extends WP_Widget {

	/**
	 * Make some vars
	 */
	private $allowed_types = NULL;


	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'digital-blasphemy-widget', // Base ID
			__( 'Digital Blasphemy', 'digital-blasphemy-widget' ), // Name
			array( 'description' => __( 'Renders a variety of content from Digital Blasphemy.', 'digital-blasphemy-widget' ), )
		);

		add_action( 'wp_head', array( $this, 'widget_css' ) );

		$this->allowed_types = array(
			'random_freebie' => 'One Random Freebie',
			'latest_freebie' => 'Latest Freebie',
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args	  Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$title  = apply_filters( 'widget_title', $instance['title'] );
		$output = '';

		// check for data type
		//if ( 'random_freebie' == wp_kses_post( $instance['db_type_option'] ) ) {
		if ( 'random_freebie' == $instance['db_type_option'] && array_key_exists( $instance['db_type_option'], $this->allowed_types ) ) {
			$output_object = new Digital_Blasphemy_Random_Freebie;
			$output = $output_object->render_random_freebie();
		} elseif ( 'latest_freebie' == $instance['db_type_option'] && array_key_exists( $instance['db_type_option'], $this->allowed_types ) ) {
			$output_object = new Digital_Blasphemy_Latest_Freebie;
			$output = $output_object->render_latest_freebie();
		}

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) )
		echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		echo wp_kses_post( $output );
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Front-end css for widget.
	 */
	public function widget_css() {

		$output = '';

		// make sure we actually have a widget
		if ( is_active_widget( false, false, $this->id_base, true ) ) {

			// don't show the styles if the filter has them off
			if ( ! apply_filters( 'digitalblasphemy-styles', true ) ) { return; }

			$output .= '<style type="text/css">' . "\n";

				$output .= '.db_title, .db_from { text-align: center; }' . "\n";
				$output .= '.db_title { font-weight: bold; }' . "\n";
				$output .= '.db_from { font-size: smaller; }' . "\n";
				$output .= '.widget_digital-blasphemy-widget img { max-width: 100%; }' . "\n";

			$output .= '</style>' . "\n";
		}

		print $output;

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
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'db_type_option' ) ); ?>"><?php _e( 'Choose Content Type' ); ?></label> 
			<select name="<?php echo esc_attr( $this->get_field_name( 'db_type_option' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'db_type_option' ) ); ?>" class="widefat">
				<?php
					foreach ( $this->allowed_types as $key => $option ) {
						echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '"' . esc_html( selected( $instance['db_type_option'], $key ) ) .  '>' . esc_html( $option ) .  '</option>';
					}
				?>
			</select>

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
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['db_type_option'] = wp_kses_post( $new_instance['db_type_option'] );

		return $instance;
	}

} // class Digital_Blasphemy_Widget

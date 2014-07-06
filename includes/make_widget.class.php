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
			'digitial-blasphemy-widget', // Base ID
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

		$random_freebie = new Digital_Blasphemy_Random_Freebie;

		$latest_freebie = new Digital_Blasphemy_Latest_Freebie;

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		//echo $latest_freebie->render_latest_freebie();
		echo $random_freebie->render_random_freebie();
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

		<p>
			<label for="<?php echo $this->get_field_id( 'db_type_option' ); ?>"><?php _e( 'Choose Content Type' ); ?></label> 
			<select name="<?php echo $this->get_field_id( 'db_type_option' ); ?>">
				<option value="random_freebie">One Random Freebie</option>
				<option value="latest_freebie">Latest Freebie</option>
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
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Digital_Blasphemy_Widget

?>

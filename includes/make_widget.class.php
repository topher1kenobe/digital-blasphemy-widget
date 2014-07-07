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
	 * @param array $args	  Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );

		// check for data type
		if ( 'random_freebie' == wp_kses_post( $instance['db_type_option'] ) ) {
			$output_object = new Digital_Blasphemy_Random_Freebie;
			$output = $output_object->render_random_freebie();
		} elseif ( 'latest_freebie' == wp_kses_post( $instance['db_type_option'] ) ) {
			$output_object = new Digital_Blasphemy_Latest_Freebie;
			$output = $output_object->render_latest_freebie();
		}

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		echo wp_kses_post( $output );
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
			<select name="<?php echo $this->get_field_name('db_type_option'); ?>" id="<?php echo $this->get_field_id('db_type_option'); ?>" class="widefat">
				<?php
				$options = array( 'random_freebie' => 'One Random Freebie', 'latest_freebie' => 'Latest Freebie' );
					foreach ($options as $key =>  $option) {
						echo '<option value="' . $key . '" id="' . $key . '"' . selected( $instance['db_type_option'], $key ) .  '>' . $option .  '</option>';
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

		$instance['db_type_option'] = wp_kses( $new_instance['db_type_option'] );

		return $instance;
	}

} // class Digital_Blasphemy_Widget

?>

<?php
/**
 * Class that handles conditional logic related to pages.
 *
 * @package WPCode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The WPCode_Conditional_Page class.
 */
class WPCode_Conditional_Page_Pro extends WPCode_Conditional_Page {

	/**
	 * Set the type options for the admin mainly.
	 *
	 * @return void
	 */
	public function load_type_options() {
		parent::load_type_options();

		$this->options['post_id'] = array(
			'label'           => __( 'Post/Page', 'wpcode-premium' ),
			'type'            => 'ajax',
			'options'         => 'wpcode_search_posts',
			'callback'        => array( $this, 'get_post_id' ),
			'labels_callback' => array( $this, 'get_posts_labels' ),
			'multiple'        => true,
			'operator_labels' => array(
				'='  => __( 'Is one of', 'wpcode-premium' ),
				'!=' => __( 'Is not one of', 'wpcode-premium' ),
			),
		);

		if ( is_admin() ) {
			if ( ! wpcode()->license->get() ) {
				$this->options['post_id']['upgrade'] = array(
					'title'  => __( 'Post specific rules are a Pro Feature', 'wpcode-premium' ),
					'text'   => __( 'Please add your license key in the Settings Panel to unlock all pro features.', 'wpcode-premium' ),
					'link'   => add_query_arg(
						array(
							'page' => 'wpcode-settings',
						),
						admin_url( 'admin.php' )
					),
					'button' => __( 'Add License Key Now', 'wpcode-premium' ),
				);
			}
		}
	}

	/**
	 * Get the post id.
	 *
	 * @return false|int
	 */
	public function get_post_id() {
		return get_the_ID();
	}

	/**
	 * Get the titles of the selected posts for this condition.
	 *
	 * @param [int] $values The post ids.
	 *
	 * @return array
	 */
	public function get_posts_labels( $values ) {
		$labels = array();
		foreach ( $values as $post_id ) {
			$labels[] = array(
				'value' => $post_id,
				'label' => get_the_title( $post_id ),
			);
		}

		return $labels;
	}
}

new WPCode_Conditional_Page_Pro();

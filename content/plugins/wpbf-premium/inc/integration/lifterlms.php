<?php
/**
 * LifterLMS integration.
 *
 * @package Page Builder Framework Premium Add-On
 * @subpackage Integration
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Remove LifterLMS post type from Blog Layouts post type array.
 *
 * @param array $post_types The post types
 *
 * @return array The updated post types
 */
function wpbf_lifterlms_remove_blog_layouts_post_type( $post_types ) {

	unset(
		$post_types['course'],
		$post_types['lesson'],
		$post_types['llms_quiz'],
		$post_types['llms_membership'],
		$post_types['llms_certificate'],
		$post_types['llms_my_certificate']
	);

	return $post_types;

}
add_filter( 'wpbf_blog_layouts_archive_array', 'wpbf_lifterlms_remove_blog_layouts_post_type' );

/**
 * Remove LifterLMS post type from Template Settings post type array.
 *
 * @param array $post_types The post types
 *
 * @return array The updated post types
 */
function wpbf_lifterlms_remove_template_settings_post_type( $post_types ) {

	unset(
		$post_types['llms_quiz'],
		$post_types['llms_certificate'],
		$post_types['llms_my_certificate']
	);

	return $post_types;

}
add_filter( 'wpbf_template_settings_post_type_array', 'wpbf_lifterlms_remove_template_settings_post_type' );

/**
 * Add LifterLMS hooks to custom sections.
 *
 * @param array $hooks The custom section hooks
 *
 * @return array The updated custom section hooks
 */
function wpbf_lifterlms_custom_section_hooks( $hooks ) {

	$custom_hooks = array(
		'LifterLMS Catalog' => array(
			'lifterlms_archive_description',
			'lifterlms_before_main_content',
			'lifterlms_after_main_content',
		),
		'LifterLMS Checkout Page' => array(
			'lifterlms_before_checkout_form',
			'lifterlms_after_checkout_form',
		),
		'LifterLMS Course Template' => array(
			'lifterlms_single_course_before_summary',
			'lifterlms_single_course_after_summary',
		),
		'LifterLMS Lesson Template' => array(
			'lifterlms_single_lesson_before_summary',
			'lifterlms_single_lesson_after_summary',
		),
		'LifterLMS Student Dashboard' => array(
			'lifterlms_before_student_dashboard_content',
			'lifterlms_student_dashboard_header',
			'lifterlms_student_dashboard_index',
		),
	);

	$hooks = array_merge( $hooks, $custom_hooks );

	return $hooks;

}
add_filter( 'wpbf_custom_section_hooks', 'wpbf_lifterlms_custom_section_hooks' );

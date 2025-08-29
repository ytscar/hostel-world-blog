<?php

function deepl_translate_comment_link( $args ) {
	$defaults = array(
		'ID'	=> false,
		'source_lang'	=> false,
		'target_lang' => DeepLConfiguration::getDefaultTargetLanguage(),
		'behaviour'	=> DeepLConfiguration::getMetaBoxDefaultBehaviour(),
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	$WP_Comment = get_comment( $args['ID'] );
	$strings_to_translate = array();

	foreach ( array( 'comment_content' ) as $key ) {
		$strings_to_translate[$key] = $WP_Comment->$key;
	}

	// shortcodes
	foreach( $strings_to_translate as $key => $string ) {
		preg_match_all( '#\[([a-z_0-9]+)]#m', $string, $matches );
		if( $matches ) {
			//plouf( $matches, $key );
			foreach( $matches[0] as $found ) {
				//echo "\n '$found' to '<x>$found</x>' ";
				$strings_to_translate[$key] = str_replace( $found, '<x>' . $found .'</x>', $strings_to_translate[$key] );

			}

		}

	}
	$strings_to_translate = apply_filters( 'deepl_translate_post_comment_link_strings', $strings_to_translate, $WP_Comment, $target_lang, $source_lang );

	$response = deepl_translate( $source_lang, $target_lang, $strings_to_translate );
	//plouf( $response );	plouf( $strings_to_translate );die('okok');


	$log = array('comment_ID'	=> $WP_Comment->comment_ID );
	$log = array_merge( $log, $response );
	$return = false;

	if ( $response['success'] ) {
		$post_comment_array = array(
			'comment_ID'	=> $WP_Comment->comment_ID
		);
		foreach ( array( 'comment_content' ) as $key ) {
			if ( isset( $response['translations'][$key] ) && !empty( $response['translations'][$key] ) ) {
				$translation = $response['translations'][$key];
				// shortcode
				$translation = preg_replace( '#<x>(.+?)<\/x>#', '\1', $translation );
				$post_comment_array[$key] = $translation;
			}
		}

		//plouf( $post_comment_array );		die('ok');
		$post_comment_array = apply_filters('deepl_translate_post_comment_link_translated_array', $post_comment_array, $strings_to_translate, $response, $WP_Comment );
		do_action('deepl_translate_before_post_comment_update', $post_comment_array, $strings_to_translate, $response, $WP_Comment );

		if (count( $post_comment_array ) > 1 ) {
			$updated = wp_update_comment( $post_comment_array );
			//var_dump( $updated);			plouf($post_comment_array, " post_comment array");		die('ok');
			$return = true;
			do_action('deepl_translate_post_comment_link_translation_success', $response, $WP_Comment );
		} else {
			$log[] = __('Nothing to update', 'wpdeepl' );
			wpdeepl_log( $log, 'errors');
			do_action('deepl_translate_post_comment_link_translation_error', $response, $WP_Comment );
		}
	} else {
		$log[] = __('Translation error', 'wpdeepl' );
		$log[] = json_encode( $response );
		do_action('deepl_translate_post_comment_link_translation_error', $response, $WP_Comment );
		wpdeepl_log( $log, 'errors');
	}
	do_action('deepl_translate_after_post_comment_update', $post_comment_array, $strings_to_translate, $response, $WP_Comment, $no_translation );
	do_action('deepl_translate_post_comment_link_after', $response, $strings_to_translate, $WP_Comment, $no_translation );
	return $return;
}
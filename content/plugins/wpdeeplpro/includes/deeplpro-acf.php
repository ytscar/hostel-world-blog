<?php

function deeplpro_get_acf_fields_for( $post_type = 'post' ) {

	$fields = array();
	$args = array(
		'post_type'	=> 'acf-field-group',
		'numberposts'	=> -1,
		'post_status'	=> 'any',

	);
	$posts = get_posts( $args );

	if( $posts ) foreach( $posts as $post ) {
		$rules = maybe_unserialize( $post->post_content );

		if( $rules ) foreach( $rules['location'] as $ruleset ) {
			foreach( $ruleset as $rule ) {
				if( $rule['param'] == $post_type ) {
					$fields[] = $post->post_name;
				}
				if( $rule['param'] == 'post_type' && $rule['value'] == $post_type ) {
					$fields[] = $post->post_name;
				}
			}
		}
	}
	return $fields;
}


function deeplpro_get_groups( ) {

	// restreindre à la la langue principale

	
	$fields = array();
	$args = array(
		'post_type'	=> 'acf-field-group',
		'numberposts'	=> -1,
		'post_status'	=> 'any',
	);
	$posts = get_posts( $args );


	$results = array();
	if( $posts ) foreach( $posts as $post ) {
		$block_slug = false;
		$is_block = false;
		$post_content = maybe_unserialize( $post->post_content );
		foreach( $post_content['location'] as $ruleset ) {
			foreach( $ruleset as $rule ) {
				if( isset( $rule['param'] ) && $rule['param'] == 'block' ) {
					$is_block = true;
					$block_slug = $rule['value'];
				}
			}
		}
		$results[$post->ID] = array(
			'post_name'		=> $post->post_name,
			'post_excerpt'	=> $post->post_excerpt,
			'post_title'	=> $post->post_title,
			'is_block'		=> $is_block,
			'block_slug'	=> $block_slug,
		);
	}

	return $results;
}
<?php

/**
 * Duplicates a post & its meta and returns the new duplicated Post ID.
 *
 * @param int $post_id The Post ID you want to clone.
 * @return int The duplicated Post ID.
 */
function deeplpro_duplicate_post($post_id, $target_lang ) {
    $title = get_the_title($post_id);
    $oldpost = get_post($post_id);
    
    $taxonomies = get_post_taxonomies($post_id);

    if( function_exists( 'pll_set_post_language' ) ) {
    	$pll_language = deeplpro_get_polylang_slug_for_language( $target_lang );
    	$pll_language_short = substr( $pll_language, 0, 2 );
	    $i18n_terms = wp_get_object_terms( $post_id, 'language');
	    if( $i18n_terms ) foreach( $i18n_terms as $i18n_term ) {
	    	$source_pll_term = get_term_by( 'slug', $i18n_term->slug, 'language' );
	    	$target_pll_term = get_term_by( 'slug', $pll_language_short, 'language' );
	    }
    }

    $post_array = array(
        'post_title' => $title,
        'post_name' => sanitize_title($title),
        'post_status' => 'draft',
        'post_type' => $oldpost->post_type,
        'post_content'  => $oldpost->post_content,
        'post_excerpt'  => $oldpost->post_excerpt,
    );

    $post_array = wp_slash( $post_array );
    $new_post_id = wp_insert_post($post_array);

    $data = get_post_custom($post_id);

    foreach ($data as $key => $values) {
        foreach ($values as $value) {
            //echo "\n $key, json ? ";             var_dump( is_array( json_decode( $value, true ) ));
            if( is_array( json_decode( $value ) ) ) {
                $array = json_decode( $value, true );
                $value = json_encode( $array, JSON_UNESCAPED_UNICODE);
            }
            add_post_meta($new_post_id, $key, maybe_unserialize($value));
        }
    }


    if ($taxonomies) {
        foreach ($taxonomies as $taxonomy) {

        	$existing_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids') );
        	if( function_exists( 'pll_get_term' ) ) {
        		foreach( $existing_terms as $i => $term_id ) {
        			$existing_terms[$i] = pll_get_term( $term_id, $pll_language_short );
        		}
        	}
            wp_set_object_terms(
                $new_post_id,
                $existing_terms,
                $taxonomy
            );
        }
    }

    if( function_exists( 'pll_set_post_language' ) ) {
    	pll_set_post_language( $new_post_id, $target_pll_term->slug );

        // existing ?
        $post_translations_terms = wp_get_object_terms( $post_id, 'post_translations' );
        if( count( $post_translations_terms ) ) {
            $post_translations = $post_translations_terms[0];
            $pll_link_array = maybe_unserialize( $post_translations->description );
            $group = $post_translations->slug;
            $pll_link_array[$target_pll_term->slug] = $new_post_id;
//            plouf( $pll_link_array, " array et on update le term $post_translations->term_id ");

            wp_update_term( $post_translations->term_id, 'post_translations', array( 'description' => maybe_serialize( $pll_link_array ) ) );
        }
        
        // not existing
        if( !$pll_link_array ) {
            $group = uniqid( 'pll_' );
        	$pll_link_array = array(
        		$source_pll_term->slug => $post_id,
        		$target_pll_term->slug => $new_post_id
        	);
            $post_translations = wp_insert_term( $group, 'post_translations', array( 'description' => maybe_serialize( $pll_link_array ) ) );
        	$inserted = wp_set_object_terms( $post_id, $post_translations['term_taxonomy_id'], 'post_translations' );
            //plouf( $post_translations, " nouvelle array et on crée le term " . $post_translations['term_taxonomy_id'] );
        }
    	
    	$inserted = wp_set_object_terms( $new_post_id, $post_translations->term_taxonomy_id, 'post_translations' );
        //plouf( $inserted, " relationship sur le nouveau $new_post_id ");        die('ok');
    }
    return $new_post_id;
}



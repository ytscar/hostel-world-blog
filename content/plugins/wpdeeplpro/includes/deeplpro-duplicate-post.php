<?php

/**
 * Duplicates a post & its meta and returns the new duplicated Post ID.
 *
 * @param int $post_id The Post ID you want to clone.
 * @return int The duplicated Post ID.
 */
function deeplpro_duplicate_post($old_post_id, $target_lang ) {

    $debug = true;
    $debug = false;

    $title = get_the_title($old_post_id);
    $oldpost = get_post($old_post_id);
    
    $taxonomies = get_post_taxonomies($old_post_id);

    if( function_exists( 'pll_set_post_language' ) ) {

        $pll_target_lang = $target_lang;
        
        $pll_language = deeplpro_get_polylang_slug_for_language( $pll_target_lang );
    	$pll_language_short = substr( $pll_language, 0, 2 );
        //echo " donc pll lang $pll_language";
	    $i18n_terms = wp_get_object_terms( $old_post_id, 'language');
        //plouf( $i18n_terms, " terms $old_post_id");
	    if( $i18n_terms ) foreach( $i18n_terms as $i18n_term ) {
	    	$source_pll_term = get_term_by( 'slug', $i18n_term->slug, 'language' );
	    	$target_pll_term = get_term_by( 'slug', $pll_language_short, 'language' );
	    }
    }

    if( $debug ) {
        plouf( $pll_language_short , "short");    plouf( $source_pll_term, "source pll term" );    plouf( $target_pll_term, "target pll term" ); plouf( $i18n_terms, "i18n terms");
    }


    $post_status = 'draft';
    if( DeepLProConfiguration::getBulkPublishOrDraft() == 'publish' ) {
        $post_status = 'publish';
    }


    $post_date = date('Y-m-d H:i:s');
    if( DeepLProConfiguration::getBulkTimeStampChoice() == 'same' ) {
        $post_date = get_the_date( 'Y-m-d H:i:s' , $old_post_id );
    }
    $post_array = array(
        'post_title' => $title,
        'post_name' => sanitize_title($title),
        'post_date' => $post_date,
        'post_status' => $post_status,
        'post_type' => $oldpost->post_type,
        'post_content'  => $oldpost->post_content,
        'post_excerpt'  => $oldpost->post_excerpt,
    );

    $post_array = wp_slash( $post_array );

    $new_post_id = wp_insert_post($post_array, true);

    $data = get_post_custom($old_post_id);

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

        	$existing_terms = wp_get_object_terms( $old_post_id, $taxonomy, array( 'fields' => 'ids') );
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
        $pll_link_array = array();
        
        // existing ?
        $post_translations_terms = wp_get_object_terms( $old_post_id, 'post_translations' );
        if( $debug ) plouf( $post_translations_terms, "existing" );

        /**
         * relations Polylang : 
         * dans _term_taxonomy
         * taxonomy = post_translations
         * description =
         * array (
              'en' => 14622,
              'fr' => 14348,
              'de' => 14870,
            )
        **/

        if( count( $post_translations_terms ) ) {
            $post_translations = $post_translations_terms[0];
            $pll_link_array = maybe_unserialize( $post_translations->description );
            $group = $post_translations->slug;

            // on ajoute le nouveau post à la nouvelle langue
            $pll_link_array[$target_pll_term->slug] = $new_post_id;
            wp_update_term( $post_translations->term_id, 'post_translations', array( 'description' => maybe_serialize( $pll_link_array ) ) );
            if( $debug ) plouf( $pll_link_array, " array update le term $post_translations->term_id ");

            $term_taxonomy_id = $post_translations->term_taxonomy_id;
        }
        
        // not existing
        if( !$pll_link_array ) {
            $term_slug = uniqid( 'pll_' );

        	$pll_link_array = array(
        		$source_pll_term->slug => $old_post_id,
        		$target_pll_term->slug => $new_post_id
        	);
            // on crée le terme groupe
            $post_translations = wp_insert_term( $term_slug, 'post_translations', array( 'description' => maybe_serialize( $pll_link_array ) ) );
            if( $debug ) plouf( $post_translations, " on a  créé le term $term_slug avec les traductions");
            if( $debug ) plouf( $pll_link_array, " traductions");
            $term_taxonomy_id = $post_translations['term_taxonomy_id'];
        }

        // on le lie avec l'ancien et le nouveau
        $inserted = wp_set_object_terms( $old_post_id, $term_taxonomy_id, 'post_translations' );
        if( $debug ) {
             echo " on lie l'ancien $old_post_id au term $term_taxonomy_id";
             var_dump( $inserted);
        }
        $new_inserted = wp_set_object_terms( $new_post_id, $term_taxonomy_id, 'post_translations' );
        if( $debug ) {
             echo " on lie le nouveau $new_post_id au term $term_taxonomy_id";
             var_dump( $new_inserted);
        }

    }
    if( $debug ) {
        plouf( $_POST );
        plouf( $pll_link_array,  " pll_link_array");
        plouf( $post_translations, " post translations, tt id = $term_taxonomy_id ");
        $term = get_term( $term_taxonomy_id, 'post_translations' );
        plouf( $term, " terme ????");
         die(" liens faits pour $new_post_id et $old_post_id car function exists " . function_exists('pll_set_post_language') );
    }

    return $new_post_id;
}



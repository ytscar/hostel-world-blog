<?php


add_filter( 'deepl_translate_post_link_strings', 'deeplpro_translate_post_link_strings', 10, 6 );
function deeplpro_translate_post_link_strings( $strings_to_translate, $WP_Post, $target_lang, $source_lang, $bulk, $bulk_action ) {

	$post_type = $WP_Post->post_type;

	// metas ?
	$meta_keys = DeepLProConfiguration::getMetaKeysToTranslateFor( $post_type );


	if( $meta_keys && is_array( $meta_keys) && count( $meta_keys ) ) {
		foreach( $meta_keys as $meta_key ) {
			$meta_value = get_post_meta( $WP_Post->ID, $meta_key, true );
			if( is_string( $meta_value ) && !empty( $meta_value ) ) {
				$strings_to_translate['meta_' . $meta_key] = $meta_value;
			}
		}
	}
	

	// ACF Featured
	// they are unicode encoded. A simple json decode / encode strips the unicode
	preg_match_all( '#<!-- wp:acf\/featured (.+?) \/-->#ism', $strings_to_translate['post_content'], $matches, PREG_SET_ORDER );
	foreach( $matches as $index => $match ) {
		$array = json_decode( $match[1], true );
		//$decoded = json_encode( $array );
		$strings_to_translate['acffeatured_' . $index . '_title'] = $array['data']['title'];
		$strings_to_translate['acffeatured_' . $index . '_text'] = $array['data']['text'];
		$strings_to_translate['post_content'] = str_replace( $match[0], "<x>\n" . $match[0] ."\n</x>", $strings_to_translate['post_content'] );
	}

 
 	// ACF Loops
 	//$metas = get_post_meta( $WP_Post->ID ); 	plouf( $metas ); die('ok');


	//plouf( $strings_to_translate , "zepiojrprzepijr");	die('ok');

	// test only acf
	///$strings_to_translate['post_content'] = preg_replace( '#<!-- wp:(?:heading|list-item|image|paragraph).*?<!--.*?-->#ism', '', $strings_to_translate['post_content'] );


	// custom_fields
	$custom_fields = DeepLProConfiguration::getCustomFieldsToTranslateFor( $post_type );
	//plouf( $custom_fields , " custom");
	if( $custom_fields && count( $custom_fields ) ) {
		foreach( $custom_fields as $meta_key ) {
			$meta_value = get_post_meta( $WP_Post->ID, $meta_key, true );
			if( is_string( $meta_value ) && !empty( $meta_value ) ) {
				$strings_to_translate['meta_' . $meta_key] = $meta_value;
			}
		}
	}

	if( DeepLProConfiguration::shouldWeTranslateSlug() ) {
		$slug = $WP_Post->post_title;
		$strings_to_translate['slug'] = $slug;
	}
	else {

	}

	$strings_to_translate['_notranslation'] = compact( 'source_lang', 'target_lang' );

	// terms
	$taxonomies_to_translate = DeepLProConfiguration::whatTaxonomiesShouldWeTranslateForPostType( $WP_Post->post_type );

	$to_translate = array();

	if( $taxonomies_to_translate ) foreach( $taxonomies_to_translate as $taxonomy ) {
		if( $taxonomy == 'language' ) {
			continue;
		}
		$terms = wp_get_post_terms( $WP_Post->ID, $taxonomy );
		//plouf( $terms, " pour $taxonomy");

		if( $terms ) foreach( $terms as $WP_Term ) {

			$already_translated = false;

			if( function_exists('pll_get_term' ) ) {
				
				if( $bulk && $bulk_action && $bulk_action == 'create' ) {
					$already_translated = true;
					// si bulk et polylang, les termes sont déjà traduits
				}
				else {
					$language = substr( $target_lang, 0, 2 );
					$translated_tt_id = pll_get_term( $WP_Term->term_taxonomy_id, $language );
					//plouf( $WP_Term, " translated by pl $language ? $translated_tt_id");
					if( $translated_tt_id) {
						$strings_to_translate['_notranslation']['terms'][$taxonomy][$WP_Term->term_taxonomy_id] = $translated_tt_id;
						$already_translated = true;
					}

				}
			}

			if( !$already_translated ) {
				//$strings_to_translate['_notranslation'][$taxonomy][] = $WP_Term->parent;
				// keeping the hierarchical information
				// TODO : repdnre la liste complète des terme s?
				$to_translate['terms_' . $taxonomy][] = $WP_Term->name;

			}
		}
	}

	if( count( $to_translate ) ) foreach( $to_translate as $string => $array ) {
		$strings_to_translate[$string] = implode('###', $array );
	}
//	plouf( $to_translate );
//plouf( $strings_to_translate, "pro" ); die('ok');

	
	return $strings_to_translate;
}

add_filter('deepl_translate_post_link_translated_array', 'deeplpro_translated_post_array', 10, 7 );
function deeplpro_translated_post_array( $post_array, $strings_to_translate, $response, $WP_Post, $no_translation, $bulk, $bulk_action ) {
	//plouf( $response);die('okozerk');

	// ACF Featured
	if( isset( $response['translations']['acffeatured_0_title']  ) ) {
		preg_match_all( '#<x>\s*?<!-- wp:acf\/featured (.+?) \/-->\s*?<\/x>#ism', $response['translations']['post_content'], $matches, PREG_SET_ORDER );

		if( $matches ) foreach( $matches as $index => $match ) {
			$array = json_decode( $match[1], true );
			$title = $response['translations']['acffeatured_' . $index . '_title'];
			$array['data']['title'] = $title;
			$text = $response['translations']['acffeatured_' . $index . '_text'];
			$array['data']['text'] = $text;
			//plouf( $array," pour index $index");
			//$array = wp_slash( $array );
			$decoded = json_encode( $array /* , JSON_UNESCAPED_UNICODE */);
			//echo "\n 1 $decoded";
			//$decoded = str_replace( 'acf\/featured', 'acf/featured', $decoded );
			//decoded = str_replace('\r\n', '\n', $decoded );
			$decoded = str_replace('acf\/featured', 'acf/featured', $decoded );
			//echo "\n 1 $decoded";
			$decoded = str_replace('\r\n', '<br />', $decoded );
			$decoded = str_replace('\n', '<br />', $decoded );
			//echo "\n 3 $decoded";


			$replace = $match[0];
			$replace = str_replace('<x>', '', $replace );
			$replace = str_replace('</x>', '', $replace );
			$replace = trim( $replace );
			$replace = str_replace( $match[1], $decoded, $replace );

			//echo "\n on remplace \n " . $match[0] . "\n par \n" . $replace ."\n";

			$post_array['post_content'] = str_replace( $match[0], $replace, $post_array['post_content'] );
			
		}
	}

//	plouf( $post_array );die('ok');



	//plouf( $response['translations'], "translations");
	if( isset( $response['translations']['slug'] ) ) {
		$post_array['post_name'] = sanitize_title_with_dashes( $response['translations']['slug'] );
	}
	//plouf( $post_array);	die('ozaerkor');
	return $post_array;
}

add_action( 'deepl_translate_before_post_update', 'deeplpro_post_translated_before_update', 10, 7 );
function deeplpro_post_translated_before_update($post_array, $strings_to_translate, $response, $WP_Post, $no_translation, $bulk, $bulk_action  ) {
/*
	plouf( $post_array, " reponse");
	plouf( $strings_to_translate);
	plouf( $response, "response");

	if( isset( $response['translations'] ) ) foreach( $response['translations'] as $key => $translation ) {
		if( substr( $key, 0, 5) == 'meta_' ) {
			$meta_key = substr( $key, 5 );
	//		update_post_meta( $WP_Post->ID, $meta_key, $translation );
			echo "\n maj $WP_Post->ID, $meta_key = " . $translation;
		}
	}
	die('ok');
*/
	//plouf( $response );	plouf( $no_translation);
	//die( __FUNCTION__ . 'okoekroerko');

}

add_action( 'deepl_translate_after_post_update', 'deeplpro_translate_post_link_after', 10, 7 );
function deeplpro_translate_post_link_after( $post_array, $strings_to_translate, $response, $WP_Post, $no_translation ) {

	if( isset( $response['translations'] ) ) foreach( $response['translations'] as $key => $translation ) {
		if( substr( $key, 0, 5) == 'meta_' ) {
			$meta_key = substr( $key, 5 );
			update_post_meta( $WP_Post->ID, $meta_key, $translation );
			//echo "\n maj $WP_Post->ID, $meta_key = " . $translation;
		}
		elseif( substr( $key, 0, 6 ) == 'terms_' ) {
			$taxonomy = substr( $key, 6 );
			$translated_terms = explode('###', $translation );
			$tt_terms = array();
			if( $translated_terms ) foreach( $translated_terms as $term_name ) {
				$WP_Term = get_term_by( 'name', $term_name, $taxonomy );
				if( $WP_Term ) {
					$tt_terms[] = $WP_Term->term_taxonomy_id;
				}
				else {
					$wp_term = wp_create_term( $term_name, $taxonomy );
					$tt_terms[] = $wp_term['term_taxonomy_id'];
				}
			}
			if( count( $tt_terms ) ) {
				$append = false;
				wp_set_post_terms( $WP_Post->ID, $tt_terms, $taxonomy, $append );
			}
		}
	}

	if( DeepLProConfiguration::usingPolylang() ) {
		$target_language = $no_translation['target_lang'];
		$locales_to_tt_id = deeplpro_get_polylang_languages_tt_id();
		if( isset( $locales_to_tt_id[$target_language] ) ) {
			wp_set_post_terms( $WP_Post->ID, array( $locales_to_tt_id[$target_language] ), 'language' );
		}
	}

	if( count( $no_translation ) ) {
		//plouf( $no_translation, " pas traduit") ;		die('okzerokr');
		if( isset( $no_translation['terms'] ) ) foreach( $no_translation['terms'] as $taxonomy => $terms ) {
			$tt_terms = array();
			foreach( $terms as $source_tt_id => $translation_tt_id ) {
				$tt_terms[] = $translation_tt_id;
			}
			if( count( $tt_terms ) ) {
				$append = true;
				wp_set_post_terms( $WP_Post->ID, $tt_terms, $taxonomy, $append );
			}
		}
	}


}

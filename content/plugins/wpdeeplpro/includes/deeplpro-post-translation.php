<?php


add_filter('deepl_translate_DeepLApiTranslate_before', 'deeplpro_maybe_using_glossary', 10, 1 );
function deeplpro_maybe_using_glossary( $DeepLApiTranslate ) {


	$request = $DeepLApiTranslate->getRequest();
	$source = isset( $request['source_lang']) ? substr( $request['source_lang'], 0 , 2 ) : false;
	$target = substr( $request['target_lang'], 0, 2 );

	$glossary_id = DeepLProConfiguration::getActiveGlossary( $source, $target );
	if( $glossary_id && $glossary_id != 0 )
		$DeepLApiTranslate->setGlossary( $glossary_id );

	return $DeepLApiTranslate;
}
/**
 * This one is called before translations
 * */
add_filter( 'deepl_translate_post_link_strings', 'deeplpro_translate_post_link_strings', 10, 6 );
function deeplpro_translate_post_link_strings( $strings_to_translate, $WP_Post, $target_lang, $source_lang, $bulk = false, $bulk_action = false) {

	$post_type = $WP_Post->post_type;
	$acf_terms = array();
	$acf_metas = array();

	$debug = true;
	$debug = false;

	// metas ?
	$meta_keys = DeepLProConfiguration::getMetaKeysToTranslateFor( $post_type );

	if( WPDEEPLPRO_DEBUG ) {
		//plouf( get_post_meta( $WP_Post->ID ), " metas");
	}

	if( $debug || WPDEEPLPRO_DEBUG ) plouf( $meta_keys, " metas to translate");


	if( $meta_keys && is_array( $meta_keys) && count( $meta_keys ) ) {
		foreach( $meta_keys as $meta_key ) {
			$meta_value = get_post_meta( $WP_Post->ID, $meta_key, true );

			if( !$meta_value ) 
				continue;

			if( $debug || WPDEEPLPRO_DEBUG ) {
				plouf( $meta_value, " pour $meta_key");
			}

			$is_acf_meta = false;
			$alt_value = get_post_meta( $WP_Post->ID, '_' . $meta_key, true );
			if( substr( $alt_value , 0, 6) == 'field_' ) {
				$is_acf_meta = true;
			}
			$is_acf_term = false;

			if( $is_acf_term ) {
				if( $debug || WPDEEPLPRO_DEBUG ) echo "\n is acf terms donc adding to acf terms $meta_key = " . $meta_value;
				$acf_terms[$meta_key] = $meta_value;
			}
			elseif( is_string( $meta_value ) && !empty( $meta_value ) ) {
				if( $debug || WPDEEPLPRO_DEBUG ) echo "\n adding $meta_key = " . $meta_value;
				$strings_to_translate['meta_' . $meta_key] = $meta_value;
			}
			else {
				if( $debug || WPDEEPLPRO_DEBUG ) plouf( $meta_value, "cas 1 pour $meta_key");
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
 	//$metas = get_post_meta( $WP_Post->ID ); 	plouf( $metas ); die('6z4ea64e68az4e84a6e4ok');





	// custom_fields
	$custom_fields = DeepLProConfiguration::getACFFieldsToTranslate();

	if( $debug) plouf( $custom_fields, __FUNCTION__ . " custom fields");


	//plouf( $field_objects );

	//if( WPDEEPLPRO_DEBUG ) plouf( $custom_fields, "custom fields to translate ");
	//if( WPDEEPLPRO_DEBUG ) plouf( get_post_meta( $WP_Post->ID) , "metas");
	if( $custom_fields && count( $custom_fields['fields'] ) ) {
		foreach( $custom_fields['fields'] as $meta_key ) {

			$real_meta_key = $meta_key;
			$explode = explode('#', $meta_key );
			if( count( $explode ) > 1 ) {
				$real_meta_key = array_shift( $explode );
				$regex = implode('\d+', $explode );
				$metas = get_post_meta( $WP_Post->ID );
				foreach( $metas as $key => $meta_values ) {
					if( substr( $key, 0, 1 ) == '_' ) 
						continue;
					if( preg_match( '#' . $regex . '#', $key ) ) {
						$meta_key = $key;
						foreach( $meta_values as $meta_value ) {
							if( is_string( $meta_value ) && !empty( $meta_value ) )  {
								if( WPDEEPLPRO_DEBUG ) echo "\n 2 adding $meta_key = " . $meta_value;
								$strings_to_translate['meta_' . $meta_key] = $meta_value;
							}
							else {
								if( WPDEEPLPRO_DEBUG ) plouf( $meta_value, "cas 2 pour $meta_key");
							}
						}
					}
				}
				//plouf( $metas, "regex");die('okzeijrzjr');
			}
			else {
				$meta_value = get_post_meta( $WP_Post->ID, $meta_key, true );
				if( is_string( $meta_value ) && !empty( $meta_value ) ) {
					if( WPDEEPLPRO_DEBUG ) echo "\n 2 adding $meta_key = " . $meta_value;
					$strings_to_translate['meta_' . $meta_key] = $meta_value;
				}
				else {
					if( WPDEEPLPRO_DEBUG ) plouf( $meta_value, "cas 3 pour $meta_key");
				}
			}
			
		}
	}

	// ACF Blocks
	// test only acf
	///$strings_to_translate['post_content'] = preg_replace( '#<!-- wp:(?:heading|list-item|image|paragraph).*?<!--.*?-->#ism', '', $strings_to_translate['post_content'] );
/*	if( $custom_fields && count( $custom_fields['blocks'] ) ) {
		foreach( $custom_fields['blocks'] as $meta_key ) {
			$real_meta_key = $meta_key;
			$explode = explode('#', $meta_key );
			$regex = '#<!-- wp:' . str_replace('/', '\/', $explode[0] ) . ' (.+?) \/-->#ism';
			preg_match_all( $regex, $strings_to_translate['post_content'], $matches, PREG_SET_ORDER );

			foreach( $matches as $index => $match ) {
				$array = json_decode( $match[1], true );
				$decoded = json_encode( $array );

				$strings_to_translate['acfblock_' . $explode[0] . '_' . $index . '_' . $explode[1]] = $array['data'][$explode[1]];
				$strings_to_translate['post_content'] = str_replace( $match[0], "<x>\n" . $match[0] ."\n</x>", $strings_to_translate['post_content'] );
			}
		}
	}
*/
	if( DeepLProConfiguration::shouldWeTranslateSlug() ) {
		$slug = $WP_Post->post_name;
		$strings_to_translate['slug'] = str_replace('-', ' ', $slug);
	}
	else {

	}
//plouf( $custom_fields, " custom");
	if( $custom_fields && count( $custom_fields['blocks'] ) ) {


		preg_match_all('#<!-- wp:acf\/(.+?) ({.+?}) \/-->#ism', $WP_Post->post_content, $matches, PREG_SET_ORDER );

		preg_match_all('#<!-- wp:custom\/(.+?) ({.+?}) \/-->#ism', $WP_Post->post_content, $matches2, PREG_SET_ORDER );
		$matches = $matches + $matches2;
		//plouf( $matches , " matches");


		$acf_strings = array();
		if( $matches ) {
			foreach( $matches as $match ) {

				//$strings_to_translate['post_content'] = str_replace( $match[0], '<x>' . $match[0] .'</x>', $strings_to_translate['post_content'] );
				$real_block_name = $match[1];
				$json = $match[2];
				$array = json_decode( $json, true );
				
				foreach( $custom_fields['blocks'] as $block_item ) {
					$explode = explode('#', $block_item );
					$search = str_replace('acf/', '', $explode[0] );
					if( $search == $real_block_name ) {
						//plouf( $array, " array");					plouf( $explode, " explode $search vs $real_block_name");
						if( count( $explode ) == 2 ) {
							$acf_strings[$block_item] = $array['data'][$explode[1]];
						}
						elseif( count( $explode) == 3 ) {
							$i = 0;
							while( isset( $array['data'][$explode[1] .'_' . $i .'_' . $explode[2]] ) ) {
								$strings_to_translate['acf_blocks_' . $explode[0] . "#" . $explode[1] .'_' . $i . '_' . $explode[2]] = $array['data'][$explode[1] .'_' . $i .'_' . $explode[2]];
								$i++;
							}
						}

					}
				}
				/*
				plouf( $array, $match[1] );
				foreach( $array['data'] as $key => $value ) {
					if( substr( $key, 0, 1 ) == '_' ) {
						$real_key = substr( $key, 1 );
						//echo "\n $key / $real_key ";
						if( !empty( $array['data'][$real_key] ) )
							$acf_strings[$value] = $array['data'][$real_key];
					}
				}*/
				
			}
			
			if( $acf_strings ) foreach( $acf_strings  as $key => $value ) {
				if( !empty( $value ) )
					$strings_to_translate['acf_blocks_' . $key ] = $value;
			}
		}
	}
	//plouf( $strings_to_translate);	die('ozkerozekr');

	$strings_to_translate['_notranslation'] = compact( 'source_lang', 'target_lang', 'acf_terms' );

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
				if( !empty( $WP_Term->name ) )
					$to_translate['terms_' . $taxonomy][] = $WP_Term->name;

			}
		}
	}

	if( count( $to_translate ) ) foreach( $to_translate as $string => $array ) {
		$strings_to_translate[$string] = implode('###', $array );
	}
//	plouf( $to_translate );
//plouf( $strings_to_translate, "pro" ); die('zae6z4e6z4e4az86eok');

	//	die('oke8a6ze4a4e64a6e468az4');

	if( WPDEEPLPRO_DEBUG ) {
		plouf( $strings_to_translate, " to translate");
	}

	if( $debug ) {
		plouf( $strings_to_translate, __FUNCTION__ . "to transalte");
		die('pzoekrzeopro');
	}

	return $strings_to_translate;
}


/** 
 * And that's where you get the translations done by DeepL and can do things
 * */
add_filter('deepl_translate_post_link_translated_array', 'deeplpro_translated_post_array', 10, 7 );
function deeplpro_translated_post_array( $post_array, $strings_to_translate, $response, $WP_Post, $no_translation, $bulk, $bulk_action ) {

	//plouf( $post_array,"  post array en debut de " . __FUNCTION__ );

	if(  WPDEEPLPRO_DEBUG ) {
		plouf( $post_array, " post array");
		plouf( $response, " response");
		die('ozekozkrozkr');
	}

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

	

	$custom_fields = DeepLProConfiguration::getACFFieldsToTranslate();

	// ACF blocks
	if( count( $custom_fields['blocks'] ) ) {

		preg_match_all('#<!-- wp:acf\/(.+?) ({.+?}) \/-->#ism', $WP_Post->post_content, $matches, PREG_SET_ORDER );
		preg_match_all('#<!-- wp:custom\/(.+?) ({.+?}) \/-->#ism', $WP_Post->post_content, $matches2, PREG_SET_ORDER );
		$matches = $matches + $matches2;


		$acf_strings = array();
		if( $matches ) {

			foreach( $matches as $match ) {

				$real_block_name = $match[1];
				$json = $match[2];
				$array = json_decode( $json, true );
				
				foreach( $custom_fields['blocks'] as $block_item ) {
					$explode = explode('#', $block_item );
					
					$search = str_replace('acf/', '', $explode[0] );
					if( $search == $real_block_name ) {
						if( count( $explode ) == 2 ) {
							$real_key = $explode[1];
							$array['data'][$real_key] = $response['translations']['acf_blocks_'. $block_item ];
						}
						elseif( count( $explode) == 3 ) {
							$i = 0;
							$key = 'acf_blocks_' . $explode[0] .'#' . $explode[1] .'_' . $i .'_' . $explode[2];
							while( isset( $response['translations'][$key] ) ) {

								$field_key = $explode[1] . '_' . $i .'_' . $explode[2];
								$array['data'][$field_key] = $response['translations'][$key];
								$i++;
								$key = 'acf_blocks_' . $explode[0] .'#' . $explode[1] .'_' . $i .'_' . $explode[2];
								//if( $i > 20 ) break;
							}

						}

					}
				}

				/*$json = $match[2];
				$array = json_decode( $json, true );
				foreach( $array['data'] as $key => $value ) {
					if( substr( $key, 0, 1 ) == '_' ) {
						$real_key = substr( $key, 1 );
						//echo "\n keys $key / $real_key";
						//plouf( $array['data'], $key );
						
						$acf_field_key = $array['data'][$key];
						if( isset( $response['translations']['acf_blocks_'. $acf_field_key ] ) ) {
							$array['data'][$real_key] = $response['translations']['acf_blocks_'. $acf_field_key ];
						}
					}
				}*/
				$json = json_encode( $array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE  );

				$replace = str_replace( $match[2], $json, $match[0] );
				//$replace = deeplpro_unicode_decode( $replace );
				$post_array['post_content'] = str_replace( $match[0], $replace, $post_array['post_content'] );
				
			}
		}


		//echo " post content = " .$post_array['post_content'];		die('ozejrizjr');


		$acf_strings = array();
		if( $matches ) {

			foreach( $matches as $match ) {
				$json = $match[2];
				$array = json_decode( $json, true );
				foreach( $array['data'] as $key => $value ) {
					if( substr( $key, 0, 1 ) == '_' ) {
						$real_key = substr( $key, 1 );
						//echo "\n keys $key / $real_key";
						//plouf( $array['data'], $key );
						
						$acf_field_key = $array['data'][$key];
						if( isset( $response['translations']['acf_blocks_'. $acf_field_key ] ) ) {
							$array['data'][$real_key] = $response['translations']['acf_blocks_'. $acf_field_key ];
						}
					}
				}
				$json = json_encode( $array );
				$replace = str_replace( $match[2], $json, $match[0] );
				$post_array['post_content'] = str_replace( $match[0], $replace, $post_array['post_content'] );
				
			}
		}



	}

	//plouf( $response['translations'], "translations");
	if( isset( $response['translations']['slug'] ) ) {
		$post_array['post_name'] = sanitize_title_with_dashes( $response['translations']['slug'] );
		$post_array['post_name'] = str_replace('---', '-', $post_array['post_name'] );
		$post_array['post_name'] = str_replace('---', '-', $post_array['post_name'] );
	}
	//plouf( $post_array, "response"); plouf( $strings_to_translate, " envoi");	
	//die('ozaerkor');

	//plouf( $post_array,"  post array en fin de " . __FUNCTION__ );


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
	die('6z4e646az4e6ok');
*/
	//plouf( $response );	plouf( $no_translation);
	//die( __FUNCTION__ . 'okoekroerko');

}

add_action( 'deepl_translate_after_post_update', 'deeplpro_translate_post_link_after', 10, 7 );
function deeplpro_translate_post_link_after( $post_array, $strings_to_translate, $response, $WP_Post, $no_translation ) {

	$acf_values = array();
	if( is_array( $response ) && isset( $response['translations'] ) ) foreach( $response['translations'] as $key => $translation ) {

		if( substr( $key, 0, 5) == 'meta_' ) {
			$meta_key = substr( $key, 5 );

			update_post_meta( $WP_Post->ID, $meta_key, $translation );
			/*
			$explode = explode('#', $meta_key );
			if( count( $explode ) == 1 ) {
				//echo "\n expldoe 1";
				update_post_meta( $WP_Post->ID, $meta_key, $translation );
			}
			else {
				$real_meta_key = array_shift( $explode );
				if( !isset( $acf_values[$real_meta_key] ) ) {
					$field_objects = get_field_objects( $WP_Post->ID );
					$acf_field = $field_objects[$real_meta_key];
					$acf_values[$real_meta_key] = get_field( $acf_field['name'], $WP_Post->ID );
					//plouf( $acf_values , "values avant");

				}
				///plouf( $explode , " key $key  translation = $translation");
				if( count( $explode ) == 1 ) {
					$meta_key = implode('_', array( $real_meta_key, 0, $explode[0] ) );
					if( WPDEEPLPRO_DEBUG ) echo "\n update $meta_key = $translation";
					update_post_meta( $WP_Post->ID, $meta_key, $translation );
					$acf_values[$real_meta_key][0][$explode[0]] = $translation;
				}
				elseif( count( $explode ) == 2 ) {
					$meta_key = implode('_', array( $real_meta_key, 0, $explode[0], 0, $explode[1] ) );
					if( WPDEEPLPRO_DEBUG ) echo "\n update $meta_key = $translation";
					update_post_meta( $WP_Post->ID, $meta_key, $translation );
					$acf_values[$real_meta_key][0][$explode[0]][0][$explode[1]] = $translation;
				}
				elseif( count( $explode == 3 ) ) {
					$meta_key = implode('_', array( $real_meta_key, 0, $explode[0], 0, $explode[1], 0, $explode[2] ) );
					if( WPDEEPLPRO_DEBUG ) echo "\n update $meta_key = $translation";
					update_post_meta( $WP_Post->ID, $meta_key, $translation );
					$acf_values[$real_meta_key][0][$explode[0]][0][$explode[1]][0][$explode[2]] = $translation;
				}
				
			}*/
		}

		if( substr( $key, 0, 6 ) == 'terms_' ) {
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
					if( $wp_term ) {
						$tt_terms[] = $wp_term['term_taxonomy_id'];
					}
					else {
						wpdeepl_log( array('unable to create term', $term_name, $taxonomy, __FUNCTION__ ), 'errors');
					}
				}
			}
			if( count( $tt_terms ) ) {
				$append = false;
				wp_set_post_terms( $WP_Post->ID, $tt_terms, $taxonomy, $append );
			}
		}
	}


	if( count( $acf_values ) ) {
		foreach( $acf_values as $acf_key => $meta_value ) {
			//$existing = maybe_unserialize( get_post_meta( $WP_Post->ID, $acf_key, true ) );
			//plouf( $existing, "existing pour '$acf_key' " );
			//if( WPDEEPLPRO_DEBUG ) plouf( $meta_value, "nouvelle $acf_key" );
		//	update_post_meta( $WP_Post->ID, $acf_key, $meta_value );
		}
	}


	/*	plouf( $acf_values, "values après");
	plouf( $response );
	plouf( $field_objects, " objets");
	die('ok');
*/

	if( DeepLProConfiguration::usingPolylang() ) {
		$target_language = $no_translation['target_lang'];
		$locales_to_tt_id = deeplpro_get_polylang_languages_tt_id();
		if( isset( $locales_to_tt_id[$target_language] ) ) {
			wp_set_post_terms( $WP_Post->ID, array( $locales_to_tt_id[$target_language] ), 'language' );
		}
	}

	if( count( $no_translation ) ) {
		//plouf( $no_translation, " pas traduit") ;		die('okzerokr');

		// 20241009 gestion des relations ACF
		if( isset( $no_translation['acf_terms'] ) && count( $no_translation['acf_terms'] ) ) foreach( $no_translation['acf_terms'] as $key => $value ) {
			$translations = wp_get_object_terms( $value, 'post_translations' );
			if( isset( $translations[0] ) && isset( $translations[0]->description ) ) {
				$translations = maybe_unserialize( $translations[0]->description );
			}
			$target_lang_for_acf_metas = strtolower ( substr( $no_translation['target_lang'], 0, 2 ) );
			if( isset( $translations[$target_lang_for_acf_metas] ) ) {
				update_post_meta( $WP_Post->ID, $key, $translations[$target_lang_for_acf_metas]  );
			}
		}
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

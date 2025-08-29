<?php

function deeplpro_get_post_language( $post_id ) {
	if( DeepLProConfiguration::usingPolylang() ) {
		return pll_get_post_language( $post_id );
	}
}


function deeplpro_unicode_decode( $string ) {
	preg_replace_callback(
		'/\\\\u([0-9a-fA-F]{4})/', 
		function ($match) {
	    	return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
		}, 
		$string 
	);
	return $string;
}


function deeplpro_get_possible_taxonomies_for( $post_type ) {
	global $wpdb;
//	$sql = "SELECT t.term_taxonomy "
	return array();
}

// 20191203 adding 6 hours cache on a consuming query
function deeplpro_getAllPossiblePostMeta( $post_types = array(), $short_list = true ) {
	if( $post_types && !is_array( $post_types ) ) {
		$post_types = array( $post_types );
	}

	$possible_meta_keys = array();

	$transient_key = 'wpdeepl_cache_all_post_meta_keys';
	//delete_transient( $transient_key );
	$all_meta_keys = get_transient( $transient_key );
	if( !$all_meta_keys ) {
		$all_meta_keys = array();
		global $wpdb;
		$sql = "SELECT p.post_type, pm.meta_key FROM $wpdb->posts p  
		JOIN 	$wpdb->postmeta pm ON pm.post_id = p.ID 
		GROUP BY p.post_type, pm.meta_key";
		$results = $wpdb->get_results( $sql, ARRAY_A );
		if( $results ) foreach( $results as $result ) {
			if( substr( $result['meta_key'], 0, 7 ) == '_oembed' ) {
				continue;
			}
			if( !isset( $all_meta_keys[$result['post_type']] ) )
				$all_meta_keys[$result['post_type']] = array();
			if( substr( $result['meta_key'], 0, 1 ) != '_' ) {
				$all_meta_keys[$result['post_type']][] = $result['meta_key'];
///				plouf( $result, "adding");
			}
			else {
			}
		}
		set_transient( $transient_key, $all_meta_keys, 24*3600 );
	}

	$relevant_meta_keys = array();


	if( $all_meta_keys ) {
		if( count( $post_types ) ) {
			foreach( $post_types as $post_type ) {
				if( isset( $all_meta_keys[$post_type] ) )
					$relevant_meta_keys = array_merge( $relevant_meta_keys, $all_meta_keys[$post_type] );
			}
		}
		else {
			foreach( $all_meta_keys as $post_type => $meta_keys ) {
				$relevant_meta_keys = array_merge( $relevant_meta_keys, $meta_keys );
			}
		}
	}
	$relevant_meta_keys = array_unique( $relevant_meta_keys );
	

	$remove_those_keys = array(  '_yoast_wpseo_estimated-reading-time-minutes', '_yoast_wpseo_wordproof_timestamp', '_edit_last', '_edit_lock', '_wp_page_template', '_yoast_wpseo_content_score', '_thumbnail_id','_children', '_manage_stock', '_product_attributes', '_product_image_gallery', '_product_url', '_product_version', '_regular_price', '_sale_price', '_sold_individually', '_stock_status', '_stock', '_virtual', '_wc_average_rating', '_wc_review_count', '_wp_old_slug', '_wpcom_is_markdown', '_button_text',

	);
	foreach( $remove_those_keys as $key ) {
		if (array_search( $key, $relevant_meta_keys ) ) {
			//echo "\n removing $key";
			unset( $relevant_meta_keys[array_search( $key, $relevant_meta_keys )] );

		}
	}

	//plouf($meta_keys, "FULL");
	$find_and_remove_extra_keys = array( 'billing', 'discount',  'recorded', 'download', 'date', 'tax', 'address', 'currency', 'payment', 'agent', 'company', 'city', 'state', 'hash', 'postcode', 'country', 'total', 'timestamp', 'price', 'sku', 'sold', 'stock'
	); 
	$find_and_remove_extra_keys = array();

	//var_dump( $short_list );
	if($short_list ) {
		foreach( $relevant_meta_keys as $i => $meta_key ) {
			foreach( $find_and_remove_extra_keys as $string ) {
				if( stripos( $meta_key, $string ) !== false ) {
					unset( $relevant_meta_keys[$i] );

				}
			}

		}
	}

	$relevant_meta_keys = array_values( $relevant_meta_keys );
	$relevant_meta_keys = array_combine( $relevant_meta_keys, $relevant_meta_keys );

	if ( 
		is_plugin_active( 'wordpress-seo/wp-seo.php' ) 
		|| 
		is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' )
	) {
		// Yoast tags ' %%title%% %%page%% %%sep%% %%sitename%% je veux voir cette traduction'
		//  don't get translated => '%%title%% %%page%% %%sep%% %%sitename%% I want to see this translation'
		$relevant_meta_keys['_yoast_wpseo_metadesc'] = __('Yoast meta description', 'deepl');
		$relevant_meta_keys['_yoast_wpseo_title'] = __('Yoast meta title', 'deepl');
		$relevant_meta_keys['_yoast_wpseo_focuskw'] = __('Yoast focus keyword', 'deepl');
		
	}
	if( is_plugin_active( 'seo-by-rank-math/rank-math.php' ) ) {
		$relevant_meta_keys['rank_math_focus_keyword'] = __('Rank Math focus keyword', 'ekan' );
	}

	if( is_plugin_active('wp-seopress/seopress.php') ) {
		$relevant_meta_keys ['_seopress_titles_title'] = __('SEOPress title', 'ekan' );
		$relevant_meta_keys ['_seopress_titles_desc'] = __('SEOPress description', 'ekan' );
		$relevant_meta_keys ['_seopress_analysis_target_kw'] = __('SEOPress keywords', 'ekan' );
	}

	
	return $relevant_meta_keys;
}


function deeplpro_get_option_like( $like ) {
	global $wpdb;
	$like = sanitize_text_field( $like );
	$sql = "SELECT option_name, option_value 
	FROM  	$wpdb->options 
	WHERE 	option_name LIKE '" . $like ."'";
	$results = $wpdb->get_results( $sql, ARRAY_A );
	return $results;
}
<?php

add_filter( 'deepl_metabox_post_types', 'deeplpro_metabox_post_types', 10, 1 );
function deeplpro_metabox_post_types( $post_types ) {
	$wp_post_types = get_post_types( array( 'show_ui' => true ), 'objects' );
	if ( $wp_post_types ) foreach ( $wp_post_types as $post_type => $WP_Post_Type ) {
		if(!isset( $post_types[$post_type] ) ) {
			$post_types[$post_type] = $WP_Post_Type->label;
		}
	}
	unset( $post_types['shop_order'] );
	return $post_types;
}



add_filter( 'deepl_admin_configuration', 'deeplpro_admin_configuration', 30, 1 );
function deeplpro_admin_configuration( $settings ) {

	
	//plouf($settings);	die('ok');

	/*foreach( $settings['integration']['sections']['metabox']['fields'] as $i => $field ) {
		if( $field['id'] == 'metabox_post_types' ) {
			$settings['integration']['sections']['metabox']['fields'][$i]['options'] = $post_types;
			$settings['integration']['sections']['metabox']['fields'][$i]['css'] = 'height: '. count( $post_types ) *1.5 .'rem;';
		}
	}*/



	$pro_settings = array(
		'title'			=> __('Pro settings', 'wpdeepl'),
		'sections' 	=> array()
	);


	$pro_settings['sections']['keypress'] =array(
		'title'	=> __('License', 'wpdeepl' ),
		'fields'	=> array(
			array(
				'id'		=> KeyPressWPDeepLPro::OPTIONKEY_LICENSE,
                'title'     => __('Clef d\'activation et de mises à jour', 'ecopart'),
                'class'		=> 'keypress_key',
                'type'      => get_option( 'wpdeepl_' . KeyPressWPDeepLPro::OPTIONKEY_LICENSE) ? 'password' : 'text',
                'description'   	=> __('You can get your license key on <a target="_blank" href="https://solutions.fluenx.com/mon-compte/">your account</a>', 'wpdeepl'),
                'css'		=> 'width: 20rem;'
			)
		)
	);
	
/*
passé dans le réglage normal
	$pro_settings['sections']['post_types'] = array(
		'title'	=> __('Post types', 'wpdeepl' ),
		'fields'	=> array(
			array(
				'id'			=> 'pro_post_types',
				'title'			=> __( 'Extra post types:', 'wpdeepl' ),
				'type'			=> 'multiselect',
				'options'		=> $post_types,
				'css'			=> 'width: 20rem; height: '. max( count( $post_types ) *1.5, 3) .'rem;',
			)
		)
	);
*/
	$pro_settings['sections']['post_types'] = array(
		'title'	=> __('Comments', 'wpdeepl' ),
		'fields'	=> array(
			array(
				'id'			=> 'pro_comments',
				'title'			=> __( 'Manage comments', 'wpdeepl' ),
				'type'			=> 'checkbox',
			)
		)
	);

	$pro_settings['sections']['specific'] = array(
		'title'	=> __('Specific fields', 'wpdeepl' ),
		'fields'	=> array(
			array(
				'id'			=> 'pro_translate_slug',
				'title'			=> __( 'SEO slug:', 'wpdeepl' ),
				'type'			=> 'checkbox',
				'label'			=> __('Translate', 'wpdeepl' ),
				'default'		=> 'no',
			),
			array(
				'id'			=> 'pro_translate_acf_featured',
				'title'			=> __( 'Blocs ACF Featured:', 'wpdeepl' ),
				'type'			=> 'checkbox',
				'label'			=> __('Translate', 'wpdeepl' ),
				'default'		=> 'no',
			)

		)
	);



	$description = false;
	$description = 
	__('When dealing with unmanaged translated hierarchical taxonomies (like Post Categories with no relationship between translations), this can create duplicates', 'wpdeepl')
	.'<br />'
	. __('Best choice is to have managed translations (like Polylang term translation management). Next best choice is NOT to translate taxonomies.', 'wpdeepl' )
	.'<br />'
	. __('<b>Warning:</b> When translating terms, if you\'re not using Polylang, the translation will create the translated terms if they\'re not found', 'wpdeepl' );

	$pro_settings['sections']['taxonomies'] = array(
		'title'		=> __('Taxonomies', 'wpdeepl' ),
		'description'	=> $description,
		'fields'	=> array()
	);

	$wp_post_types = get_post_types( array(  'show_ui' => true ), 'objects' );
	$post_types = array();
	if( $wp_post_types ) foreach( $wp_post_types as $post_type => $WP_Post_Type ) {
		$post_types[$post_type] = $WP_Post_Type->label;
	}
	unset( $post_types['shop_order'] );
	/*
	$possible_pro_post_types = $post_types;
	unset( $possible_pro_post_types['post'] );
	unset( $possible_pro_post_types['page'] );
	unset( $possible_pro_post_types['attachment'] );
	*/
	foreach( $post_types as $post_type => $post_type_name ) {

		$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$possible_taxonomies = array();
		if( $post_type_taxonomies ) foreach( $post_type_taxonomies  as $WP_Taxonomy ) {
			if( empty( $WP_Taxonomy->label ) ) 
				continue;
			if( $WP_Taxonomy->name == 'language' ) // polylang
				continue;
			$possible_taxonomies[$WP_Taxonomy->name] = $WP_Taxonomy->label;
		}
		$pro_settings['sections']['taxonomies']['fields'][] = array(
			'id'			=> 'pro_taxonomies_' . $post_type,
			'title'			=> sprintf( __( 'Translate terms (%s):', 'wpdeepl' ), $post_type_name ),
			'type'			=> 'multiselect',
			'options'		=> $possible_taxonomies,
			'css'			=> 'width: 20rem; height: ' . max( 4, count( $possible_taxonomies) +1 ) . 'rem;',
		);
	}


	/**
	 * ACF
	 * */
	if( class_exists('ACF') ) {
		$pro_settings['sections']['custom_fields'] = array(
			'title'	=> __('Custom fields (ACF)', 'wpdeepl' ),
			'fields'	=> array()
		);


		$possible_fields = array();

		$these_are_acf_fields = array();
		//plouf( acf_get_location_rule_values( 'post_type') , " post type");

		$acf_rule = array( 'param'	=> 'post_type' );
		if( function_exists('acf_get_location_rule_values' ) )
			$choices = acf_get_location_rule_values( $acf_rule );
		else 
			$choices = array();

		
		if( $choices ) foreach( $choices as $post_type => $post_type_name	) {
			if( $post_type == 'shop_order' ) {
				continue;
			}
			$these_are_acf_fields[$post_type] = array();
			$possible_fields = array();
			$field_groups = acf_get_field_groups( array('post_type' => $post_type ) );
			foreach ( $field_groups as $group ) {
				// DO NOT USE here: $fields = acf_get_fields($group['key']);
				// because it causes repeater field bugs and returns "trashed" fields
				$fields = get_posts(array(
					'posts_per_page'	 => -1,
					'post_type'				=> 'acf-field',
					'orderby'				=> 'menu_order',
					'order'					=> 'ASC',
					'suppress_filters' 		=> true, // DO NOT allow WPML to modify the query
					'post_parent'			=> $group['ID'],
					'post_status'			=> 'any',
					'update_post_meta_cache' => false
				));

//				plouf($fields," pour $post_type / $post_type_name");
				if( $fields ) foreach ( $fields as $field ) {
					$these_are_acf_fields[$post_type][] = $field->post_excerpt;
					$possible_fields[$field->post_excerpt] = $field->post_title;
				}
			}
		

			$pro_settings['sections']['custom_fields']['fields'][] = array(
				'id'			=> 'pro_custom_fields_' . $post_type,
				'title'			=> sprintf( __( 'Translate custom fields (ACF) (%s):', 'wpdeepl' ), $post_type_name ),
				'type'			=> 'multiselect',
				'options'		=> $possible_fields,
				'css'			=> 'width: 20rem; height: ' . max( 4, count( $possible_fields) + 1 ) . 'rem; name: ' . $post_type. ';',
			);
		}

	}

	/**
	 * 
	 * Meta
	 * */
/*
	if( class_exists('RWMB_Loader' ) ) {

		$meta_boxes = RWMB_Core::get_meta_boxes();

		//plouf( $meta_boxes, " LOADER");
		$pro_settings['sections']['meta_fields'] = array(
			'title'	=> __('Custom fields (Meta)', 'wpdeepl' ),
			'fields'	=> array()
		);

		foreach( $meta_boxes as $post_type => $meta_fields ) {

			$possible_fields = array();
			foreach( $meta_fields['fields'] as $field ) {
				$possible_fields[$field['id']] = $field['name'];
			}
			$pro_settings['sections']['meta_fields']['fields'][] = array(
				'id'			=> 'meta_custom_fields_' . $post_type,
				'title'			=> sprintf( __( 'Translate custom fields (Meta) (%s):', 'wpdeepl' ), $meta_fields['title'] ),
				'type'			=> 'multiselect',
				'options'		=> $possible_fields,
				'css'			=> 'width: 20rem; height: ' . max( 4, count( $possible_fields) + 1 ) . 'rem; name: ' . $post_type. ';',
			);
		}


	}
*/
	
	$pro_settings['sections']['metas'] = array(
		'title'	=> __('Meta keys', 'wpdeepl' ),
		'fields'	=> array()
	);

	$active_post_types = DeepLConfiguration::getActivePostTypes();

	foreach( $active_post_types as $post_type ) {
		$possible_metas = deeplpro_getAllPossiblePostMeta( array( $post_type ) );

		//plouf( $these_are_acf_fields );		
		//plouf( $possible_metas, "possible pour $post_type");

		if( isset( $these_are_acf_fields ) && $these_are_acf_fields && isset( $these_are_acf_fields[$post_type] ) ) foreach( $these_are_acf_fields[$post_type] as $meta_key ) {
			if( isset( $possible_metas['_' .$meta_key] ) ) {
				unset( $possible_metas['_' .$meta_key] );
			}
		}
		

		if( isset( $post_types[$post_type] ) ) {
			$pro_settings['sections']['metas']['fields'][] = array(
				'id'			=> 'pro_metas_' . $post_type,
				'title'			=> sprintf( __( 'Translate metas (%s):', 'wpdeepl' ), $post_types[$post_type] ),
				'type'			=> 'multiselect',
				'options'		=> $possible_metas,
				'css'			=> 'width: 20rem; height: ' . max( 4, count( $possible_metas) * 1.5 ) . 'rem;',
			);
		}
	}


	$languages = DeepLConfiguration::DefaultsAllLanguages();
	$target_locales = array();

	$locale = get_locale();

	foreach( $languages as $language_locale => $language_data ) {
		if( isset( $language_data['labels'][$locale] ) ) {
			$target_locales[$language_locale] = $language_data['labels'][$locale];
		}
		else {
			$target_locales[$language_locale] = $language_data['labels']['en_GB'];
		}

	}

	$bulk_replace_or_create = array(
		'replace'	=> __('Translate posts in place', 'wpdeepl' ),
		'create'	=> __('Create new posts', 'wpdeepl' ),
	);
	$possible_post_types = deeplpro_metabox_post_types( array() );
	$pro_settings['sections']['bulk'] = array(
		'title'	=> __('Bulk translation', 'wpdeepl' ),
		'fields'	=> array(
			array(
				'id'			=> 'bulk_post_types',
				'title'			=> __( 'Post types for bulk actions', 'wpdeepl' ),
				'type'			=> 'multiselect',
				'options'		=> $possible_post_types,
				'description'	=> __( 'Add bulk actions for these post types', 'wpdeepl' ),
				'css'			=> 'height: ' . (count( $possible_post_types ) + 6 ) . 'em; width: 15em;',
			),
			array(
				'id'			=> 'bulk_replace_or_create',
				'title'			=> __( 'What to do when bulk translating ?', 'wpdeepl' ),
				'type'			=> 'radio',
				'values'		=> $bulk_replace_or_create,
			),
			array(
				'id'			=> 'bulk_target_locales',
				'title'			=> __( 'Target languages for bulk actions', 'wpdeepl' ),
				'type'			=> 'multiselect',
				'options'		=> $target_locales,
				'description'	=> __( 'Show these target languages in bulk menu', 'wpdeepl' ),
				'css'			=> 'height: ' . (count( $target_locales ) + 6 ) . 'em; width: 15em;',
			),
		) 
	);

	
	$new_settings = array();
	foreach( $settings as $tab => $data ) {
		if( $tab == 'maintenance' ) {
			$new_settings['pro'] = $pro_settings;
			$new_settings[$tab] = $data;
		}
		else {
			$new_settings[$tab] = $data;
		}
		
	}
	
	$new_settings['pro']['footer']['actions'] = array('deeplpro_admin_test');
	//echo " PRO SETTIGS ADDDED";	
	return $new_settings;
}


add_action('admin_footer', 'wpdeepl_pro_admin_css');
function wpdeepl_pro_admin_css() {

	?>
	<style type="text/css">
		th#wpdeepl_translation {
			width:	6rem;
		}
		a#deepl_settings-pro {
			background: #ffd0f1;
			color: #810909;
			border-right-color: #000000;
			border-left-color: #000000;
			border-top-color: #000000;
			
		}
	</style>

	<?php 
}

function raw_get_object_terms( $object_id ) {
	global $wpdb;
	$sql = "SELECT t.*, tt.*
	FROM 	$wpdb->terms t 
	JOIN 	$wpdb->term_taxonomy tt ON tt.term_id = t.term_id 
	JOIN 	$wpdb->term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id 
	WHERE 	tr.object_id = %d";
	$sql = $wpdb->prepare( $sql, $object_id );
	return $wpdb->get_results( $sql, ARRAY_A );
}
function deeplpro_admin_test() {
	return;
	
	echo "using pL ?";
	var_dump( DeepLProConfiguration::usingPolylang() );

	$taxonomies = array('category', 'language', 'post_translations', 'product_cat', 'product_type', 'product_visibility', 'term_language', 'term_translations', 'wp_theme' );

	$post_ids = array(171,173 );
	$all_terms = array();
	foreach( $post_ids as $post_id ) {
		foreach( $taxonomies as $taxo ) {
			$terms = get_the_terms( $post_id, $taxo );
			if( $terms ) foreach( $terms as $WP_Term  ) {
				$WP_Term->description = maybe_unserialize( $WP_Term->description );
				$all_terms[$post_id][$taxo][$WP_Term->term_id] = $WP_Term;
			}
		}
	}

	plouf( $all_terms );
}



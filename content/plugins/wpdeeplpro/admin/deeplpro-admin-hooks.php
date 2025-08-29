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


add_action('admin_notices', 'wpdeeplpro_acf_warning');
function wpdeeplpro_acf_warning() {
	$screen = get_current_screen();
	if( class_exists('ACF') && $screen->base == 'settings_page_deepl_settings' && isset( $_GET['tab'] ) && htmlspecialchars_decode( $_GET['tab'] ) == 'metas' ) {
	?>
	<div class="notice notice-warning is-dismissible">
<p><?php _e('If you\'re using ACF, please make sure NEVER to name a Fields Group, a Field or a Block starting with "block"', 'wpdeepl') ?></p>
</div>
<?php

	}
	
}
	


add_filter( 'deepl_admin_configuration', 'deeplpro_admin_configuration', 30, 1 );
function deeplpro_admin_configuration( $settings ) {

	if( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] !== 'deepl_settings' ) 
		return $settings;

	if( 1 == 2 ) {

		$glossary_id = 'c028b669-1af8-45ad-8b3c-24a159d02455';
		$glossary_id = 'f42eb13b-efa0-44e7-952f-06a602c85394';
		$entries = deepl_listGlossaryEntries( $glossary_id );
		var_dump( $entries );

		$source_lang = 'ru';
		$target_lang = 'en-gb';
		$strings_to_translate = array( 'post_content' => 'Ну приветик' );
		plouf( $strings_to_translate);
		$allow_cache = false;
		$translations = deepl_translate( $source_lang, $target_lang, $strings_to_translate, '', $allow_cache );
		plouf( $translations );
		die('ok');

	}
	$debug = true;
	$debug = false;

	
	//plouf($settings);	die('okaze4eaz4e84aze6984');

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



	$meta_settings = array(
		'title'			=> __('Metas', 'wpdeepl'),
		'sections' 	=> array()
	);
			
	/**
	 * ACF
	 * */
	$possible_fields = array();
	$these_are_acf_fields = array();
	if( class_exists('ACF') ) {
		$meta_settings['sections']['custom_fields_blocks'] = array(
			'title'	=> __('ACF Blocks', 'wpdeepl' ),
			'fields'	=> array(
				
			)
		);

		$meta_settings['sections']['custom_fields'] = array(
			'title'	=> __('ACF Custom fields', 'wpdeepl' ),
			'fields'	=> array(
				
			)
		);



		$not_translated_types = array(
			'number', 'range', 'email', 'url', 'password',
			'image', 'file', 'oembed', 'gallery',
			'checkbox', 'radio', 'button_group', 'true_false', 'select',
			'link', 'post_object', 'page_link', 'relationship','taxonomy', 'user',
			'google_map', 'date_picker', 'date_time_picker', 'time_picker', 'color_picker',
			'accordion', 'clone', 'tab',

		);
		$groups = deeplpro_get_groups();


		if( $groups ) foreach( $groups as $group_id => $group_data ) {
	

			$select_fields = array();
			$fields = acf_get_fields( $group_data['post_name'] );
			if( $fields ) foreach ( $fields as $field ) {
					if( isset( $field['type'] ) && $field['type'] == 'flexible_content' )  {
						$key = $field['name'];
						//$key = str_replace('acf-', '', $key );
						$these_are_acf_fields[] = $key;

						foreach( $field['layouts'] as $layout_id => $layout ) {
							if( isset( $layout['sub_fields'] ) && count( $layout['sub_fields'] ) ) {
								foreach( $layout['sub_fields'] as $sub_field ) {
									
									$sub_key = $key .'#' . $sub_field['name'];
									if( !empty( $sub_key ) ) 
										$these_are_acf_fields[] = $sub_key;
									if( isset( $sub_field['sub_fields'] ) ) {
										foreach( $sub_field['sub_fields'] as $sub_sub_field ) {
											if( in_array( $sub_sub_field['type'], $not_translated_types) ) {
												continue;
											}
											$sub_sub_key = $sub_key . '#' . $sub_sub_field['name'];
											if( !empty( $sub_sub_key ) ) 
												$these_are_acf_fields[] = $sub_sub_key;
											if( !empty( $field['label'] ) && !empty( $sub_field['label'] ) && !empty( $sub_sub_field['label'] ) ) {
												$label = $field['label'] . ' / ' . $sub_field['label'] . ' / ' . $sub_sub_field['label'];
												$possible_fields[$sub_sub_key] = $label;
												$select_fields[$sub_sub_key] = $label;
											}
										}
									}
									else {
										if( in_array( $sub_field['type'], $not_translated_types ) ) {
											continue;
										}
										if( !empty( $sub_field['name'] ) ){
											$these_are_acf_fields[] = $sub_field['name'];
											if( !empty( $field['label'] ) && !empty( $sub_field['label'] ) ) {
												$label = $field['label'] . ' / ' . $sub_field['label'];
												$possible_fields[$sub_key] = $label;
												$select_fields[$sub_key] = $label;
											}
										}
										
									}									
								}
							}


						}
					}
					elseif( isset( $field['type'] ) && in_array( $field['type'], array('repeater', 'group' ) ) )  {
						//plouf( $field );
						$key = $field['name'];
						if( !empty( $field['name'] ) ) 
							$these_are_acf_fields[] = $key;

						if( isset( $field['sub_fields'] ) ) foreach( $field['sub_fields'] as $sub_field ) {
							if( in_array( $sub_field['type'], $not_translated_types ) ) {
								continue;
							}
							$sub_key = $key .'#' . $sub_field['name'];
							if( !empty( $sub_key ) ) 
								$these_are_acf_fields[] = $sub_key;
							if( isset( $sub_field['sub_fields'] ) ) {
								foreach( $sub_field['sub_fields'] as $sub_sub_field ) {
									if( in_array( $sub_sub_field['type'], $not_translated_types) ) {
										continue;
									}
									$sub_sub_key = $sub_key . '#' . $sub_sub_field['name'];
									if( !empty( $sub_sub_key ) ) 
										$these_are_acf_fields[] = $sub_sub_key;
									if( !empty( $field['label'] ) && !empty( $sub_field['label'] ) && !empty( $sub_sub_field['label'] ) ) {
										$label = $field['label'] . ' / ' . $sub_field['label'] . ' / ' . $sub_sub_field['label'];
										$possible_fields[$sub_sub_key] = $label;
										$select_fields[$sub_sub_key] = $label;
									}
								}
							}
							else {
								if( !empty( $sub_field['name'] ) ){
									$these_are_acf_fields[] = $sub_field['name'];
									if( !empty( $field['label'] ) && !empty( $sub_field['label'] ) ) {
										$label = $field['label'] . ' / ' . $sub_field['label'];
										$possible_fields[$sub_key] = $label;
										$select_fields[$sub_key] = $label;
									}
								}
								
							}

						}
					}
					else {
						if( in_array( $field['type'], $not_translated_types ) ) {
							continue;
						}
						if( !empty( $field['name'] ) ) {
							$these_are_acf_fields[] = $field['name'];
							if( !empty( $field['label'] ) ) {
								$possible_fields[$field['name']] = $field['label'] . ' (' . $field['type'] . ')';
								$select_fields[$field['name']] = $field['label'];

							}
						}


					}
				}

			//plouf( $group_data, " group");			plouf( $select_fields, " select fields");

			if( count ($select_fields ) ) {
				if( $group_data['is_block'] ) {
					$array = $select_fields;
					$select_fields = array();
					
					/*
					$group_slug = $group_data['post_excerpt'];
					$group_slug = str_replace('block-', '', $group_slug );
					$group_slug = str_replace('acf-', '', $group_slug );
					*/
					$group_slug = $group_data['block_slug'];

					foreach( $array as $key => $label ) {
						$select_fields['#block#' . $group_slug .'#' . $key] = $label;
					}

					$meta_settings['sections']['custom_fields_blocks']['fields'][] = array(
						'id'			=> 'pro_custom_fields_blocks_' . $group_id,
						'title'			=> sprintf( __( 'ACF %s', 'wpdeepl' ), $group_data['post_title'] ),
						'type'			=> 'multiselect',
						'options'		=> $select_fields,
						'css'			=> 'width: 40rem; height: ' . min( 100, count( $select_fields) + 1 ) . 'rem;',
					);
				}
				else {
					$meta_settings['sections']['custom_fields']['fields'][] = array(
						'id'			=> 'pro_custom_fields_' . $group_id,
						'title'			=> sprintf( __( 'ACF group : %s', 'wpdeepl' ), $group_data['post_title'] ),
						'type'			=> 'multiselect',
						'options'		=> $select_fields,
						'css'			=> 'width: 40rem; height: ' . min( 100, count( $select_fields) + 1 ) . 'rem;',
					);
				}
				
	
			}
			else {
			}


		}
		if( isset( $meta_settings['sections']['custom_fields_blocks']) && count( $meta_settings['sections']['custom_fields_blocks']['fields'] ) == 0 ) {
			unset( $meta_settings['sections']['custom_fields_blocks'] );
		}
		if( isset( $meta_settings['sections']['custom_fields']['fields'] ) && count( $meta_settings['sections']['custom_fields']['fields'] ) == 0 ) {
			unset( $meta_settings['sections']['custom_fields'] );
		}



	}
	

	/**
	 * 
	 * Meta
	 * */

	
	$meta_settings['sections']['metas'] = array(
		'title'	=> __('Meta keys', 'wpdeepl' ),
		'fields'	=> array()


	);

	if( $debug) plouf( $these_are_acf_fields, " acf fields");

	$active_post_types = DeepLConfiguration::getActivePostTypes();

	foreach( $active_post_types as $post_type ) {
		$possible_metas = deeplpro_getAllPossiblePostMeta( array( $post_type ) );

		if( $debug ) plouf( $possible_metas, "possible pour $post_type");
		if( $debug  ) plouf( $these_are_acf_fields, "mais ceux la sont acf");		

		if( isset( $these_are_acf_fields ) && $these_are_acf_fields ) foreach( $these_are_acf_fields as $meta_key ) {

			if( isset( $possible_metas['_' .$meta_key] ) ) {
				unset( $possible_metas['_' .$meta_key] );
			}
			$regex = str_replace('#', '_\d+_', $meta_key );
			if( $debug && $post_type == 'product' ) echo "\n removing with $regex";
			foreach( $possible_metas as $key => $value ) {
				if( preg_match( '#' . $regex . '#' , $key ) ) {
					unset( $possible_metas[$key] );
				}
			}
		}
		
		if( $debug ) {
			plouf( $possible_metas, "final après regex");
		}

		if( isset( $post_types[$post_type] ) ) {
			$meta_settings['sections']['metas']['fields'][] = array(
				'id'			=> 'pro_metas_' . $post_type,
				'title'			=> sprintf( __( 'Translate metas (%s):', 'wpdeepl' ), $post_types[$post_type] ),
				'type'			=> 'multiselect',
				'options'		=> $possible_metas,
				'css'			=> 'width: 30rem; height: ' . max( 4, count( $possible_metas) * 1.5 ) . 'rem;',
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
	$bulk_timestamps = array(
		'same'	=> __('Keep the same timestamp', 'wpdeepl' ),
		'now'	=> __('Time stamp to the time of translation', 'wpdeepl' ) 
	);
	$bulk_publish = array(
		'draft'	=> __('Translate to drafts', 'wpdeepl' ),
		'publish'	=> __('Translate and publish', 'wpdeepl' ) 
	);


	$possible_post_types = deeplpro_metabox_post_types( array() );

	$bulk_settings = array(
		'title'	=> __('Bulk translation','wpdeepl' ) , 
		'sections'	=> array() 
	);

	$bulk_settings['sections']['bulk'] = array(
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
				'id'			=> 'bulk_same_datetime',
				'title'			=> __( 'Keep the same timestamp on newly created posts', 'wpdeepl' ),
				'type'			=> 'radio',
				'values'		=> $bulk_timestamps,
			),
			array(
				'id'			=> 'bulk_publish',
				'title'			=> __( 'Publish automatically the new translations', 'wpdeepl' ),
				'type'			=> 'radio',
				'values'		=> $bulk_publish,
				'default'		=> 'draft',
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




	$glossaries_settings = array(
		'title'	=> __('Glossaries', 'wpdeepl' ),
		'sections'	=> array()
	);

	$results = deepl_listGlossaries();

	$orphaned_glossaries = deeplpro_getOrphanedGlossaries();
	$glossaries = array();
	if( $results) foreach( $results as $glossary_id => $glossary_data ) {
		$glossaries[$glossary_data['source_lang']][$glossary_data['target_lang']][$glossary_data['glossary_id']] = sprintf( 
			__('%s (%d entries)', 'wpdeepl'),
			$glossary_data['name'],
			$glossary_data['entry_count']
		);
	}

	$glossaries_settings['sections']['list'] = array(
		'title'	=> __('Glossaries in use','wpdeepl'),
		'fields'	=> array(
		)
	);

	$show_entries = DeepLProConfiguration::showGlossaryEntries();

	foreach( $glossaries as $source_lang => $target_glossaries ) {
		foreach( $target_glossaries as $target_lang => $list ) {


			$entries = false; 
			$description = false;
			if( $show_entries ) {
				$active_glossary = DeepLProConfiguration::getActiveGlossary( $source_lang, $target_lang );
				if( $active_glossary ) {
					$entries = deepl_listGlossaryEntries( $active_glossary );
					$entries_count = count( $entries );
					$entries = array_slice( $entries, 0, 10 );

					$showing_count = count( $entries ) > 10 ? 10 : count( $entries );
					$description = sprintf( __('Showing first %d entries', 'wpdeepl' ), $showing_count );
					foreach( $entries as $from => $to ) {
						$description .= '<br />' . sprintf('%s > %s', $from, $to );
					}

				}

			}

			$list = array_merge( array('0' => __(' - (None)', 'wpdeepl') ), $list );

			$glossaries_settings['sections']['list']['fields'][] = array(
				'id'		=> 'glossary_' . $source_lang . '_' . $target_lang,
				'title'		=> sprintf( 
					__('From %s to %s', 'wpdeepl'),
					DeepLConfiguration::getLocaleNameForIsoCode2( $source_lang ),
					DeepLConfiguration::getLocaleNameForIsoCode2( $target_lang )
				),
				'description'	=> $description,
				'type'		=> 'select',
				'options'		=> $list
			);
			$orphan_key = 'wpdeepl_glossary_'. $source_lang .'_' . $target_lang;
			if( isset( $orphaned_glossaries[$orphan_key] ) ) {
				unset( $orphaned_glossaries[$orphan_key] );
			}
		}
	}
	if( count( $orphaned_glossaries ) ) foreach( $orphaned_glossaries as $option_key => $option_value) {
		if( $option_value == 0 ) {
			unset( $orphaned_glossaries[$option_key] );
		}
	}
	if( count( $orphaned_glossaries ) ) foreach( $orphaned_glossaries as $option_key => $option_value) {
		$source_lang = substr( $option_key, 17, 2 );
		$target_lang = substr( $option_key, 20, 2 );
		$glossaries_settings['sections']['list']['fields'][] = array(
			'id'		=> 'glossary_' . $source_lang . '_' . $target_lang,
			'title'		=> sprintf( 
				__('From %s to %s', 'wpdeepl'),
				DeepLConfiguration::getLocaleNameForIsoCode2( $source_lang ),
				DeepLConfiguration::getLocaleNameForIsoCode2( $target_lang )
			),
			'type'		=> 'select',
			'value'	=> $option_value,
			'options'		=> array('0' => __(' - (None)', 'wpdeepl') ),
		);
	}

	$glossaries_settings['sections']['list']['fields'][] = array(
		'id'	=> 'glossary_show_entries',
		'title'	=> __('Show first entries of each glossary', 'wpdeepl' ),
		'type'	=> 'checkbox',
	);




	$glossaries_settings['footer']['actions'] = array( 'deeplpro_add_glossary_html', 'deeplpro_remove_glossary_selectors' );



	$new_settings = array();
	foreach( $settings as $tab => $data ) {
		if( $tab == 'maintenance' ) {
			$new_settings['pro'] = $pro_settings;
			$new_settings['metas'] = $meta_settings;
			$new_settings['bulk'] = $bulk_settings;
			$new_settings['glossaries'] = $glossaries_settings;
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
		a#deepl_settings-pro, a#deepl_settings-metas, a#deepl_settings-bulk, a#deepl_settings-glossaries {
			background: #ffb300;
			color: #810909;
			border-color: #5c4000;
			border-bottom: none;	
			
		}
		a#deepl_settings-pro.nav-tab-active, a#deepl_settings-metas.nav-tab-active, a#deepl_settings-bulk.nav-tab-active, a#deepl_settings-glossaries.nav-tab-active {
			border-bottom: 1px solid #f0f0f1;
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



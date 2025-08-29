<?php

class DeepLProConfiguration {

	static function getProPostTypes() {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_post_types') );
	}

	static function getProBulkPostTypes() {
		return apply_filters( __METHOD__, get_option('wpdeepl_bulk_post_types' ) );
	}

	static function getProBulkAction() {
		$choice = get_option( 'wpdeepl_bulk_replace_or_create' ) ? get_option( 'wpdeepl_bulk_replace_or_create' ) : 'create';
		return apply_filters( __METHOD__, $choice );
	}

	static function getBulkPublishOrDraft() {
		$choice = get_option( 'wpdeepl_bulk_publish' ) ? get_option( 'wpdeepl_bulk_publish' ) : 'draft';
		return apply_filters( __METHOD__, $choice );
	}

	static function getBulkTimeStampChoice() {
		$choice = get_option( 'wpdeepl_bulk_same_datetime' ) ? get_option( 'wpdeepl_bulk_same_datetime' ) : 'same';
		return apply_filters( __METHOD__, $choice );
	}

	static function getContentTypes() {
		return apply_filters( __METHOD__, get_option('wpdeepl_contents_to_translate') );
	}
	
	static function getTargetLocales() {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_bulk_target_locales') );
	}

	static function usingPolylang() {
		return function_exists( 'pll_the_languages' );
	}

	static function getBulkTargetLanguages() {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_bulk_target_locales' ) );
	}

	static function shouldWeTranslateSlug() {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_translate_slug' ) === 'yes' );
	}

	static function shouldWeTranslateACFFeaturedBlocks() {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_translate_acf_featured' ) === 'yes' );
	}

	static function getACFBlocksToTranslate() {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_pro_pro_custom_blocks' ) );
	}
	static function whatTaxonomiesShouldWeTranslateForPostType( $post_type ) {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_pro_taxonomies_' . $post_type ) );	
	}

	static function getMetaKeysToTranslateFor($post_type ) {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_metas_' . $post_type ) );
	}
	static function getCustomFieldsToTranslateFor($post_type ) {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_custom_fields_' . $post_type ) );
	}
	static function getACFFieldsToTranslate() {
		$fields = array(
			'blocks'	=> array(),
			'fields'	=> array()
		);
		$results = deeplpro_get_option_like( 'wpdeepl_pro_custom_fields_%' );
		
		if( $results ) foreach( $results as $result ) {
			$array = maybe_unserialize( $result['option_value'] );
			$block_id = str_replace('wpdeepl_pro_custom_fields_blocks_', '', $result['option_name'] );
			if( $array && is_array( $array ) ) foreach( $array as $value ) {
				if( !empty( $value ) ) {
					if( substr( $value, 0, 7 ) == '#block#' ) {
						$fields['blocks'][] = substr( $value, 7);
						$block = get_post( $block_id );
						if( $block && isset( $block->post_content ) )   {
							$block_data = maybe_unserialize( $block->post_content );
							foreach( $block_data['location'] as $i => $sub ) {
								foreach( $sub as $j => $parameter ) {
									if( $parameter['param'] == 'block' ) {
										$block_slug = str_replace( 'acf/', '', $parameter['value'] );
										//plouf( $result );										echo "\n block $block_id slug = $block_slug ou value = " . substr( $value, 7 );
									}
								}
							}
						}

					}
					else {
						$fields['fields'][] = $value;
					}
					
				}
			}
		}
		return apply_filters( __METHOD__, $fields );
	}


	static function doWeManageComments() {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_pro_comments') ) ;
	}

	static function getActiveGlossary( $source, $target ) {
		$key = 'wpdeepl_glossary_' . $source . '_'.$target;
		return apply_filters( __METHOD__, get_option( $key ) );
	}

	static function showGlossaryEntries() {
		return apply_filters( __METHOD__, get_option('wpdeepl_glossary_show_entries') );
	}
	
}


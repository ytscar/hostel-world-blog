<?php

class DeepLProConfiguration {

	static function getProPostTypes() {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_post_types') );
	}

	static function getProBulkPostTypes() {
		return apply_filters( __METHOD__, get_option('wpdeepl_bulk_post_types' ) );
	}

	static function getProBulkAction() {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_bulk_replace_or_create' ) );
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
	static function whatTaxonomiesShouldWeTranslateForPostType( $post_type ) {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_pro_taxonomies_' . $post_type ) );	
	}

	static function getMetaKeysToTranslateFor($post_type ) {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_metas_' . $post_type ) );
	}
	static function getCustomFieldsToTranslateFor($post_type ) {
		return apply_filters( __METHOD__, get_option('wpdeepl_pro_custom_fields_' . $post_type ) );
	}

	static function doWeManageComments() {
		return apply_filters( __METHOD__, get_option( 'wpdeepl_pro_comments') ) ;
	}

	
}


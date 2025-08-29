<?php

function deeplpro_get_polylang_languages_tt_id() {
	$languages = get_terms( 'language', array( 'hide_empty' => false ) );
	$array = array();
	if( $languages )foreach( $languages as $language ) {
		$details = maybe_unserialize( $language->description );
		if( isset( $details['locale']  ) ) {
			$array[$details['locale']] = $language->term_taxonomy_id;
		}
	}
	return $array;
}

function deeplpro_get_polylang_slug_for_language( $language ) {
	// get EN-GB, returns en_GB
	$explode = explode( '-', $language );
	$explode[0] = strtolower( $explode[0] );
	$pll_lang = implode('_', $explode );

	if( $pll_lang == 'nb' )
		$pll_lang = 'nn';

	return $pll_lang;
}
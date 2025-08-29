<?php


add_action('admin_init', 'deeplpro_add_bulk_actions');
function deeplpro_add_bulk_actions() {


	$comments_on = DeepLProConfiguration::doWeManageComments();
	
	$post_types = DeepLProConfiguration::getProBulkPostTypes();
	$comments_on = DeepLProConfiguration::doWeManageComments();
	if( $comments_on ) {
		$post_types[] = 'comments';
	}

//	echo '<!-- wpdeepl_pro:post_types_for_bulk '; plouf( $post_types ); echo '-->';
	if( !$post_types || !count( $post_types ) ) {
		return;
	}

	
	foreach( $post_types as $post_type ) {
		add_filter( 'bulk_actions-edit-' . $post_type , 'deeplpro_bulk_action' );
		add_filter( 'handle_bulk_actions-edit-' . $post_type, 'deeplpro_bulk_translate', 10, 3 );
	}
	 
}

function deeplpro_bulk_action( $bulk_array ) {
	//plouf( func_get_args() );	die(__FUNCTION__);

	$target_languages = DeepLProConfiguration::getBulkTargetLanguages();
	//echo '<!-- wpdeeplpro:target_languages_for_bulk' ; plouf( $target_languages, "alors ?" ); echo ' -->';
	if( !$target_languages || !is_array( $target_languages ) || !count( $target_languages ) ) {
		return;
	}

 	foreach( $target_languages as $isocode ) {
 		$language = DeepLConfiguration::validateLang( $isocode, 'full' );
		$bulk_array['deeplpro_' . $language['astarget']] = sprintf( __('Translate to %s', 'wpdeepl' ), $language['label'] );
 	}
	return $bulk_array;
 
}

function deeplpro_bulk_translate(  $redirect_url, $doaction, $object_ids ) {


	if( substr( $doaction, 0, 8 ) != 'deeplpro' ) {
		return;
	}
	// already checked by wordpress check_admin_referer( 'bulk-posts' );

	$target_language = substr( $doaction, 9 );
	$dp_translated = 0;

	if( !$object_ids ) {
		return $redirect_url;
	}

	$bulk_action = DeepLProConfiguration::getProBulkAction();

	$mode = 'post';
	if( get_current_screen() && get_current_screen()->base == 'edit-comments' ) {
		$mode = 'comments';
	}


	foreach( $object_ids as $object_id ) {

		if( $bulk_action == 'create' ) {
			$old_post_id = $object_id;
			$pll_lang = deeplpro_get_polylang_slug_for_language( $target_language );

			$object_id = deeplpro_duplicate_post(  $old_post_id, $pll_lang );
			//echo ("old $old_post_id new $object_id"); die ('ok');
		}
		$data = array(
			'ID'	=> $object_id,
			'target_lang'	=> $target_language,
			'bulk'	=> true,
			'bulk_action'	=> $bulk_action,
		);
		if( $mode == 'post' )
			$translated = deepl_translate_post_link( $data );
		else 
			$translated = deepl_translate_comment_link( $data );
		//var_dump( $translated );
		if( $translated ) {
			$dp_translated++;
		}
	}

//	die('oezrozk');

	$redirect_url = add_query_arg( 'dp-translated', $dp_translated, $redirect_url );

	return $redirect_url;

}

add_action('admin_notices', 'deeplpro_admin_notices_translated' );
function deeplpro_admin_notices_translated() {
	if (!empty($_REQUEST['dp-translated'])) {
		$num_translated = filter_var( $_REQUEST['dp-translated'], FILTER_VALIDATE_INT );
		if( $num_translated > 1 ) 
			printf('<div id="message" class="updated notice is-dismissable"><p>' . __('%d posts translated.', 'wpdeepl') . '</p></div>', $num_translated);
		else 
			printf('<div id="message" class="updated notice is-dismissable"><p>' . __('%d post translated.', 'wpdeepl') . '</p></div>', $num_translated);
	}

}

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


	$bulk_action = DeepLProConfiguration::getProBulkAction();
	
	foreach( $post_types as $post_type ) {
		//echo "\n adding to $post_type";
		add_filter( 'bulk_actions-edit-' . $post_type , 'deeplpro_bulk_action' );
		add_filter( 'handle_bulk_actions-edit-' . $post_type, 'deeplpro_bulk_translate', 10, 3 );
		if( 1 ==1 || $bulk_action == 'replace') {
			if( $post_type == 'post' || $post_type == 'page' || $post_type == 'product' ) {
				add_action('manage_' . $post_type . 's_extra_tablenav', 'deeplpro_source_language_selector' );

			}
			if( $post_type == 'comments' ) {
				add_action('manage_' . $post_type . '_extra_tablenav', 'deeplpro_source_language_selector' );
			}
		}
	}

	 
}

function deeplpro_source_language_selector( $which ) {
	
	global $added_source_language_selector;
	if( $added_source_language_selector ) 
		return;
	$added_source_language_selector = true;
	?>

	<span id="source_language_selector">
		<label for="source_lang" style="float: left; padding: .25rem"><?php _e('Source language', 'wpdeepl'); ?></label>
		<?php echo deepl_language_selector( 'source', 'source_lang', false, false, true ); ?>
	</span>
	<!-- which=  <?php echo $which; ?> -->

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#source_language_selector').insertAfter('select#bulk-action-selector-<?php echo $which; ?>');
			jQuery('#source_language_selector').hide();

			jQuery('select[name="action"]').on('change', function() {
				var action = jQuery(this).val();
				console.log("action " + action );
				if( action.substr(0, 8) == 'deeplpro') {
					jQuery('#source_language_selector').show();
				}
				else {
					jQuery('#source_language_selector').hide();
				}

			});

		});
	</script>

	<?php
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

	if( WPDEEPLPRO_DEBUG ) {
		plouf( $_REQUEST, " do action $doaction");	plouf( $object_ids);	

	}


	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'pll_translate' )
		return $redirect_url;


	if( substr( $doaction, 0, 8 ) != 'deeplpro' ) {
		if( WPDEEPLPRO_DEBUG ) die('1');
		return $redirect_url;
	}
	// already checked by wordpress check_admin_referer( 'bulk-posts' );

	$target_language = substr( $doaction, 9 );
	//	echo "\n action $doaction donc $target_language";
	$dp_translated = 0;

	if( !$object_ids ) {
		if( WPDEEPLPRO_DEBUG ) die('2');
		return $redirect_url;
	}

	$bulk_action = DeepLProConfiguration::getProBulkAction();

	$mode = 'post';
	if( get_current_screen() && get_current_screen()->base == 'edit-comments' ) {
		$mode = 'comments';
	}

	if( WPDEEPLPRO_DEBUG ) plouf( $object_ids);

	//if( WPDEEPLPRO_DEBUG ) plouf( $object_ids );
	foreach( $object_ids as $object_id ) {

		$force_polylang = false;
		$existing_translation = deepl_already_exists_in_polylang( $object_id, $target_language );

		if( WPDEEPLPRO_DEBUG ) echo "\n existing pour $object_id ? $existing_translation";				

		$old_post_id = $object_id;

		if( $existing_translation ) {
			//if( WPDEEPLPRO_DEBUG ) plouf( $existing_translation, " exsitsings ");			if( WPDEEPLPRO_DEBUG ) die( $existing_translation );
			// on permet un doublon éventuel pour éviter l'absence de traduction ...
			//	continue;
			$force_polylang = true;
			$new_post_id = $existing_translation;
			$bulk_action = 'replace';
		}

		if( $bulk_action == 'create' ) {
			
			$pll_lang = deeplpro_get_polylang_slug_for_language( $target_language );
			$new_post_id = deeplpro_duplicate_post(  $old_post_id, $pll_lang );
			if( WPDEEPLPRO_DEBUG ) echo " <br />\n old $old_post_id > new $new_post_id ";
		}
		else {
			$new_post_id = $old_post_id;
		}


		if( WPDEEPLPRO_DEBUG ) {
			echo "\n on va traduire $old_post_id > $new_post_id en mode $bulk_action";
		}
		if( isset( $_REQUEST['source_lang'] ) ) {
			$source_language = DeepLConfiguration::validateLang( $_REQUEST['source_lang'] );
		}
		elseif( function_exists('pll_get_post_language') ) {
			$source_language = DeepLConfiguration::validateLang( pll_get_post_language( $object_id, 'slug' ) );
		}


		$target_language = DeepLConfiguration::validateLang( $target_language, 'astarget' );
		//echo ("old $old_post_id new $object_id, target '$target_language' "); plouf( $pll_lang ); 
		$data = array(
			'ID'	=> $new_post_id,
			'source_lang'	=> $source_language,
			'target_lang'	=> $target_language,
			'bulk'	=> true,
			'bulk_action'	=> $bulk_action,
			'force_polylang'	=> $force_polylang,
			'redirect'	=> false,
		);

		if( $mode == 'post' ) {
			$translated = deepl_translate_post_link( $data );
		}
		else  {
			$translated = deepl_translate_comment_link( $data );
		}
		if( WPDEEPLPRO_DEBUG ) {
			plouf( $data, " args de traduction ");
			plouf( $translated, " resultat");
		}


		if( $translated ) {
			$dp_translated++;
		}
		
		if( WPDEEPLPRO_DEBUG ) {
			plouf( $data, "data");
			plouf( $object_ids, "objects, action = $bulk_action, old post id $old_post_id ");
			plouf( $translated," translated");
			plouf( $dp_translated);
			$redirect_url = add_query_arg( 'dp-translated', $dp_translated, $redirect_url );
			plouf( $redirect_url );
		}

	}


	$redirect_url = add_query_arg( 'dp-translated', $dp_translated, $redirect_url );
	if( WPDEEPLPRO_DEBUG ) die("oezrozk redirect $redirect_url");

	if( WPDEEPLPRO_DEBUG ) {
		plouf( $data, "data");
		plouf( $object_ids, "objects, action = $bulk_action, old post id $old_post_id ");
		plouf( $translated," translated");
		plouf( $dp_translated);
		plouf( $redirect_url );
		die('ozeporj');
	}


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

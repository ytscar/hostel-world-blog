<?php



add_action( 'init', 'wpdeeplpro_manage_items_columns' );
function deeplpro_manage_items_columns() {

	$content_types = DeepLProConfiguration::getProPostTypes();
	if( $content_types ) foreach( $content_types as $content_type ) {
		add_filter( 'manage_' . $content_type . '_posts_columns', 'deeplpro_admin_columns' );
		add_action( 'manage_'. $content_type . '_posts_custom_column', 'deeplpro_admin_column', 10, 2);
	}
}

function deeplpro_admin_columns( $columns ) {
	$columns['wpdeepl_translation'] = __( 'Translation', 'wpdeepl' );
	return $columns;
}

function deeplpro_admin_column( $column, $post_id ) {
  // Image column
  if ( 'wpdeepl_translation' === $column ) {

  	global $post;
  	$target_locales = DeepLProConfiguration::getTargetLocales();
  	
  	$post_language = deeplpro_get_post_language( $post->ID );

  	if( $target_locales ) foreach( $target_locales as $target_locale ) {
  		$flag_content = file_get_contents( trailingslashit(  WPDEEPLPRO_PATH ) . 'flags/' . $target_locale . '.png' );
  		if( $post_language == substr($target_locale, 0, 2) ) {
  			echo '<a style="width: 16px; height: 16px">&nbsp;</a>' . "\n";
  		}
  		else {
	  		printf( 
	  			'<a href="%s" class="wpdeeplpro_ajax_translate" data-id="%d" data-target="%s"><img src="%s" /></a>' . "\n",
	  			'#',
	  			$post->ID,
	  			$target_locale,
	  			//'data:image/png;base64,' . base64_encode( $flag_content )
	  			trailingslashit( WPDEEPLPRO_URL ) . 'flags/' . $target_locale . '.png'

	  		);

  		}
  	}
  }
}



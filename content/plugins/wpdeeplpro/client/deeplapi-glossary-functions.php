<?php

function deeplpro_getOrphanedGlossaries() {
	global $wpdb;
	$sql = "SELECT option_name, option_value 
	FROM $wpdb->options 
	WHERE option_name LIKE 'wpdeepl_glossary%'
	";
	$glossaries = array();
	$results = $wpdb->get_results( $sql, ARRAY_A );
	if( $results ) foreach( $results as $result ) {
		$glossaries[$result['option_name']] = $result['option_value'];
	}
	return $glossaries;
}
function deeplpro_remove_glossary_selectors() {

	$glossaries = deepl_listGlossaries();
	$list = array();
	foreach( $glossaries as $glossary_id => $glossary_data ) {
		$pair = $glossary_data['source_lang'] . ' > ' . $glossary_data['target_lang'];
		$list[$pair][$glossary_id] = $glossary_data;
	}

	if( $list ) {
		echo '<h2>'; _e('Delete glossaries', 'wpdeepl'); echo '</h2>' ;?>
		<script type="text/javascript">
			function confirm_delete_glossary() {
			  return confirm('<?php _e('Are you sure you want to delete this glossary ?. This action is irreversible.', 'wpdeepl') ; ?>');
			}
		</script>
<?php 
		foreach( $list as $pair => $pair_list ) {
			if( $pair_list ) {
				printf('<h3>' . $pair . '</h3>');
				echo '
				<ul>';
				foreach( $pair_list as $glossary_id => $glossary_data ) {
					$link = admin_url('options-general.php?page=deepl_settings&tab=glossaries&action=delete_glossary&glossary_id=' . $glossary_id );
					$link = add_query_arg( '_wp_nonce', wp_create_nonce( 'delete_glossary' ), $link );
					printf(
						'<li><a href="%s" onclick="return confirm_delete_glossary(); ">%s</a></li>',
						$link,
						sprintf( 
							__('Delete glossary %s (%d entries)', 'wpdeepl'),
							$glossary_data['name'],
							$glossary_data['entry_count']
						)
					);
					echo "
					\n";

				}
				echo '
				</ul>';
			}
		}

	}
}

function deepl_listLanguagePairs() {

	$transient = get_transient('deepl_language_pairs' );
	if( $transient ) 
		return $transient;

	$DeepLApiGlossary = new DeepLApiGlossary();
	$languages_pairs = $DeepLApiGlossary->listLanguagePairs();

	set_transient( 'deepl_language_pairs', $languages_pairs, 24*3600*7 );
	return $languages_pairs;

}


function deepl_listGlossaryEntries( $glossary_id /*, $format = 'tsv' */) {
	$DeepLApiGlossary = new DeepLApiGlossary();
	$tsv = $DeepLApiGlossary->listGlossaryEntries( $glossary_id /* , $format */ );

//	var_dump( $tsv );

	$lines = explode("\n", $tsv);
	$entries = array();
	foreach( $lines as $line ) {
		$explode = explode("\t", $line );
		$entries[$explode[0]] = $explode[1];
	}
	return $entries;
}

function deepl_deleteGlosssary( $glossary_id ) {
	$DeepLApiGlossary = new DeepLApiGlossary();
	$return = $DeepLApiGlossary->deleteGlossary( $glossary_id );
	$update = true;
	deepl_listGlossaries( $update );
	return $return;

}

function deepl_listGlossaries( $update = false ) {

	if( get_option('wpdeepl_glossaries') && is_array( get_option('wpdeepl_glossaries') ) && !$update ) {
		return get_option('wpdeepl_glossaries');
	}

	$DeepLApiGlossary = new DeepLApiGlossary();
	$array = $DeepLApiGlossary->listGlossaries();
	$list = array();
	if( $array ) {
		foreach( $array as $glossary ) {
			$list[$glossary['glossary_id']] = $glossary;
		}
	}

	update_option( 'wpdeepl_glossaries', $list );
	return $list;

}
function deepl_createGlossary( $name, $data, $source_lang, $target_lang, $format ) {
	$DeepLApiGlossary = new DeepLApiGlossary();
	$glossary = $DeepLApiGlossary->createGlossary( $name, $data, $source_lang, $target_lang, $format );
	
	if( is_wp_error( $glossary ) ) {
		return $glossary;
	}
	/*if( !isset( $glossary['glossary_id'] ) ) {
		return new WP_Error( 'glossary', __('Glossary creation failed', 'wpdeepl' ) );
	}
	*/
	deepl_listGlossaries( true );
	return $glossary;
}



add_action('init', 'deeplpro_maybe_delete_glossary' );
function deeplpro_maybe_delete_glossary() {

	if( !isset( $_GET['action'] ) || $_GET['action'] != 'delete_glossary' ) 
		return;
	if( !isset( $_GET['glossary_id'] ) )
		return;
	if( !isset( $_GET['_wp_nonce'] ) || !wp_verify_nonce( $_GET['_wp_nonce'], 'delete_glossary' ) )
		return;

	$glossary_id = sanitize_text_field( $_GET['glossary_id'] );
	deepl_deleteGlosssary( $glossary_id );
	$url = admin_url('options-general.php?page=deepl_settings&tab=glossaries&deleted=1');
	wp_redirect( $url, 302 );
	die();


}

add_action('init', 'deeplpro_maybe_upload_glossary'); 
function deeplpro_maybe_upload_glossary() {
	
	if( !isset( $_POST['action'] ) || $_POST['action'] != 'deeplpro_glossary_file_upload' ) {
		return;
	}
	if( !wp_verify_nonce( $_POST['nonce'], 'upload_glossary' ) ) {
		return;
	}
	if( empty( $_POST['source_lang'] ) || empty( $_POST['target_lang'] ) ) {
		return;
	}


	$source_lang = htmlspecialchars_decode( $_POST['source_lang'] );
	$target_lang = htmlspecialchars_decode( $_POST['target_lang'] );
	if( $source_lang == $target_lang ) 
		return;

	$glossary_name = htmlspecialchars_decode( $_POST['glossary_name'] );

	$file_format = htmlspecialchars_decode( $_POST['file_format'] );
	$content = file_get_contents( $_FILES['glossary_file']['tmp_name'] ); 
	$lines = explode("\n", $content );

	$entries = array();
	if( $lines ) foreach( $lines as $line ) {
		if( $file_format == 'tsv' ) {
			$line = str_replace("\t\t", "\t", $line );
			$entry = str_getcsv( $line, "\t" );
		}
		else {
			//$line = str_replace(';;', ';', $line );
			$entry = str_getcsv( $line, ",");
		}
		foreach( $entry as $i => $value ) {
			$entry[$i] = trim( $value, '"\'');
			$entry[$i] = trim( $entry[$i], "\t");

			$entry[$i] = deeplpro_unicode_decode( $entry[$i] );
			/*
			$entry[$i] = preg_replace_callback('/[\x{80}-\x{10FFFF}]/u', function ($match) {
			    list($utf8) = $match;
			    $binary = iconv('UTF-8', 'UTF-32BE', $utf8);
			    $entity = vsprintf('&#x%X;', unpack('N', $binary));
			    return $entity;
			}, $entry[$i] );
			*/
		}
		$entries[] = $entry;
	}
	// unique entries
	$list = array();
	if( $entries ) foreach( $entries as $entry ) {
		$list[$entry[0]] = $entry[1];
	}

	$data = array();
	if( $list ) foreach( $list as $k => $v ) {
		$data[] = "$k\t$v";
	}
	$data = implode("\r\n", $data );

	//plouf( $data); 	var_dump( $data );	die('okzeorkzeorkzokr');


	$glossary = deepl_createGlossary( $glossary_name, $data, $source_lang, $target_lang, 'tsv' );
	$redirect_url = admin_url('admin.php?page=deepl_settings&tab=glossaries');

	if( is_wp_error( $glossary ) ) {
		$redirect_url = add_query_arg( array(
				'created'		=> 0,
				'error'		=> $glossary->get_error_message(),
			), 
			$redirect_url 
		);
		$error_message = __('Glossary creation: an error was encountered', 'wpdeepl');
		$error_message .= '<br /><pre>' . implode("\n", $glossary->get_all_error_data()[0] ) .'</pre>';
		wp_admin_notice(  $error_message, array('type'	=> 'error' ) );

	}
	else {
		$redirect_url = add_query_arg( array(
				'created'		=> 1,
				'glossary_id'		=> $glossary['glossary_id']
			), 
			$redirect_url 
		);
		wp_redirect( $redirect_url );
		exit();
	}
}
        

function deeplpro_add_glossary_html() {
	$bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
	$size = size_format( $bytes );


	if( isset( $_GET['created'] ) ) {
		if( $_GET['created'] == 1 ) {
			$glossary_id = htmlspecialchars_decode( $_GET['glossary_id'] );
			?>
			<div id="message" class="notice notice-success is-dismissible updated">
				<p>
				<?php
			printf( 
				__('Glossary %s was created', 'wpdeepl'),
				$glossary_id
			);
			?>
				</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">
						<?php
						/* translators: Hidden accessibility text. */
						_e( 'Dismiss this notice.' );
						?>
					</span>
				</button>
			</div>
			<?php 

		}
		else {
			$error_message = htmlspecialchars_decode( $_GET['error'] );
			?>
			<div id="message" class="notice notice-error is-dismissible updated">
				<p>
				<?php
			printf( 
				__('Unable to create glossary: %s', 'wpdeepl'),
				$error_message
			);
			?>
				</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">
						<?php
						/* translators: Hidden accessibility text. */
						_e( 'Dismiss this notice.' );
						?>
					</span>
				</button>
			</div>
			<?php 

		}
	}
	//deepl_listGlossaries( true );
	?>

	<form enctype="multipart/form-data" id="deeplpro_upload_glossary" method="post" class="wp-upload-form" action="?page=<?php echo admin_url('admin.php?page=deepl_settings&tab=glossaries'); ?>">
		<?php wp_nonce_field( 'upload_glossary', 'nonce' ); ?>
			<h3><?php _e( 'Upload a glossary', 'wpdeepl'); ?></h3>
			<p>
				<label for="glossary_file"><?php _e( 'Choose a file from your computer:' ); ?></label> (<?php printf( __('Maximum size: %s' ), $size ); ?>)
				<input type="file" id="glossary_file" name="glossary_file" size="25" />
				<p class="description"><?php 
				_e('The file must be either in CSV or TSV format. CSV entries must be separated with commas (,).', 'wpdeepl' ); ?></p>
			</p>

			<p>
				<label for="glossary_name"><?php _e('Glossary name','wpdeepl' );?>	</label>
				<input type="text" name="glossary_name" style="width: 25rem;" />
			<p>

			<p>
				<label for="file_format"><?php _e('File format','wpdeepl' );?>	</label>
				<select name="file_format">
					<option value="csv">CSV</option>
					<option value="tsv">TSV</option>
				</select>
			<p>
				<label for="source_lang"><?php _e('Source language', 'wpdeepl' ); ?></label>
				<?php echo deeplpro_glossary_language_selector( 'source' ); ?>
				<label for="source_lang"><?php _e('Target language', 'wpdeepl' ); ?></label>
				<?php echo deeplpro_glossary_language_selector( 'target' ); ?>
			</p>

			<input type="hidden" name="action" value="deeplpro_glossary_file_upload" />
			<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
			</p>
			<?php submit_button( __('Upload file'), 'primary' ); ?>
			</form>

	<?php
}




function deeplpro_glossary_language_selector( $type = 'target' ) {


	$languages_pairs = deepl_listLanguagePairs();
	$sources = array();
	$targets = array();
	foreach( $languages_pairs as $languages_pair ) {
		$sources[] = $languages_pair['source_lang'];
		$targets[] = $languages_pair['target_lang'];
	}
	$sources = array_values( array_unique( $sources ) );
	$targets = array_values( array_unique( $sources ) );

	if( $type == 'target' ) {
		$list = $targets;
	}
	else {
		$list = $sources;
	}

	$languages = array();
	foreach( $list as $ln ) {
		$languages[$ln] = DeepLConfiguration::getLocaleNameForIsoCode2( $ln, $type );
	}

	$name = $type . '_lang';
	$html = '';

	$html .= "\n" . '<select id="' . $name. '" name="' . $name . '">';

	foreach ( $languages as $ln_id => $label ) {

		$html .= '
		<option value="' . $ln_id .'"';
		$html .= '>' . $label. '</option>';
	}
	$html .="\n</select>";
	return $html;
}




function deeplpro_display_glossaries() {
	$glossaries = deepl_listGlossaries();

	if( $glossaries && count( $glossaries ) ) {
		echo '
		<ul id="glossaries">';
		foreach( $glossaries as $glossary ) {
			echo  '
			<li>';
			printf(
				__( '<li>%s : %s > %s', 'wpdeepl' ),
				$glossary['name'],
				$glossary['source_lang'],
				$glossary['target_lang']
			);
			printf(
				__(' : <a href="%s">%d entries</a>', 'wpdeepl' ),
				admin_url( 'options-general.php?page=deepl_settings&tab=glossaries&glossary_id=' . $glossary['glossary_id'] ),
				$glossary['entry_count']
			);

			if( isset( $_GET['glossary_id'] ) && $_GET['glossary_id'] == $glossary['glossary_id'] ) {
				echo '
				<ul class="entries">';
				$entries = deepl_listGlossaryEntries( $glossary['glossary_id'] );
				$entries = explode("\n", $entries );
				if( count( $entries )  ) foreach( $entries as $entry ) {
					$explode = explode("\t", $entry );
					printf( 
						__('<li>%s : %s</li>', 'wpdeepl'),
						$explode[0], 
						$explode[1] 
					);
				}
				echo  '
				</ul>';
			}


		}

	}
	else {
		_e('No glossary found', 'wpdeepl');
	}

}



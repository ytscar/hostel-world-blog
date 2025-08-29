<?php

if ( !class_exists( 'WP_Improved_Settings\WP_Improved_Settings' ) ) {
	require( dirname( __FILE__ ) . '/wp-improved-settings.class.php' );
}

class WP_Improved_Settings_DeepL extends WP_Improved_Settings\WP_Improved_Settings {

	public $plugin_id = 'wpdeepl';
	public $option_page = 'deepl_settings';
	public $menu_order = 15;
	public $parent_menu = 'options-general.php';
	public $defaultSettingsTab = 'ids';

	public $settingsStructure = array();

	public $minimum_capability = 'manage_options';

	public $log_folder = WPDEEPL_FILES;

	//public $configurationClass = 'WooCEGIDConfiguration';
	//public $post_type = 'devis';
	public $extendedActions = array(
		'prune_logs'				=> 'wpdeepl_prune_logs',
		'generate_csv'				=> 'admin_cegid_generate_csv',

	);


	static function geti18nDomain() {
		return false;
	}


	static function getPageTitle() {
		return __( 'DeepL settings', 'wpdeepl' );
	}

	static function getMenuTitle() {
		return __( 'DeepL translation', 'wpdeepl' );
	}

	function before_save() {
	}

	function on_save() {
		update_option( 'deepl_plugin_installed', 1 );

	}




	function maybe_print_notices() {
		$fully_configured = deepl_is_plugin_fully_configured();
		if ( $fully_configured !== true ) {
			$class = 'notice notice-error';

			$messages = array();

			if ( is_wp_error( $fully_configured ) ) {
				foreach ( $fully_configured->get_error_codes() as $error_code ) {
					foreach ( $fully_configured->get_error_messages( $error_code ) as $error_message ) {
						$messages[] = sprintf(
							__( '<li><a href="%s">%s</a></li>', 'wpdeepl' ),
							admin_url( '/' . $this->parent_menu . '?page=' . $this->option_page . '&tab=' . $error_code ),
							$error_message
						);
					}
				}
			}
			$message = sprintf(
				__( 'The DeepL plugin is not fully configured yet: <ul>%s</ul>', 'wpdeepl' ),
				implode( "\n", $messages )
			);
			if ( count( $messages ) ) {
				$message .= sprintf(
					__( '<a href="%s">Please provide required informations</a>', 'wpdeepl' ),
					admin_url( '/' . $this->parent_menu . '?page=' . $this->option_page )
				);
			}

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), ( $message ) );
		}
	}

	function getSettingsStructure() {

		if( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] !== 'deepl_settings' ) 
			return array();

		$settings = array(
			'ids'			=> array(
				'title'			=> __( 'Credentials', 'wpdeepl' ),
				'sections'		=> array()
			),
			'translation' 	=> array(
				'title'			=> __( 'Translation', 'wpdeepl' ),
				'sections'		=> array()
			),
			/*'glossaries'	=> array(
				'title'			=> __('Glossaries', 'wpdeepl') ,
				'sections'		=> array(),
			),*/
			'integration'	=> array(
				'title'			=> __( 'Integration', 'wpdeepl' ),
				'sections'		=> array()
			),
			'maintenance'	=> array(
				'title'			=> __('Maintenance', 'wpdeepl' ),
				'sections'		=> array(),
			),

		);

		/** IDS **/

		$servers = array();
		$possibilities = DeepLConfiguration::getDeeplAPIServers();
		foreach ( $possibilities as $key => $data ) {
			$servers[$key] = $data['description'];
		}

		$settings['ids']['sections']['identifiants'] = array(
			'title'			=> __( 'DeepL credentials', 'wpdeepl' ),
			'fields'	=> array(

				array(
					'id'			=> 'api_key',
					'title'			=> __( 'API Key', 'wpdeepl' ),
					'type'			=> 'text',
					'css'			=> 'width: 20em;',
					'description'	=> sprintf(
						__( '<a target="_blank" href="%s">Create a DeepL Pro account here</a>. Get your <a target="_blank" href="%s">API key there</a>', 'wpdeepl' ),
						'https://www.deepl.com/pro/',
						'https://www.deepl.com/account/summary'
					),
				),
				array(
					'id'			=> 'api_server',
					'title'			=> __( 'API Server', 'wpdeepl' ),
					'type'			=> 'select',
					'css'			=> 'width: 30em;',
					'options'		=> $servers,
					'description'	=> sprintf(
						__( 'Pick a plan on <a href="https://www.deepl.com/pro-account/plan">%s</a>', 'wpdeepl'),
						'https://www.deepl.com/'
					),
				),


			)
		);


		if ( DeepLConfiguration::getAPIKey() ) {
			$settings['ids']['footer']['actions'] = array( 'deepl_show_usage' );
		/** END IDS **/

		/** TRANSLATION **/

			$formality_levels = array(
				'default'	=> __('default', 'wpdeepl' ),
				'more'		=> __('formal', 'wpdeepl' ),
				'less'		=> __('informal', 'wpdeepl' ),
			);

			$settings['translation']['sections']['languages'] = array(
				'title'		=> __( 'Translation', 'wpdeepl' ),
				'fields'	=> array(
					array(
						'id'			=> 'default_language',
						'title'			=> __( 'Default target language', 'wpdeepl' ),
						'type'			=> 'select',
						'options'		=> DeepLConfiguration::DefaultsISOCodes(),
						'default'		=> substr( get_locale(), 0, 2 ),
						'css'			=> 'width: 15rem; ',
					),
					array(
						'id'			=> 'allow_front_end',
						'title'			=> __( 'Allow front end usage', 'wpdeepl' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'description'	=> __('By default, this plugin is only loaded on the admin side and never interacts with the front end. If you need to use the plugin on the front side of your website, please check this box', 'wpdeepl' ),
					),


					array(
						'id'			=> 'displayed_languages',
						'title'			=> __( 'Displayed languages', 'wpdeepl' ),
						'type'			=> 'multiselect',
						'options'		=> DeepLConfiguration::DefaultsISOCodes(),
						'default'		=> substr( get_locale(), 0, 2 ),
						'css'			=> 'width: 15rem; height: 20rem;',
					),
				)
			);

			$settings['translation']['sections']['default'] = array(
				'title'		=> __( 'Translation by default', 'wpdeepl' ),
				'fields'	=> array(
					array(
						'id'			=> 'tpost_title',
						'title'			=> __( 'Post title', 'wpdeepl' ),
						'type'			=> 'checkbox',
						'default'		=> 'on',
					),
					array(
						'id'			=> 'tpost_excerpt',
						'title'			=> __( 'Post excerpt', 'wpdeepl' ),
						'type'			=> 'checkbox',
						'default'		=> 'on',
					),
					array(
						'id'			=> 'tpost_content',
						'title'			=> __( 'Post content', 'wpdeepl' ),
						'type'			=> 'checkbox',
						'default'		=> 'on',
					),

				)
			);

			$settings['translation']['sections']['formality'] = array(
				'title'	=> __( 'Formality level', 'wpdeepl' ),
				'fields' => array(
					array(
						'id'			=> 'default_formality',
						'title'			=> __( 'Default', 'default' ),
						'type'			=> 'select',
						'options'		=> $formality_levels,
						'default'		=> 'default',
					),
				)
			);
			$displayed_languages = DeepLConfiguration::getDisplayedLanguages();

			$allowed_formality_languages = DeepLConfiguration::getLanguagesAllowingFormality();

			
			if( $displayed_languages ) foreach( $displayed_languages as $language ) {
				if( in_array( $language, $allowed_formality_languages ) ) {
					$language_name = DeepLConfiguration::validateLang( $language, 'label' );
					$settings['translation']['sections']['formality']['fields'][] = array(
						'id'			=> 'formality_'. $language,
						'title'			=> sprintf( __( 'Formality level : %s', 'wpdeepl' ), $language_name ),
						'type'			=> 'select',
						'options'		=> $formality_levels,
						'default'		=> 'default',
					);

				}
				else {
					$language_name = DeepLConfiguration::validateLang( $language, 'label' );
					$settings['translation']['sections']['formality']['fields'][] = array(
						'id'			=> 'formality_'. $language,
						'title'			=> sprintf( __( 'Formality level : %s', 'wpdeepl' ), $language_name ),
						'type'			=> 'select',
						'options'		=> array('default'),
						'default'		=> 'default',
						'css'			=> 'background-color: #c3c4c7',
					);					
				}
			}

		}

		/** END TRANSLATION **/

		/** INTEGRATION **/
		$wp_post_types = get_post_types( array( 'public'	=> true, 'show_ui' => true ), 'objects' );
		$post_types = array();
		if ( $wp_post_types ) foreach ( $wp_post_types as $post_type => $WP_Post_Type ) {
			$post_types[$post_type] = $WP_Post_Type->label;
		}
		// I see what you're doing here
		unset( $post_types['product'] );
		// no sense translating orders
		unset( $post_types['shop_order'] );
		
		$post_types = apply_filters( 'deepl_metabox_post_types', $post_types );

		$default_metabox_behaviours = DeepLConfiguration::DefaultsMetaboxBehaviours();

		//plouf($post_types); 		plouf( DeeplConfiguration::getMetaBoxPostTypes() , "saved");

		$settings['integration']['sections']['metabox'] = array(
			'title'			=> __( 'Metabox', 'wpdeepl' ),
			'fields'	=> array(
				array(
					'id'			=> 'metabox_post_types',
					'title'			=> __( 'Metabox should be displayed on:', 'wpdeepl' ),
					'type'			=> 'multiselect',
					'options'		=> $post_types,
					'css'			=> 'height: '. count( $post_types ) *1.5 .'rem;',
					'default'		=> array( 'post', 'page' ),
					'description'	=> __( 'Select which post types you want the metabox to appear on. To duplicate posts in different languages (as a starting point for translation), it might be useful to use the "Duplicate post" feature of Polylang Pro, or a "Duplicate post" plugin.', 'wpdeepl' ),
 				), 
/*
				array(
					'id'			=> 'metabox_behaviour',
					'title'			=> __( 'Default behaviour', 'wpdeepl' ),
					'type'			=> 'radio',
					'values'		=> $default_metabox_behaviours,
					'default'		=> 'replace',
					'description'	=> __( 'For content to be appended, you need to use a supported multilingual plugin', 'wpdeepl' ),
 				),
 				*/
 				array(
					'id'			=> 'metabox_context',
					'title'			=> __( 'Metabox context', 'wpdeepl' ),
					'type'			=> 'select',
					'options'		=> array(
						'normal' 		=> 'normal',
						 'side' 		=> 'side',
						 'advanced'		=> 'advanced'
					),
					'default'		=> 'side',
					'description'	=> __("'Side' = metabox on the side column, 'Normal' = on the main column",'wpdeepl' ),
 				),
 				array(
					'id'			=> 'metabox_priority',
					'title'			=> __( 'Metabox priority', 'wpdeepl' ),
					'type'			=> 'select',
					'options'		=> array(
						'high'			=> 'high',
						'low'			=> 'low'
					),
					'default'		=> 'high',
					'description'	=> __('Position of the metabox in the column', 'wpdeepl' ),
 				),
			)
		);

		$settings['maintenance']['sections']['logs'] = array(
			'title'		=> __('Logging', 'wpdeepl' ),
			'fields'	=> array(
				array(
					'id'			=> 'log_level',
					'title'			=> __( 'Log level', 'wpdeepl' ),
					'type'			=> 'select',
					'options'		=> array(
						'0'	=> __('None','wpdeepl' ),
						'1'	=> __('Minimal','wpdeepl' ),
						'2'	=> __('Full','wpdeepl' ),
					),
					'default'		=> 0,
 				),
			)
		);

		$admin_url = get_admin_url(null, '/' . $this->parent_menu . '?page=' . $this->option_page . '&tab=maintenance&prune_logs=1' );
		$admin_url = wp_nonce_url( $admin_url, 'prune_logs', 'nonce' );
	    

		$message =
		
			'<a href="'
			. $admin_url
			.'" class="button button-primary">'
			. __( 'Delete logs from previous months', 'wpdeepl' )
			. '</a>';

		$settings['maintenance']['footer']['html'] = array( $message );

		$settings['maintenance']['footer']['actions'] = array( 'wpdeepl_test_admin', array( $this, 'showServerInfo'), array( $this, 'showLogs') );

		return  apply_filters( 'deepl_admin_configuration', $settings );
	}
}
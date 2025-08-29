<?php defined( 'ABSPATH' ) or die();

class BrizyPro_Admin_Membership_Membership {

	const CP_ROLE = 'editor_roles';

	public static function _init() {

		static $instance;

		return $instance ? $instance : $instance = new self();
	}

	private function __construct() {

		if ( Brizy_Editor_User::is_user_allowed() ) {
			add_action( 'brizy_avaible_roles', [ $this, 'roleList' ] );
		}

		if ( current_user_can( 'manage_options' ) || current_user_can( Brizy_Admin_Capabilities::CAP_EDIT_WHOLE_PAGE ) ) {
			add_action( 'enter_title_here',                   [ $this, 'enter_title_here' ] );
			add_action( 'transition_post_status',             [ $this, 'transition_post_status' ], 10, 3 );
			add_filter( 'post_updated_messages',              [ $this, 'post_updated_messages' ] );
			add_filter( 'post_row_actions',                   [ $this, 'post_row_actions' ], 10, 1 );
			add_filter( 'bulk_actions-edit-' . self::CP_ROLE, [ $this, 'bulk_actions' ] );
			add_filter( 'admin_enqueue_scripts',              [ $this, 'admin_enqueue_scripts' ] );
			add_filter( 'admin_head',                         [ $this, 'admin_head' ] );
			add_action( 'show_user_profile',                  [ $this, 'output_checklist' ] );
			add_action( 'edit_user_profile',                  [ $this, 'output_checklist' ] );
			add_action( 'user_new_form',                      [ $this, 'output_checklist' ] );
			add_action( 'profile_update',                     [ $this, 'profile_update' ] );

			if ( is_multisite() ) {
				add_action( 'after_signup_user',  [ $this, 'after_signup_user' ], 10, 4 );
				add_action( 'wpmu_activate_user', [ $this, 'wpmu_activate_user' ], 10, 3 );
				add_action( 'added_existing_user', [ $this, 'profile_update' ] );
			} else {
				add_action( 'user_register', [ $this, 'profile_update' ] );
			}
		}
	}

	static public function registerCustomPostRoles() {

		$labels = [
			'name'               => _x( 'Roles', 'post type general name', 'brizy-pro' ),
			'singular_name'      => _x( 'Role', 'post type singular name', 'brizy-pro' ),
			'menu_name'          => _x( 'Roles', 'admin menu', 'brizy-pro' ),
			'name_admin_bar'     => _x( 'Role', 'add new on admin bar', 'brizy-pro' ),
			'add_new'            => _x( 'Add Role', 'add new post type role', 'brizy-pro' ),
			'add_new_item'       => __( 'Add New Role', 'brizy-pro' ),
			'new_item'           => __( 'New Role', 'brizy-pro' ),
			'edit_item'          => __( 'Edit Role', 'brizy-pro' ),
			'view_item'          => __( 'View Role', 'brizy-pro' ),
			'all_items'          => __( 'Roles', 'brizy-pro' ),
			'search_items'       => __( 'Search Role', 'brizy-pro' ),
			'parent_item_colon'  => __( 'Parent Role:', 'brizy-pro' ),
			'not_found'          => __( 'No Roles found.', 'brizy-pro' ),
			'not_found_in_trash' => __( 'No Roles found in Trash.', 'brizy-pro' )
		];

		register_post_type( self::CP_ROLE, [
				'labels'              => $labels,
				'public'              => false,
				'has_archive'         => false,
				'description'         => __bt( 'brizy', 'Brizy' ) . ' ' . __( 'roles', 'brizy-pro' ) . '.',
				'publicly_queryable'  => false,
				'show_ui'             => is_user_logged_in() && ( current_user_can( 'manage_options' ) || current_user_can( Brizy_Admin_Capabilities::CAP_EDIT_WHOLE_PAGE )  ),
				'show_in_menu'        => 'users.php',
				'query_var'           => false,
				'rewrite'             => [ 'slug' => 'editor-roles' ],
				'capability_type'     => 'page',
				'hierarchical'        => false,
				'show_in_rest'        => false,
				'can_export'          => true,
				'exclude_from_search' => true,
				'supports'            => [ 'title' ]
			]
		);
	}

	public function enter_title_here( $title ) {

		if ( ! $this->is_screen_roles() ) {
			return $title;
		}

		return esc_html__( 'Enter Role Name', 'brizy-pro' );
	}

	private function is_screen_roles() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();

		return ! ( empty( $screen->post_type ) || self::CP_ROLE !== $screen->post_type );
	}

	public function transition_post_status( $new_status, $old_status, $post ) {

		if ( ! $this->can_update_roles() || $post->post_type !== self::CP_ROLE ) {
			return;
		}

		$postID = $post->ID;

		if ( $old_status == 'publish' && $new_status != 'publish' ) {
			remove_role( get_post_meta( $postID, 'role_id', true ) );
		}

		if ( $new_status === 'publish' ) {
			$role_id         = get_post_meta( $postID, 'role_id', true );
			$roleDisplayName = empty( $post->post_title ) ? 'Role #' . $postID : $post->post_title;

			if ( ! $role_id ) {
				$role_id = sanitize_title_with_dashes( $roleDisplayName ) . '-' . $postID;
				update_post_meta( $postID, 'role_id', $role_id );
			}

			remove_role( $role_id );

			add_role( $role_id, $roleDisplayName );
		}
	}

	public function post_updated_messages( $messages ) {

		$messages[ self::CP_ROLE ] = [
			0  => '',
			1  => __( 'Role updated.', 'brizy-pro' ),
			2  => __( 'Custom field updated.', 'brizy-pro' ),
			3  => __( 'Custom field deleted.', 'brizy-pro' ),
			4  => __( 'Role updated.', 'brizy-pro' ),
			6  => __( 'Role published.', 'brizy-pro' ),
			7  => __( 'Role saved.', 'brizy-pro' ),
			8  => __( 'Role submitted.', 'brizy-pro' ),
		];

		return $messages;
	}

	public function post_row_actions( $actions ) {

		if ( ! $this->is_screen_roles() ) {
			return $actions;
		}

		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}

	public function bulk_actions( $actions ){
		unset( $actions[ 'edit' ] );
		return $actions;
	}

	public function admin_enqueue_scripts() {
		global $pagenow;

		if ( 'user-edit.php' !== $pagenow && 'user-new.php' !== $pagenow && 'profile.php' !== $pagenow ) {
			return;
		}

		if ( ! $this->can_update_roles() ) {
			return;
		}

		wp_register_script( 'editor-add-roles-checklist', '', [ 'jquery' ], '', true );
		wp_enqueue_script( 'editor-add-roles-checklist' );

		$script =
			"jQuery( document ).ready( function( $ ) {
				var checklist = $( '.editor-checklist-roles' ).html(),
					labelText = $( '.editor-checklist-roles' ).attr( 'data-label-text' );
				
                $( '.user-role-wrap th label' ).text( labelText );
                $( '.user-role-wrap td' ).html( checklist );
                
                $( '#adduser-role' ).closest( '.form-field' ).find( 'th' ).text( labelText );
                $( '#adduser-role' ).parent( 'td' ).html( checklist );
                
                $( '#role' ).closest( '.form-field' ).find( 'th' ).text( labelText );
                $( '#role' ).hide().after( checklist );
                
                $( '.editor-checklist-roles' ).remove();
            } );";

		wp_add_inline_script( 'editor-add-roles-checklist', $script );
	}

	public function admin_head() {

		if ( ! $this->is_screen_roles() ) {
			return;
		}

		echo '<style>#minor-publishing{display:none;}</style>';
	}

	public function output_checklist( $user ) {

		if ( ! $this->can_update_roles() ) {
			return;
		}

		$roles          = $this->get_editable_roles();
		$user_roles     = isset( $user->roles ) ? $user->roles : null;
		$creating       = isset( $_POST['createuser'] );
		$selected_roles = $creating && isset( $_POST['editor_multiple_roles'] ) ? wp_unslash( $_POST['editor_multiple_roles'] ) : '';

		echo '<div class="editor-checklist-roles" style="display:none;" data-label-text="' . esc_attr( __( 'Roles' ) ) . '">';

		foreach( $roles as $name => $label ) {
			$checked = '';

			if ( ! is_null( $user_roles ) ) {
				$checked = checked( in_array( $name, $user_roles ), true, false );
			} elseif ( ! empty( $selected_roles ) ) {
				$checked = checked( in_array( $name, $selected_roles ), true, false );
			}

			echo
				'<label>
					<input type="checkbox" name="editor_multiple_roles[]" style="width:auto;" value="' . esc_attr( $name ) . '" ' . $checked . '>' .
					esc_html( translate_user_role( $label ) ) .
				'</label>
				<br>';
		}

		echo '</div>';
	}

	/**
	 * Update the given user's roles as long as we've passed the nonce
	 * and permissions checks.
	 *
	 * @param int $user_id The user ID whose roles might get updated.
	 */
	public function profile_update( $user_id ) {

		if ( ! $this->can_update_roles() ) {
			return;
		}

		$addNewUser    = isset( $_POST['_wpnonce_create-user'] ) ? wp_verify_nonce( $_POST['_wpnonce_create-user'], 'create-user' ) : false;
		$createNewUser = isset( $_POST['_wpnonce_add-user'] ) ? wp_verify_nonce( $_POST['_wpnonce_add-user'], 'add-user' ) : false;
		$editUser      = isset( $_POST['_wpnonce'] ) ? wp_verify_nonce( $_POST['_wpnonce'], "update-user_{$user_id}" ) : false;

		if ( ! $addNewUser && ! $createNewUser && ! $editUser ) {
			return;
		}

		$roles = isset( $_POST['editor_multiple_roles'] ) && is_array( $_POST['editor_multiple_roles'] ) ? $_POST['editor_multiple_roles'] : [];

		$this->update_roles( $user_id, $roles );
	}

	/**
	 * Add multiple roles in the $meta array in wp_signups db table
	 *
	 * @param $user
	 * @param $user_email
	 * @param $key
	 * @param $meta
	 *
	 * @return void|WP_Error
	 */
	public function after_signup_user( $user, $user_email, $key, $meta ) {

		if ( ! $this->can_update_roles() ) {
			return;
		}

		$addNewUser    = isset( $_POST['_wpnonce_add-user'] ) ? wp_verify_nonce( $_POST['_wpnonce_add-user'], 'add-user' ) : false;
		$createNewUser = isset( $_POST['_wpnonce_create-user'] ) ? wp_verify_nonce( $_POST['_wpnonce_create-user'], 'create-user' ) : false;

		if ( ! $addNewUser && ! $createNewUser ) {
			return;
		}

		global $wpdb;

		// Get user signup
		// Suppress errors in case the table doesn't exist
		$suppress = $wpdb->suppress_errors();
		$signup   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->signups} WHERE user_email = %s", $user_email ) );
		$wpdb->suppress_errors( $suppress );

		if ( empty( $signup ) || is_wp_error( $signup ) ) {
			return new WP_Error( 'editor_get_user_signups_failed' );
		}

		// Add multiple roles to a new array in meta var
		$roles = isset( $_POST['editor_multiple_roles'] ) && is_array( $_POST['editor_multiple_roles'] ) ? $_POST['editor_multiple_roles'] : [];
		$meta = maybe_unserialize( $meta );
		$meta['editor_roles'] = $roles;
		$meta = maybe_serialize( $meta );

		// Update user signup with good meta
		$where        = [ 'signup_id' => (int) $signup->signup_id ];
		$where_format = [ '%d' ];
		$formats      = [ '%s' ];
		$fields       = [ 'meta' => $meta ];
		$result       = $wpdb->update( $wpdb->signups, $fields, $where, $formats, $where_format );

		// Check for errors
		if ( empty( $result ) && ! empty( $wpdb->last_error ) ) {
			return new WP_Error( 'editor_update_user_signups_failed' );
		}
	}

	/**
	 * Add multiple roles after user activation
	 *
	 * @param $user_id
	 * @param $password
	 * @param $meta
	 */
	public function wpmu_activate_user( $user_id, $password, $meta ) {
		if ( ! empty( $meta['editor_roles'] ) ) {
			$this->update_roles( $user_id, $meta['editor_roles'] );
		}
	}

	/**
	 * Erase the user's existing roles and replace them with the new array.
	 *
	 * @param integer $user_id The WordPress user ID.
	 * @param array $roles The new array of roles for the user.
	 *
	 * @return bool
	 */
	public function update_roles( $user_id = 0, $roles = [] ) {

		$roles = array_map( 'sanitize_key', (array) $roles );
		$roles = array_filter( (array) $roles, 'get_role' );

		$user = get_user_by( 'id', (int) $user_id );

		// Remove all editable roles
		$editable = get_editable_roles();
		$editable_roles = is_array( $editable ) ? array_keys( $editable ) : [];

		foreach( $editable_roles as $role ) {
			$user->remove_role( $role );
		}

		foreach( $roles as $role ) {
			$user->add_role( $role );
		}

		return true;
	}

	/**
	 * Check whether or not a user can edit roles. User must have the edit_roles cap and
	 * must be on a specific site (and not in the network admin area). Users also can't
	 * edit their own roles unless they're a network admin.
	 *
	 * @return bool True if current user can update roles, false if not.
	 */
	public function can_update_roles() {

		if ( is_network_admin() || ! current_user_can( 'promote_users' ) || ( defined( 'IS_PROFILE_PAGE' ) && IS_PROFILE_PAGE && ! current_user_can( 'manage_sites' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get all editable roles by the current user
	 *
	 * @return array editable roles
	 */
	public function get_editable_roles() {

		$editable_roles = get_editable_roles();
		$final_roles    = [];

		foreach ( $editable_roles as $key => $role ) {
			$final_roles[ $key ] = $role['name'];
		}

		return $final_roles;
	}

	/**
	 * @return array
	 */
	public function roleList() {
		$editable_roles = apply_filters( 'editable_roles', wp_roles()->roles );
		$wpRoles        = [ 'customer', 'shop_manager', 'subscriber', 'contributor', 'author', 'editor', 'administrator' ];
		$out            = [];

		$roles = array_filter( $editable_roles, function( $key ) {
			if ( ! strpos( $key, '-' ) ) {
				return false;
			}

			$parts = explode( '-', $key );

			return get_post_type( end( $parts ) ) == BrizyPro_Admin_Membership_Membership::CP_ROLE;
		}, ARRAY_FILTER_USE_KEY );

		foreach ( $wpRoles as $role ) {
			if ( isset( $editable_roles[ $role ] ) ) {
				$roles[ $role ] = $editable_roles[ $role ];
			}
		}

		$roles = array_merge( $roles, array_diff_key( $editable_roles, $roles ) );

		foreach ( $roles as $role => $details ) {
			$out[] = [
				'role' => esc_attr( $role ),
				'name' => translate_user_role( $details['name'] )
			];
		}

		return $out;
	}
}
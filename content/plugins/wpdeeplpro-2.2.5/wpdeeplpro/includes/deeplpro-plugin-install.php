<?php

function deeplpro_is_plugin_fully_configured() {

	return true;

	$WP_Error = new WP_Error();


	if( count( $WP_Error->get_error_messages() ) ) {
		return $WP_Error;
	}
	return true;
}

function deeplpro_install_plugin() {

	$default_values = array();

	foreach( $default_values as $key => $value ) {
		if( !get_option( $key ) ) {
			update_option( $key, $value );
		}
	}

	update_option( 'wpdeeplpro_plugin_installed', 1 );
}


function deeplpro_maybe_activate_wpdeepl(){
	$plugins = get_plugins();
	if( isset( $plugins['wpdeepl/wpdeepl.php'] ) ) {
		activate_plugin( 'wpdeepl/wpdeepl.php' );
	}
	else {
		//deactivate_plugins( 'wpdeeplpro/wpdeeplpro.php', true, true );

	}
}
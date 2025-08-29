<?php
/*
Plugin Name:    Custom Taxonomy Templates
Description:    Create and define custom templates for taxonomy archive views just like you do for custom page templates.
Author:         Hassan Derakhshandeh
Version:        0.2.1
Author URI:     
Text Domain:    custom-taxonomy-templates
Domain Path:    /languages

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Custom_Taxonomy_Templates {

	var $meta_key;

	function __construct() {
		add_action( 'init', array( $this, 'init' ), 100 );
		if( is_admin() ) {
			add_action( 'init', array( $this, 'i18n' ) );
		} else {
			add_filter( 'category_template', array( $this, 'template' ) );
			add_filter( 'tag_template', array( $this, 'template' ) );
			add_filter( 'taxonomy_template', array( $this, 'template' ) );
		}
	}

	public function i18n() {
		load_plugin_textdomain( 'custom-taxonomy-templates', false, '/languages' );
	}

	public function init() {
		$this->meta_key = apply_filters( 'custom_taxonomy_templates_meta_key', '_custom_template' );

		$taxonomies = get_taxonomies( array( 'public' => true ) );
		if( empty( $taxonomies ) )
			return;

		foreach( $taxonomies as $taxonomy ) {
			add_action( "{$taxonomy}_add_form_fields", array( $this, 'add_template_option' ) );
			add_action( "{$taxonomy}_edit_form_fields", array( $this, 'edit_template_option' ), 10, 2 );
			add_action( "created_{$taxonomy}", array( $this, 'save_option' ), 10, 2 );
			add_action( "edited_{$taxonomy}", array( $this, 'save_option' ), 10, 2 );
			add_action( "delete_{$taxonomy}", array( $this, 'delete_option' ) );
		}
	}

	function template( $template ) {
		$term = get_queried_object();
		$template = get_term_meta( $term->term_id, $this->meta_key, true );
		if( ! empty( $template ) ) {
			$tmpl = locate_template( $template );
			/* verify that the template file exists */
			if( '' !== $tmpl ) {
				add_filter( 'body_class', array( $this, 'body_class' ) );
				return $tmpl;
			}
		}

		return $template;
	}

	function body_class( $classes ) {
		$term = get_queried_object();
		$template = get_term_meta( $term->term_id, $this->meta_key, true );
		$template = sanitize_html_class( str_replace( '.', '-', $template ) );
		$classes[] = 'taxonomy-template-' . $template;

		return $classes;
	}

	function save_option( $term_id ) {
		if( isset( $_POST['custom-taxonomy-template'] ) ) {
			$template = trim( $_POST['custom-taxonomy-template'] );
			if( 'default' == $template ) {
				delete_term_meta( $term_id, $this->meta_key );
			} else {
				update_term_meta( $term_id, $this->meta_key, $template );
			}
		}
	}

	function add_template_option( $taxonomy ) {
		$category_templates = $this->get_templates( $taxonomy );
		if( empty( $category_templates ) )
			return;

		?>
		<div class="form-field custom-taxonomy-template">
			<label for="custom-taxonomy-template"><?php _e( 'Template', 'custom-taxonomy-templates' ); ?></label>
			<select name="custom-taxonomy-template" id="custom-taxonomy-template" class="postform">
				<option value="default"><?php _e( 'Default Template', 'custom-taxonomy-templates' ); ?></option>
				<?php $this->templates_dropdown( $taxonomy ) ?>
			</select>
		</div>
	<?php }

	function edit_template_option( $tag, $taxonomy ) {
		$category_templates = $this->get_templates( $taxonomy );
		if( empty( $category_templates ) )
			return;

		$template = get_term_meta( $tag->term_id, $this->meta_key, true );
		?>
		<tr class="form-field custom-taxonomy-template">
			<th scope="row" valign="top">
				<label for="custom-taxonomy-template"><?php _e( 'Template', 'custom-taxonomy-templates' ); ?></label>
			</th>
			<td>
				<select name="custom-taxonomy-template" id="custom-taxonomy-template" class="postform">
					<option value="default"><?php _e( 'Default Template', 'custom-taxonomy-templates' ); ?></option>
					<?php $this->templates_dropdown( $taxonomy, $template ) ?>
				</select>
			</td>
		</tr>
	<?php }

	function delete_option( $term_id ) {
		delete_term_meta( $term_id, $this->meta_key );
	}

	/**
	 * Generate the options for the category templates list
	 *
	 * @since 0.1
	 * @return void
	 */
	function templates_dropdown( $taxonomy = 'category', $default = null ) {
		$templates = array_flip( $this->get_templates( $taxonomy ) );
		ksort( $templates );
		foreach( array_keys( $templates ) as $template )
			: if ( $default == $templates[$template] )
				$selected = " selected='selected'";
			else
				$selected = '';
		echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
		endforeach;
	}

	/**
	 * Get a list of taxonomy templates available in the current theme
	 *
	 * @since 0.1
	 * @return array Key is the template name, value is the filename of the template
	 */
	function get_templates( $taxonomy = 'category', $template = null ) {
		$tax = get_taxonomy( $taxonomy );
		if( ! $tax )
			return array();

		$templates = array();
		$theme = wp_get_theme( $template );
		$files = (array) $theme->get_files( 'php', 1 );
                
		foreach ( $files as $file => $full_path ) {        
			if ( ! preg_match("#({$tax->labels->singular_name}|{$tax->name}) Template:(.*)$#mi", file_get_contents( $full_path ), $header ) )
				continue;
			$templates[ $file ] = _cleanup_header_comment( $header[2] );
		}

		if ( $theme->parent() )
			$templates += $this->get_templates( $taxonomy, $theme->get_template() );

		return apply_filters( 'custom_taxonomy_templates', $templates, $taxonomy, $template );
	}
}
$custom_taxonomy_templates = new Custom_Taxonomy_Templates();
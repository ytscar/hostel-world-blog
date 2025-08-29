<?php
// Silence is golden.


function hostelworld_enqueue_styles()
{
    $parenthandle = 'page-builder-framework-style';
    $theme = wp_get_theme();

    wp_enqueue_style($parenthandle, get_template_directory_uri() . '/style.css',
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );

    $hwCss = file_get_contents(__DIR__ . '/style/hostelworld.css');
    wp_register_style('hw-child-customstyle', false);
    wp_enqueue_style('hw-child-customstyle');
    wp_add_inline_style('hw-child-customstyle', $hwCss);

    $hwJs = file_get_contents(__DIR__ . '/js/hostelworld.js');
    wp_register_script('hw-child-customscript', '');
    wp_enqueue_script('hw-child-customscript');
    wp_add_inline_script('hw-child-customscript', $hwJs);

    wp_enqueue_script( 'hoverIntent' );
}

add_action('wp_enqueue_scripts', 'hostelworld_enqueue_styles');

require 'inc/authors.php';
require 'inc/qr.php';
require 'inc/seo.php';
require 'inc/sg.php';
require 'inc/tinymce.php';

add_filter( 'category_description', function( $description ) {
	if ( is_paged() ) {
		return '';
	}
	return $description;
} );

add_filter( 'big_image_size_threshold', '__return_false' );
function display_related_pages_by_tagging() {
    $tagging = get_post_meta(get_the_ID(), 'tagging', true);

    if ($tagging && strpos($tagging, 'intlink-') === 0) {
        $locations = get_posts(array(
            'meta_query' => array(
                array(
                    'key' => 'tagging',
                    'value' => $tagging,
                    'compare' => '='
                )
            ),
            'post_type' => 'page',
            'posts_per_page' => -1,  // Fetch all posts initially
            'post__not_in' => array(get_the_ID())
        ));

        if ($locations) {
            $location_links = '';
            $count = 0;

            foreach ($locations as $location) {
                
    $location_title = get_post_meta($location->ID, '_yoast_wpseo_focuskw', true);
    $location_url = get_permalink($location->ID);

    // Add 'hidden' class if count is 5 or more
    $hidden_class = ($count >= 5) ? ' hidden' : '';
    
    $location_links .= '<span class="location-item' . $hidden_class . '"><a href="' . esc_url($location_url) . '" class="location-link">' . esc_html($location_title) . '</a> | </span>';
    
    $count++;
}

            $new_content = '<div class="other-locations" style="text-align: center;">';
            $new_content .= '<p id="locationLinks" style="padding-bottom: 30px; margin:10px; text-align: center">';
            $new_content .= rtrim($location_links, ' | ');
            $new_content .= '</p>';

            if (count($locations) > 5) {
                $new_content .= '<p><a href="javascript:void(0);" id="toggleLocations" style="text-align: center; font-weight: bold;">Read more</a></p>';
            }

            $new_content .= '</div>';
            
            echo $new_content;
        }
    }
}


 add_filter( 'big_image_size_threshold', '__return_false' );

function get_city_activities($city) {
    $file_path = ABSPATH . 'wp-content/uploads/wpallimport/files/Linkups-test-upload-dbase.csv';
    $file = fopen($file_path, 'r');
    $activities = [];

    // Skip the header row
    fgetcsv($file);

    while (($line = fgetcsv($file)) !== FALSE) { // Default delimiter is comma
        // Check if the 'gatheringLocation' (first column of CSV) matches the provided city
        if (strtolower($line[0]) === strtolower($city)) {
            $activities[] = [
                'gatheringName' => $line[1],
                'imageUrl' => $line[2],
                'gatheringDescription' => $line[3],
                'hostelOwnerName' => $line[4],
                'idCategory' => $line[5]
            ];
        }
    }
    fclose($file);
    return $activities;
}
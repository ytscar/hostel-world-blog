<?php
// Silence is golden.


function hostelworld_render_mobile_search_trigger()
{
    echo '<button id="wpbf-mobile-search-toggle" class="wpbf-mobile-nav-item  wpbff wpbff-search" aria-label="Mobile Site Navigation" aria-controls="navigation" aria-expanded="false" aria-haspopup="true">
					<span class="screen-reader-text">Search Toggle</span></button>';
}
add_action('wpbf_before_mobile_toggle', 'hostelworld_render_mobile_search_trigger');

function hostelworld_display_qr()
{
    echo '<div aria-role="dialog" class="qr-wrapper">';
    echo '<img src="' . get_stylesheet_directory_uri() . '/img/5ac4c25.svg' . '" alt="Get the App. QR"/>';
    echo '<span class="text">Get the App.</span>';
    echo '</div>';


    echo '<div aria-role="dialog" class="getapp-wrapper">';
    echo '<a target="_blank" rel="noopener noreferrer" href="https://w5az.app.link/HWBlog" class="text">
         <img src="' . get_stylesheet_directory_uri() . '/img/logo.svg' . '" alt="Get the App. QR"/>';
    echo '&nbsp;&nbsp;Get the App.';
    echo '</div>';
}

add_action('wpbf_body_close', 'hostelworld_display_qr');
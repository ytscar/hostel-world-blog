<?php
// Silence is golden.
/*
if (defined('WPSEO_VERSION')) {
    add_filter('wpseo_disable_adjacent_rel_links', function () {
        return true;
    });
*/
    if (substr_count($_SERVER['REQUEST_URI'], 'blog/page/')) {
        add_filter('wpseo_canonical', function () {
            return 'https://www.hostelworld.com/blog/';
        });
    }


    if (substr_count($_SERVER['REQUEST_URI'], 'blog/c/') && $paged == 1) {
        $canonicalArray = explode('/page/', $_SERVER['REQUEST_URI']);

        $canonicalUrl = $canonicalArray[0] . '/';
        if(substr_count($canonicalUrl, '/blog/'))
        {

            add_filter('wpseo_canonical', function () {
                global $canonicalUrl;
                return 'https://www.hostelworld.com'.$canonicalUrl;
            });
        }
    }
function cor_rel_next_prev_pagination() { 

  global $paged; 


  if ( get_previous_posts_link() ) { ?> 

  <link rel="prev" href="<?php echo get_pagenum_link( $paged - 1 ); ?>"> 

  <?php 

  } 

  if ( get_next_posts_link() ) { ?> 

  <link rel="next" href="<?php echo get_pagenum_link( $paged + 1 ); ?>"> 

  <?php 

  } 

} 

remove_action('wp_head', 'adjacent_posts_rel_link_wp_head'); 

add_action('wp_head', 'cor_rel_next_prev_pagination'); 


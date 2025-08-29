<?php

add_filter('wp_head', 'fifu_ctgr_add_social_tags');

function fifu_ctgr_add_social_tags() {
    $url = fifu_ctgr_get_url(null);
    $title = single_cat_title('', false);

    $term_id = fifu_ctgr_get_term_id();
    if ($term_id)
        $description = wp_strip_all_tags(category_description($term_id));
}

function fifu_ctgr_get_url($term_id) {
    $term_id = $term_id ?: fifu_ctgr_get_term_id();
    return get_term_meta($term_id, 'fifu_image_url', true);
}

function fifu_ctgr_get_alt($term_id) {
    $term_id = $term_id ?: fifu_ctgr_get_term_id();
    return get_term_meta($term_id, 'fifu_image_alt', true);
}

function fifu_ctgr_get_term_id() {
    global $wp_query;
    if (!isset($wp_query)) {
        return null;
    }
    return $wp_query->get_queried_object_id() ?? null;
}


<?php

define('FIFU_AUTHOR', get_option('fifu_author') ?: 77777);

add_filter('get_attached_file', 'fifu_replace_attached_file', 10, 2);

function fifu_replace_attached_file($att_url, $att_id) {
    return fifu_process_url($att_url, $att_id);
}

function fifu_process_url($att_url, $att_id) {
    if (strpos($att_url, "https://thumbnails.odycdn.com") === 0 ||
            strpos($att_url, "https://res.cloudinary.com/glide/") === 0 ||
            // strpos($att_url, "//wp.fifu.app") === 0 ||
            strpos($att_url, "https://i0.wp.com") === 0 ||
            strpos($att_url, "https://i1.wp.com") === 0 ||
            strpos($att_url, "https://i2.wp.com") === 0 ||
            strpos($att_url, "https://i3.wp.com") === 0)
        return $att_url;

    if (!$att_id)
        return $att_url;

    $att_post = get_post($att_id);

    if (!$att_post)
        return $att_url;

    // internal
    if ($att_post->post_author != FIFU_AUTHOR)
        return $att_url;

    $url = get_post_meta($att_id, '_wp_attached_file', true); // to avoid wp_get_attachment_url() infinite loop

    fifu_fix_legacy($url, $att_id);

    return fifu_process_external_url($url, $att_id, null);
}

function fifu_process_external_url($url, $att_id, $size) {
    return fifu_add_url_parameters($url, $att_id, $size);
}

function fifu_fix_legacy($url, $att_id) {
    if (strpos($url, ';') === false)
        return;
    $att_url = get_post_meta($att_id, '_wp_attached_file');
    $att_url = is_array($att_url) ? ($att_url[0] ?? '') : $att_url;
    if (fifu_starts_with($att_url, ';http') || fifu_starts_with($att_url, ';/'))
        update_post_meta($att_id, '_wp_attached_file', $url);
}

add_filter('wp_get_attachment_url', 'fifu_replace_attachment_url', 10, 2);

function fifu_replace_attachment_url($att_url, $att_id) {
    if ($att_url)
        return fifu_process_url($att_url, $att_id);
    return $att_url;
}

add_filter('posts_where', 'fifu_query_attachments');

function fifu_query_attachments($where) {
    global $wpdb;
    if (fifu_is_web_story() || (($_POST['action'] ?? '') == 'query-attachments' || ($_POST['action'] ?? '') == 'get-attachment'))
        $where .= ' AND ' . $wpdb->prefix . 'posts.post_author <> ' . FIFU_AUTHOR . ' ';
    return $where;
}

add_filter('posts_where', function ($where, \WP_Query $q) {
    global $wpdb;
    if (fifu_is_web_story() || (is_admin() && $q->is_main_query() && strpos($where, 'attachment') !== false))
        $where .= ' AND ' . $wpdb->prefix . 'posts.post_author <> ' . FIFU_AUTHOR . ' ';
    return $where;
}, 10, 2);

add_filter('wp_get_attachment_image_src', 'fifu_replace_attachment_image_src', 10, 3);

function fifu_replace_attachment_image_src($image, $att_id, $size) {
    if (!$image || !$att_id)
        return $image;

    $att_post = get_post($att_id);

    if (!$att_post)
        return $image;

    // internal
    if ($att_post->post_author != FIFU_AUTHOR)
        return $image;

    global $FIFU_SESSION;
    $prev_url = null;
    if (isset($FIFU_SESSION['cdn-new-old']) && isset($image[0]) && isset($FIFU_SESSION['cdn-new-old'][$image[0]]))
        $prev_url = $FIFU_SESSION['cdn-new-old'][$image[0]];

    $FIFU_SESSION['att_img_src'] = $FIFU_SESSION['att_img_src'] ?? array();

    $image[0] = fifu_process_url($image[0] ?? '', $att_id);

    $original_url = fifu_main_image_url(get_queried_object_id(), true);
    if (fifu_should_hide() && ($original_url == $image[0] || ($prev_url && $prev_url == $original_url))) {
        if (!in_array($original_url, $FIFU_SESSION['att_img_src'])) {
            $aux = is_array($size) ? implode(',', $size) : $size;
            $FIFU_SESSION['att_img_src'][] = $original_url . $aux;
            return null;
        }
    }

    $FIFU_SESSION['att_img_src'][] = $original_url;

    if (fifu_is_from_speedup($image[0] ?? ''))
        $image = fifu_speedup_get_url($image, $size, $att_id);

    // photon
    if (fifu_is_on('fifu_photon') && !fifu_jetpack_blocked($image[0] ?? '') && !fifu_is_in_editor())
        $image = fifu_get_photon_url($image, $size, $att_id);

    if (($image[1] ?? 0) <= 1 && ($image[2] ?? 0) <= 1) {
        $result = fifu_add_size($image, $size);
        $image = $result['image'] ?? $image;
    }

    return $image;
}

function fifu_add_size($image, $size) {
    // Get size details using fifu_get_image_size_details
    $size_details = fifu_get_image_size_details($size);

    // If no valid size details are found, return the original image with null crop
    if (!($size_details['width'] ?? false) && !($size_details['height'] ?? false)) {
        return array(
            'image' => $image,
            'crop' => null
        );
    }

    // Assign only width and height to the image array
    $image[1] = $size_details['width'] ?? 0;
    $image[2] = $size_details['height'] ?? 0;

    // Return the modified image and crop separately
    return array(
        'image' => $image,
        'crop' => $size_details['crop'] ?? false
    );
}

function fifu_get_photon_url($image, $size, $att_id) {
    $result = fifu_add_size($image, $size);
    $image = $result['image'] ?? $image;
    $w = $image[1] ?? 0;
    $h = $image[2] ?? 0;
    $c = ($result['crop'] ?? false) ? 1 : 0;

    if (fifu_is_from_proxy_urls($image[0])) {
        $image[0] = fifu_jetpack_photon_url($image[0] ?? '', "?w={$w}&h={$h}&c={$c}", $att_id);
    } else {
        $args = array();

        if ($w > 0 && $h > 0) {
            $args['resize'] = $w . ',' . $h;
        } elseif ($w > 0) {
            $args['resize'] = $w;
            $args['w'] = $w;
        } elseif ($h > 0) {
            $args['resize'] = $h;
            $args['h'] = $h;
        } else {
            
        }

        $image[0] = fifu_jetpack_photon_url($image[0], $args, $att_id);
    }

    $image[0] = fifu_process_external_url($image[0], $att_id, $size);

    return $image;
}

add_action('template_redirect', 'fifu_action', 10);

function fifu_action() {
    ob_start("fifu_callback");
}

function fifu_callback($buffer) {
    global $FIFU_SESSION;

    if (empty($buffer))
        return $buffer;

    /* plugins: Oxygen, Bricks */
    if (isset($_REQUEST['ct_builder']) || isset($_REQUEST['bricks']) || isset($_REQUEST['fb-edit']))
        return $buffer;

    /* img */

    $srcType = "src";
    $imgList = array();
    preg_match_all('/<img[^>]*>/', $buffer, $imgList);

    foreach (($imgList[0] ?? []) as $imgItem) {
        preg_match('/(' . $srcType . ')([^\'\"]*[\'\"]){2}/', $imgItem, $src);
        if (!$src)
            continue;
        $del = substr($src[0] ?? '', - 1);
        $url = fifu_normalize(explode($del, $src[0] ?? '')[1] ?? '');
        $post_id = null;

        // get parameters
        $data = null;
        $prev_url = null;

        if (isset($FIFU_SESSION[$url])) {
            $data = $FIFU_SESSION[$url];
        } else {
            if (isset($FIFU_SESSION['cdn-new-old'][$url])) {
                $prev_url = $FIFU_SESSION['cdn-new-old'][$url];
                if (isset($FIFU_SESSION[$prev_url])) {
                    $data = $FIFU_SESSION[$prev_url];
                }
            }
        }

        if (!$data)
            continue;

        if (strpos($imgItem, 'fifu-replaced') !== false)
            continue;

        $post_id = $data['post_id'] ?? null;
        $att_id = $data['att_id'] ?? null;
        $featured = $data['featured'] ?? null;
        $is_category = $data['category'] ?? false;
        $theme_width = $data['theme-width'] ?? null;
        $theme_height = $data['theme-height'] ?? null;

        if ($featured && is_single()) {
            $buffer = str_replace('</head>', '<link rel="preload" as="image" href="' . esc_url($url) . '">' . "</head>\n", $buffer);
        }

        if ($featured) {
            // add featured
            $newImgItem = str_replace('<img ', '<img fifu-featured="' . $featured . '" ', $imgItem);

            // add category 
            if ($is_category)
                $newImgItem = str_replace('<img ', '<img fifu-category="1" ', $newImgItem);

            // add post_id
            if (get_post_type($post_id) == 'product')
                $newImgItem = str_replace('<img ', '<img product-id="' . $post_id . '" ', $newImgItem);
            else
                $newImgItem = str_replace('<img ', '<img post-id="' . $post_id . '" ', $newImgItem);

            // add theme sizes
            if ($theme_width && $theme_height) {
                $newImgItem = str_replace('<img ', '<img theme-width="' . $theme_width . '" ', $newImgItem);
                $newImgItem = str_replace('<img ', '<img theme-height="' . $theme_height . '" ', $newImgItem);
            }

            // speed up (doesn't work with ajax calls)
            if (fifu_is_from_speedup($url)) {
                $newImgItem = str_replace('<img ', '<img srcset="' . fifu_speedup_get_set($url) . '" ', $newImgItem);
                $newImgItem = str_replace('<img ', '<img sizes="(max-width:' . $theme_width . 'px) 100vw, ' . $theme_width . 'px" ', $newImgItem);
            }

            $buffer = str_replace($imgItem, fifu_replace($newImgItem, $post_id, null, null, null), $buffer);
        }
    }

    /* background-image */

    $imgList = array();
    preg_match_all('/<[^>]*background-image[^>]*>/', $buffer, $imgList);
    foreach (($imgList[0] ?? []) as $imgItem) {
        if (strpos($imgItem, 'style=') === false || strpos($imgItem, 'url(') === false)
            continue;

        $mainDelimiter = substr(explode('style=', str_replace('\\', '', $imgItem))[1] ?? '', 0, 1);
        $subDelimiter = substr(explode('url(', str_replace('\\', '', $imgItem))[1] ?? '', 0, 1);
        if (in_array($subDelimiter, array('"', "'", ' ')))
            $url = preg_split('/[\'\" ]{1}\)/', preg_split('/url\([\'\" ]{1}/', $imgItem, -1)[1] ?? '', -1)[0] ?? '';
        else {
            $url = preg_split('/\)/', preg_split('/url\(/', $imgItem, -1)[1] ?? '', -1)[0] ?? '';
            $subDelimiter = '';
        }

        $newImgItem = $imgItem;

        $url = fifu_normalize($url);
        if (isset($FIFU_SESSION[$url])) {
            $data = $FIFU_SESSION[$url];

            if (strpos($imgItem, 'fifu-replaced') !== false)
                continue;

            $att_id = $data['att_id'] ?? null;

            $post_id = $data['post_id'] ?? null;
            $newImgItem = str_replace('>', ' ' . 'post-id="' . $post_id . '">', $newImgItem);
        }

        if ($newImgItem != $imgItem)
            $buffer = str_replace($imgItem, $newImgItem, $buffer);
    }

    return $buffer;
}

add_filter('wp_get_attachment_metadata', 'fifu_filter_wp_get_attachment_metadata', 10, 2);

function fifu_filter_wp_get_attachment_metadata($data, $att_id) {
    return $data;
}

function fifu_add_url_parameters($url, $att_id, $size) {
    global $FIFU_SESSION;

    // avoid duplicated call
    if (isset($FIFU_SESSION[$url]))
        return $url;

    $post = get_post($att_id);
    $post_id = $post ? $post->post_parent : null;

    if (!$post_id)
        return $url;

    // "categories" page
    if (function_exists('get_current_screen') && isset(get_current_screen()->parent_file) && get_current_screen()->parent_file == 'edit.php?post_type=product' && get_current_screen()->id == 'edit-product_cat')
        return fifu_optimized_column_image($url, $att_id);

    $post_thumbnail_id = get_post_thumbnail_id($post_id);

    $is_category = false;
    if (!$post_thumbnail_id) {
        $post_thumbnail_id = get_term_meta($post_id, 'thumbnail_id', true);
        if ($post_thumbnail_id)
            $is_category = true;
    }

    $featured = $post_thumbnail_id == $att_id ? 1 : 0;

    if (!$featured)
        return $url;

    $parameters = array();
    $parameters['att_id'] = $att_id;
    $parameters['post_id'] = $post_id;
    $parameters['featured'] = $featured;
    $parameters['category'] = $is_category;

    // theme size
    if ($size) {
        $size_details = fifu_get_image_size_details($size);
        if (($size_details['width'] ?? false) && ($size_details['height'] ?? false)) {
            $parameters['theme-width'] = $size_details['width'];
            $parameters['theme-height'] = $size_details['height'];
            $parameters['theme-crop'] = $size_details['crop'] ?? false;
        }
    }

    $FIFU_SESSION[$url] = $parameters;

    if (fifu_is_from_speedup($url)) {
        $FIFU_SESSION['fifu-cloud'][$url] = fifu_speedup_get_set($url);
        wp_enqueue_script('fifu-cloud', plugins_url('/html/js/cloud.js', __FILE__), array('jquery'), fifu_version_number_enq());
        $json = wp_json_encode(['srcsets' => $FIFU_SESSION['fifu-cloud']]);
        wp_add_inline_script('fifu-cloud', "var fifuCloudVars = {$json};", 'before');
    }

    return $url;
}

function fifu_get_photon_args($h, $c) {
    $args = array();
    $args['resize'] = $h . ',' . $h;
    return $args;
}

function fifu_add_parameters_single_post($post_id) {
    $att_id = get_post_thumbnail_id($post_id);
    $url = get_post_meta($att_id, '_wp_attached_file', true);
    if ($url)
        fifu_add_url_parameters($url, $att_id, null);
}

// dont load remote image data in the media library when called from block editor

function custom_get_attachment_intercept() {
    $att_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

    if ($att_id > 0) {
        if (fifu_is_remote_image($att_id)) {
            $response = array(
                'success' => false,
                'data' => array(),
            );
            wp_send_json($response); // This terminates execution
        }
    }
}

add_action('wp_ajax_get-attachment', 'custom_get_attachment_intercept', 0);


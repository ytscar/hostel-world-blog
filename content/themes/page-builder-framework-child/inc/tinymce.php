<?php
// Silence is golden.


function hostelworld_tinymce_toolbar_fontsize($args)
{
    $args['fontsize_formats'] = "8px 10px 12px 13px 14px 16px 20px 24px 28px 32px 36px 40px";
    return $args;

}

add_filter('tiny_mce_before_init', 'hostelworld_tinymce_toolbar_fontsize');

function hostelworld_tinymce_toolbar($buttons)
{

    $italic_index = array_search('italic', $buttons);

    return array_merge(array_slice($buttons, 0, $italic_index + 1, true),
        array("underline"),
        array_slice($buttons, $italic_index + 1, count($buttons) - $italic_index, true));
}

add_filter('mce_buttons', 'hostelworld_tinymce_toolbar');

function hostelworld_tinymce_toolbar2($buttons)
{
    array_unshift($buttons, 'fontselect');
    array_unshift($buttons, 'fontsizeselect');
    return $buttons;
}

function hostelworld_tinymce_toolbar2_backcolor($buttons)
{

    $color_index = array_search('color', $buttons);

    return array_merge(array_slice($buttons, 0, $color_index + 2, true),
        array("backcolor"),
        array_slice($buttons, $color_index + 2, count($buttons) - $color_index, true));
}

add_filter('mce_buttons_2', 'hostelworld_tinymce_toolbar2_backcolor');
add_filter('mce_buttons_2', 'hostelworld_tinymce_toolbar2');


if (defined('WPSEO_VERSION')) {
    add_filter('wpseo_disable_adjacent_rel_links', function () {
        return true;
    });

    if (substr_count($_SERVER['REQUEST_URI'], 'blog/page')) {
        add_filter('wpseo_canonical', function () {
            return 'https://www.hostelworld.com/blog/';
        });

    }

}

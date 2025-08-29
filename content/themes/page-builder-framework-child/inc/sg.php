<?php
// Silence is golden.


add_action('wp_ajax_admin_bar_purge_cache', function () {
    add_filter('home_url', function () {
        return 'https://marylineg1.sg-host.com/blog/';
    });
}, 1);
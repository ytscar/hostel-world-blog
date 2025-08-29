<?php

define('FIFU_SETTINGS', serialize(array('fifu_skip', 'fifu_html_cpt', 'fifu_square_mobile', 'fifu_square_desktop', 'fifu_debug', 'fifu_photon', 'fifu_cdn_content', 'fifu_reset', 'fifu_enable_default_url', 'fifu_fake', 'fifu_default_url', 'fifu_default_cpt', 'fifu_pcontent_types', 'fifu_hide_format', 'fifu_hide_type', 'fifu_wc_lbox', 'fifu_wc_zoom', 'fifu_hide', 'fifu_pcontent_add', 'fifu_pcontent_remove', 'fifu_get_first', 'fifu_ovw_first', 'fifu_run_delete_all', 'fifu_data_clean', 'fifu_cloud_upload_auto', 'fifu_cloud_delete_auto', 'fifu_cloud_hotlink')));
define('FIFU_ACTION_SETTINGS', '/wp-admin/admin.php?page=featured-image-from-url');
define('FIFU_ACTION_CLOUD', '/wp-admin/admin.php?page=fifu-cloud');

define('FIFU_SLUG', 'featured-image-from-url');

add_action('admin_menu', 'fifu_insert_menu');

function fifu_insert_menu() {
    $fifu = fifu_get_strings_settings();

    if (isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], FIFU_SLUG) !== false || strpos($_SERVER['REQUEST_URI'], 'fifu') !== false)) {
        wp_enqueue_script('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js');
        wp_enqueue_style('jquery-ui-style1', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
        wp_enqueue_style('jquery-ui-style2', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css');
        wp_enqueue_style('jquery-ui-style3', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css');

        wp_enqueue_style('fifu-pro-css', plugins_url('/html/css/pro.css', __FILE__), array(), fifu_version_number_enq());

        wp_enqueue_script('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
        wp_enqueue_script('jquery-block-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js');

        wp_enqueue_style('datatable-css', '//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
        wp_enqueue_style('datatable-select-css', '//cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css');
        wp_enqueue_style('datatable-buttons-css', '//cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css');
        wp_enqueue_script('datatable-js', '//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js');
        wp_enqueue_script('datatable-select', '//cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js');
        wp_enqueue_script('datatable-buttons', '//cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js');

        wp_enqueue_script('fifu-rest-route-js', plugins_url('/html/js/rest-route.js', __FILE__), array('jquery'), fifu_version_number_enq());

        // register custom variables for the AJAX script
        wp_localize_script('fifu-rest-route-js', 'fifuScriptVars', [
            'restUrl' => esc_url_raw(rest_url()),
            'homeUrl' => esc_url_raw(home_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    add_menu_page('Featured Image from URL', 'FIFU', 'manage_options', FIFU_SLUG, 'fifu_get_menu_html', 'dashicons-camera', 57);
    add_submenu_page(FIFU_SLUG, 'FIFU Settings', $fifu['options']['settings'](), 'manage_options', FIFU_SLUG);
    add_submenu_page(FIFU_SLUG, 'FIFU Cloud', $fifu['options']['cloud'](), 'manage_options', 'fifu-cloud', 'fifu_cloud');
    add_submenu_page(FIFU_SLUG, 'FIFU Troubleshooting', $fifu['options']['troubleshooting'](), 'manage_options', 'fifu-troubleshooting', 'fifu_troubleshooting');
    add_submenu_page(FIFU_SLUG, 'FIFU Status', $fifu['options']['status'](), 'manage_options', 'fifu-support-data', 'fifu_support_data');
    add_submenu_page(FIFU_SLUG, 'FIFU Pro', '<a href="https://fifu.app/" target="_blank"><div style="padding:5px;color:white;background-color:#1da867">' . $fifu['options']['upgrade']() . '</div></a>', 'manage_options', '#', null);

    add_action('admin_init', 'fifu_get_menu_settings');
}

function fifu_cloud() {
    flush();

    $fifu = fifu_get_strings_settings();
    $fifucloud = fifu_get_strings_cloud();

    // css and js
    wp_enqueue_script('fifu-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/latest/js.cookie.min.js');
    wp_enqueue_style('fifu-menu-su-css', plugins_url('/html/css/menu-su.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_script('fifu-menu-su-js', plugins_url('/html/js/menu-su.js', __FILE__), array('jquery', 'jquery-ui'), fifu_version_number_enq());

    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_script('fifu-cloud-js', plugins_url('/html/js/cloud.js', __FILE__), array('jquery'), fifu_version_number_enq());

    wp_localize_script('fifu-cloud-js', 'fifuScriptCloudVars', [
        'signUpComplete' => fifu_su_sign_up_complete(),
        'woocommerce' => class_exists('WooCommerce'),
        'availableImages' => fifu_db_count_available_images(),
        'down' => $fifucloud['ws']['down'](),
        'connected' => $fifucloud['ws']['connection']['ok'](),
        'notConnected' => $fifucloud['ws']['connection']['fail'](),
        'noImages' => $fifucloud['table']['no']['images'](),
        'noPosts' => $fifucloud['table']['no']['posts'](),
        'noData' => $fifucloud['table']['no']['data'](),
        'selectAll' => $fifucloud['table']['select']['all'](),
        'selectNone' => $fifucloud['table']['select']['none'](),
        'load' => $fifucloud['table']['load'](),
        'limit' => $fifucloud['table']['limit'](),
        'delete' => $fifucloud['table']['delete'](),
        'upload' => $fifucloud['table']['upload'](),
        'link' => $fifucloud['table']['link'](),
        'dialogDelete' => $fifucloud['table']['dialog']['delete'](),
        'dialogCancel' => $fifucloud['table']['dialog']['cancel'](),
        'dialogYes' => $fifucloud['table']['dialog']['yes'](),
        'dialogNo' => $fifucloud['table']['dialog']['no'](),
        'category' => $fifucloud['table']['category'](),
        'slider' => $fifucloud['table']['slider'](),
        'gallery' => $fifucloud['table']['gallery'](),
        'featured' => $fifucloud['table']['featured'](),
        'filterResults' => $fifucloud['table']['filter'](),
        'showResults' => $fifucloud['table']['show'](),
    ]);

    $enable_cloud_upload_auto = get_option('fifu_cloud_upload_auto');
    $enable_cloud_delete_auto = get_option('fifu_cloud_delete_auto');
    $enable_cloud_hotlink = get_option('fifu_cloud_hotlink');

    include 'html/cloud.html';

    if (fifu_is_valid_nonce('nonce_fifu_form_cloud_upload_auto', FIFU_ACTION_CLOUD))
        fifu_update_option('fifu_input_cloud_upload_auto', 'fifu_cloud_upload_auto');

    if (fifu_is_valid_nonce('nonce_fifu_form_cloud_delete_auto', FIFU_ACTION_CLOUD))
        fifu_update_option('fifu_input_cloud_delete_auto', 'fifu_cloud_delete_auto');

    if (fifu_is_valid_nonce('nonce_fifu_form_cloud_hotlink', FIFU_ACTION_CLOUD))
        fifu_update_option('fifu_input_cloud_hotlink', 'fifu_cloud_hotlink');

    // schedule upload
    if (fifu_is_on('fifu_cloud_upload_auto')) {
        if (!wp_next_scheduled('fifu_create_cloud_upload_auto_event')) {
            wp_schedule_event(time(), 'fifu_schedule_cloud_upload_auto', 'fifu_create_cloud_upload_auto_event');
            fifu_run_cron_now();
        }
    } else {
        wp_clear_scheduled_hook('fifu_create_cloud_upload_auto_event');
        fifu_delete_transient('fifu_cloud_upload_auto_semaphore');
        fifu_stop_job('fifu_cloud_upload_auto');
    }

    if (fifu_is_on('fifu_cloud_delete_auto')) {
        if (!wp_next_scheduled('fifu_create_cloud_delete_auto_event')) {
            wp_schedule_event(time(), 'fifu_schedule_cloud_delete_auto', 'fifu_create_cloud_delete_auto_event');
            fifu_run_cron_now();
        }
    } else {
        wp_clear_scheduled_hook('fifu_create_cloud_delete_auto_event');
        fifu_delete_transient('fifu_cloud_delete_auto_semaphore');
        fifu_stop_job('fifu_cloud_delete_auto');
    }
}

function fifu_troubleshooting() {
    flush();

    $fifu = fifu_get_strings_settings();

    // css and js
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_script('fifu-troubleshooting-js', plugins_url('/html/js/troubleshooting.js', __FILE__), array('jquery', 'jquery-ui'), fifu_version_number_enq());

    include 'html/troubleshooting.html';
}

function fifu_support_data() {
    $fifu = fifu_get_strings_settings();

    // css
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number_enq());

    $skip = esc_attr(get_option('fifu_skip'));
    $html_cpt = esc_attr(get_option('fifu_html_cpt'));
    $square_mobile = esc_attr(get_option('fifu_square_mobile'));
    $square_desktop = esc_attr(get_option('fifu_square_desktop'));
    $enable_debug = get_option('fifu_debug');
    $enable_photon = get_option('fifu_photon');
    $enable_cdn_content = get_option('fifu_cdn_content');
    $enable_reset = get_option('fifu_reset');
    $enable_fake = get_option('fifu_fake');
    $default_url = esc_url(get_option('fifu_default_url'));
    $default_cpt = esc_attr(get_option('fifu_default_cpt'));
    $pcontent_types = esc_attr(get_option('fifu_pcontent_types'));
    $hide_format = esc_attr(get_option('fifu_hide_format'));
    $hide_type = esc_attr(get_option('fifu_hide_type'));
    $enable_default_url = get_option('fifu_enable_default_url');
    $enable_wc_lbox = get_option('fifu_wc_lbox');
    $enable_wc_zoom = get_option('fifu_wc_zoom');
    $enable_hide = get_option('fifu_hide');
    $enable_pcontent_add = get_option('fifu_pcontent_add');
    $enable_pcontent_remove = get_option('fifu_pcontent_remove');
    $enable_get_first = get_option('fifu_get_first');
    $enable_ovw_first = get_option('fifu_ovw_first');
    $enable_run_delete_all = get_option('fifu_run_delete_all');
    $enable_run_delete_all_time = get_option('fifu_run_delete_all_time');
    $enable_data_clean = 'toggleoff';
    $enable_cloud_upload_auto = get_option('fifu_cloud_upload_auto');
    $enable_cloud_delete_auto = get_option('fifu_cloud_delete_auto');
    $enable_cloud_hotlink = get_option('fifu_cloud_hotlink');

    include 'html/support-data.html';
}

function fifu_get_menu_html() {
    flush();

    $fifu = fifu_get_strings_settings();
    $fifucloud = fifu_get_strings_cloud();

    // css and js
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number_enq());
    wp_enqueue_script('fifu-menu-js', plugins_url('/html/js/menu.js', __FILE__), array('jquery', 'jquery-ui'), fifu_version_number_enq());

    // register custom variables for the AJAX script
    wp_localize_script('fifu-menu-js', 'fifuScriptVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'wait' => $fifu['php']['message']['wait'](),
        'saving' => $fifu['word']['saving'](),
        'saved' => $fifu['word']['saved'](),
        'error' => $fifu['word']['error'](),
        'reset' => $fifu['word']['reset'](),
        'save' => $fifu['word']['save'](),
        'pluginUrl' => plugins_url() . '/' . FIFU_SLUG,
    ]);

    $skip = esc_attr(get_option('fifu_skip'));
    $html_cpt = esc_attr(get_option('fifu_html_cpt'));
    $square_mobile = esc_attr(get_option('fifu_square_mobile'));
    $square_desktop = esc_attr(get_option('fifu_square_desktop'));
    $enable_debug = get_option('fifu_debug');
    $enable_photon = get_option('fifu_photon');
    $enable_cdn_content = get_option('fifu_cdn_content');
    $enable_reset = get_option('fifu_reset');
    $enable_fake = get_option('fifu_fake');
    $default_url = esc_url(get_option('fifu_default_url'));
    $default_cpt = esc_attr(get_option('fifu_default_cpt'));
    $pcontent_types = esc_attr(get_option('fifu_pcontent_types'));
    $hide_format = esc_attr(get_option('fifu_hide_format'));
    $hide_type = esc_attr(get_option('fifu_hide_type'));
    $enable_default_url = get_option('fifu_enable_default_url');
    $enable_wc_lbox = get_option('fifu_wc_lbox');
    $enable_wc_zoom = get_option('fifu_wc_zoom');
    $enable_hide = get_option('fifu_hide');
    $enable_pcontent_add = get_option('fifu_pcontent_add');
    $enable_pcontent_remove = get_option('fifu_pcontent_remove');
    $enable_get_first = get_option('fifu_get_first');
    $enable_ovw_first = get_option('fifu_ovw_first');
    $enable_run_delete_all = get_option('fifu_run_delete_all');
    $enable_run_delete_all_time = get_option('fifu_run_delete_all_time');
    $enable_data_clean = 'toggleoff';

    include 'html/menu.html';

    $arr = fifu_update_menu_options();

    // default
    if (!$arr['fifu_default_cpt']) { # submit via post type form
        $default_url = $arr['fifu_default_url']; # submit via default url form
        if (!empty($default_url) && fifu_is_on('fifu_enable_default_url') && fifu_is_on('fifu_fake')) {
            if (!wp_get_attachment_url(get_option('fifu_default_attach_id'))) {
                $att_id = fifu_db_create_attachment($default_url);
                update_option('fifu_default_attach_id', $att_id);
                fifu_db_set_default_url();
            } else
                fifu_db_update_default_url($default_url);
        }
    }

    // reset
    if (fifu_is_on('fifu_reset')) {
        fifu_reset_settings();
        update_option('fifu_reset', 'toggleoff', 'no');
    }
}

function fifu_get_menu_settings() {
    foreach (unserialize(FIFU_SETTINGS) as $i)
        fifu_get_setting($i);
}

function fifu_reset_settings() {
    foreach (unserialize(FIFU_SETTINGS) as $i) {
        if ($i != 'fifu_key' &&
                $i != 'fifu_email' &&
                $i != 'fifu_default_url' &&
                $i != 'fifu_enable_default_url')
            delete_option($i);
    }
}

function fifu_get_setting($type) {
    register_setting('settings-group', $type);

    $arrEmpty = array('fifu_default_url', 'fifu_skip', 'fifu_html_cpt', 'fifu_square_mobile', 'fifu_square_desktop', 'fifu_hide_format', 'fifu_hide_type', 'fifu_pcontent_types');
    $arrDefaultType = array('fifu_default_cpt');
    $arrOn = array('fifu_wc_zoom', 'fifu_wc_lbox');
    $arrOnNo = array('fifu_fake');
    $arrOffNo = array('fifu_data_clean', 'fifu_run_delete_all', 'fifu_reset');

    if (get_option($type) === false) {
        if (in_array($type, $arrEmpty))
            update_option($type, '');
        else if (in_array($type, $arrDefaultType))
            update_option($type, "post,page,product", 'no');
        else if (in_array($type, $arrOn))
            update_option($type, 'toggleon');
        else if (in_array($type, $arrOnNo))
            update_option($type, 'toggleon', 'no');
        else if (in_array($type, $arrOffNo))
            update_option($type, 'toggleoff', 'no');
        else
            update_option($type, 'toggleoff');
    }
}

function fifu_update_menu_options() {
    if (fifu_is_valid_nonce('nonce_fifu_form_skip'))
        fifu_update_option('fifu_input_skip', 'fifu_skip');

    if (fifu_is_valid_nonce('nonce_fifu_form_html_cpt'))
        fifu_update_option('fifu_input_html_cpt', 'fifu_html_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_square')) {
        fifu_update_option('fifu_input_square_mobile', 'fifu_square_mobile');
        fifu_update_option('fifu_input_square_desktop', 'fifu_square_desktop');
    }

    if (fifu_is_valid_nonce('nonce_fifu_form_debug'))
        fifu_update_option('fifu_input_debug', 'fifu_debug');

    if (fifu_is_valid_nonce('nonce_fifu_form_photon'))
        fifu_update_option('fifu_input_photon', 'fifu_photon');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_content'))
        fifu_update_option('fifu_input_cdn_content', 'fifu_cdn_content');

    if (fifu_is_valid_nonce('nonce_fifu_form_reset'))
        fifu_update_option('fifu_input_reset', 'fifu_reset');

    if (fifu_is_valid_nonce('nonce_fifu_form_fake'))
        fifu_update_option('fifu_input_fake', 'fifu_fake');

    if (fifu_is_valid_nonce('nonce_fifu_form_default_url'))
        fifu_update_option('fifu_input_default_url', 'fifu_default_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_default_cpt'))
        fifu_update_option('fifu_input_default_cpt', 'fifu_default_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_pcontent_types'))
        fifu_update_option('fifu_input_pcontent_types', 'fifu_pcontent_types');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_format'))
        fifu_update_option('fifu_input_hide_format', 'fifu_hide_format');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_type'))
        fifu_update_option('fifu_input_hide_type', 'fifu_hide_type');

    if (fifu_is_valid_nonce('nonce_fifu_form_enable_default_url'))
        fifu_update_option('fifu_input_enable_default_url', 'fifu_enable_default_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_wc_lbox'))
        fifu_update_option('fifu_input_wc_lbox', 'fifu_wc_lbox');

    if (fifu_is_valid_nonce('nonce_fifu_form_wc_zoom'))
        fifu_update_option('fifu_input_wc_zoom', 'fifu_wc_zoom');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide'))
        fifu_update_option('fifu_input_hide', 'fifu_hide');

    if (fifu_is_valid_nonce('nonce_fifu_form_pcontent_add'))
        fifu_update_option('fifu_input_pcontent_add', 'fifu_pcontent_add');

    if (fifu_is_valid_nonce('nonce_fifu_form_pcontent_remove'))
        fifu_update_option('fifu_input_pcontent_remove', 'fifu_pcontent_remove');

    if (fifu_is_valid_nonce('nonce_fifu_form_get_first'))
        fifu_update_option('fifu_input_get_first', 'fifu_get_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_ovw_first'))
        fifu_update_option('fifu_input_ovw_first', 'fifu_ovw_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_run_delete_all'))
        fifu_update_option('fifu_input_run_delete_all', 'fifu_run_delete_all');

    if (fifu_is_valid_nonce('nonce_fifu_form_data_clean'))
        fifu_update_option('fifu_input_data_clean', 'fifu_data_clean');

    // delete all run log
    if (fifu_is_on('fifu_run_delete_all'))
        update_option('fifu_run_delete_all_time', current_time('mysql'), 'no');

    // urgent updates
    $arr = array();
    if (isset($_POST['fifu_input_default_url'])) {
        $arr['fifu_default_url'] = wp_strip_all_tags($_POST['fifu_input_default_url']);
    } else {
        $default_url = get_option('fifu_default_url');
        $arr['fifu_default_url'] = $default_url ? $default_url : '';
    }

    if (isset($_POST['fifu_input_default_cpt'])) {
        $arr['fifu_default_cpt'] = wp_strip_all_tags($_POST['fifu_input_default_cpt']);
    } else
        $arr['fifu_default_cpt'] = null;

    if (isset($_POST['fifu_input_pcontent_types'])) {
        $arr['fifu_pcontent_types'] = wp_strip_all_tags($_POST['fifu_input_pcontent_types']);
    } else
        $arr['fifu_pcontent_types'] = null;

    if (isset($_POST['fifu_input_hide_format'])) {
        $arr['fifu_hide_format'] = wp_strip_all_tags($_POST['fifu_input_hide_format']);
    } else
        $arr['fifu_hide_format'] = null;

    if (isset($_POST['fifu_input_hide_type'])) {
        $arr['fifu_hide_type'] = wp_strip_all_tags($_POST['fifu_input_hide_type']);
    } else
        $arr['fifu_hide_type'] = null;

    return $arr;
}

function fifu_update_option($input, $field) {
    if (!isset($_POST[$input]))
        return;

    $value = $_POST[$input] ?? '';

    $arr_boolean = array('fifu_auto_alt', 'fifu_cdn_content', 'fifu_check', 'fifu_data_clean', 'fifu_decode', 'fifu_enable_default_url', 'fifu_fake', 'fifu_get_first', 'fifu_hide', 'fifu_pcontent_add', 'fifu_pcontent_remove', 'fifu_debug', 'fifu_ovw_first', 'fifu_photon', 'fifu_pop_first', 'fifu_reset', 'fifu_run_delete_all', 'fifu_wc_lbox', 'fifu_wc_zoom', 'fifu_cloud_upload_auto', 'fifu_cloud_delete_auto', 'fifu_cloud_hotlink');
    if (in_array($field, $arr_boolean)) {
        if (in_array($value, array('on', 'off')))
            update_option($field, 'toggle' . $value);
        return;
    }

    $arr_int = array('fifu_spinner_nth');
    if (in_array($field, $arr_int)) {
        if (filter_var($value, FILTER_VALIDATE_INT))
            update_option($field, $value);
        return;
    }

    $arr_square_type = array('fifu_square_mobile', 'fifu_square_desktop');
    if (in_array($field, $arr_square_type)) {
        if (in_array($value, array('', 'crop', 'extend')))
            update_option($field, $value);
        return;
    }

    $arr_url = array('fifu_default_url');
    if (in_array($field, $arr_url)) {
        if (empty($value) || filter_var($value, FILTER_VALIDATE_URL))
            update_option($field, esc_url_raw($value));
        return;
    }

    $arr_text = array('fifu_default_cpt', 'fifu_pcontent_types', 'fifu_hide_format', 'fifu_hide_type', 'fifu_skip', 'fifu_html_cpt');
    if (in_array($field, $arr_text))
        update_option($field, sanitize_text_field($value));
}

function fifu_enable_fake() {
    fifu_db_clear_meta_out();

    $result = fifu_db_get_meta_in_first();
    if (count($result) == 0) {
        fifu_db_prepare_meta_in();
        $result = fifu_db_get_meta_in_first();
    }

    if (isset($result[0])) {
        $url = rest_url() . "featured-image-from-url/v2/metain/";
        $transient_token = wp_generate_password(8, false);
        fifu_set_transient('fifu_api_metain_auth_token', $transient_token, MINUTE_IN_SECONDS);
        $body = json_encode(array(
            'post_id' => $result[0]->post_id,
        ));
        $headers = array(
            'Content-Type' => 'application/json',
            'X-FIFU-Authorization' => $transient_token,
        );
        $response = wp_remote_post($url, array(
            'method' => 'POST',
            'body' => $body,
            'headers' => $headers,
            'blocking' => true,
            'timeout' => 30,
        ));
    }
}

function fifu_disable_fake() {
    $result = fifu_db_get_meta_out_first();
    if (count($result) == 0) {
        fifu_db_prepare_meta_out();
        $result = fifu_db_get_meta_out_first();
    }

    if (isset($result[0])) {
        $url = rest_url() . "featured-image-from-url/v2/metaout/";
        $transient_token = wp_generate_password(8, false);
        fifu_set_transient('fifu_api_metaout_auth_token', $transient_token, MINUTE_IN_SECONDS);
        $body = json_encode(array(
            'post_id' => $result[0]->post_id,
        ));
        $headers = array(
            'Content-Type' => 'application/json',
            'X-FIFU-Authorization' => $transient_token,
        );
        $response = wp_remote_post($url, array(
            'method' => 'POST',
            'body' => $body,
            'headers' => $headers,
            'blocking' => true,
            'timeout' => 30,
        ));
    }
}

function fifu_version() {
    $plugin_data = get_plugin_data(FIFU_PLUGIN_DIR . 'featured-image-from-url.php');
    $name = $plugin_data['Name'] ?? '';
    $version = $plugin_data['Version'] ?? '';
    return $plugin_data && $name && $version ? $name . ':' . $version : '';
}

function fifu_version_number() {
    $plugin_data = get_plugin_data(FIFU_PLUGIN_DIR . 'featured-image-from-url.php');
    return $plugin_data['Version'] ?? '';
}

function fifu_version_number_enq() {
    if (fifu_is_on('fifu_debug'))
        return mt_rand();
    return fifu_version_number();
}

function fifu_su_sign_up_complete() {
    return isset(get_option('fifu_su_privkey')[0]) ? true : false;
}

function fifu_su_get_email() {
    $su_email_option = get_option('fifu_su_email');
    return base64_decode($su_email_option[0] ?? '');
}

function fifu_get_last($meta_key) {
    $list = '';
    foreach (fifu_db_get_last($meta_key) as $key => $row) {
        $aux = $row->meta_value . ' &#10; → ' . get_permalink($row->id);
        $list .= '&#10; - ' . $aux;
    }
    return $list;
}

function fifu_get_plugins_list() {
    $list = '';
    foreach (get_plugins() as $key => $domain) {
        $name = $domain['Name'] . ' (' . $domain['TextDomain'] . ')';
        $list .= '&#10; - ' . $name;
    }
    return $list;
}

function fifu_get_active_plugins_list() {
    $list = '';
    $active_plugins = get_option('active_plugins', []);
    $all_plugins = get_plugins();

    foreach ($active_plugins as $basename) {
        if (isset($all_plugins[$basename])) {
            $data = $all_plugins[$basename];
            $name = $data['Name'] ?? $basename;
            $text_domain = $data['TextDomain'] ?? '';
            $author = isset($data['Author']) ? wp_strip_all_tags($data['Author']) : '';

            $display = $name;
            if ($text_domain !== '') {
                $display .= ' (' . $text_domain . ')';
            }
            if ($author !== '') {
                $display .= ': ' . $author;
            }
        } else {
            // Fallback to directory name if metadata is missing
            $parts = explode('/', $basename);
            $display = $parts[0] ?? $basename;
        }

        $list .= '&#10; - ' . $display;
    }
    return $list;
}

function fifu_get_registered_sizes() {
    $raw_sizes = fifu_db_select_option_prefix('fifu_detected_size_');
    $formatted_list = '';

    if ($raw_sizes && is_array($raw_sizes)) {
        foreach ($raw_sizes as $size) {
            // Extract the name by removing the prefix
            $name = str_replace('fifu_detected_size_', '', $size->option_name);

            // Unserialize the value to get width, height and crop
            $data = maybe_unserialize($size->option_value);

            if (is_array($data) && isset($data['w']) && isset($data['h']) && isset($data['c'])) {
                $crop_value = $data['c'] ? '1' : '0';
                $formatted_list .= '&#10; - ' . $name . ': ' . $data['w'] . 'x' . $data['h'] . 'x' . $crop_value;
            }
        }
    }

    return $formatted_list ?: '&#10; - No registered sizes found';
}

function fifu_is_valid_nonce($nonce, $action = FIFU_ACTION_SETTINGS) {
    return isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], $action);
}


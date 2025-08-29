<?php
// Silence is golden.

function hostelworld_author_meta()
{
    global $post;
    if ($post) {
        $authorArray = get_the_terms($post, 'authors');
        if (is_array($authorArray) && count($authorArray)) {
            $author = $authorArray[0];
            echo sprintf(
                '<span class="article-author author vcard" itemscope="itemscope" itemprop="author" itemtype="https://schema.org/Person"><a class="url fn" href="%1$s" title="%2$s" rel="author" itemprop="url"><span itemprop="name">%3$s</span></a></span>',
                esc_url(get_term_link($author)),
                esc_attr(sprintf(__('View all posts by %s', 'page-builder-framework'), $author->name)),
                esc_html($author->name)
            );

            echo '<span class="article-meta-separator">' . apply_filters('wpbf_article_meta_separator', ' | ') . '</span>';
        } else {
            echo sprintf(
                '<span class="article-author author vcard" itemscope="itemscope" itemprop="author" itemtype="https://schema.org/Person"><span itemprop="name">%1$s</span></span>',
                esc_html(get_the_author())
            );
            echo '<span class="article-meta-separator">' . apply_filters('wpbf_article_meta_separator', ' | ') . '</span>';
        }

    }
}

add_action('wpbf_before_author_meta', function () {
    remove_action('wpbf_author_meta', 'wpbf_do_author_meta');
    add_action('wpbf_author_meta', 'hostelworld_author_meta');
});

function hostelworld_add_custom_taxonomies()
{
    // Add new "Authors" taxonomy to Posts
    register_taxonomy('authors', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x('Authors', 'taxonomy general name'),
            'singular_name' => _x('Author', 'taxonomy singular name'),
            'search_items' => __('Search Authors'),
            'all_items' => __('All Authors'),
            'parent_item' => __('Parent Author'),
            'parent_item_colon' => __('Parent Author:'),
            'edit_item' => __('Edit Author'),
            'update_item' => __('Update Author'),
            'add_new_item' => __('Add New Author'),
            'new_item_name' => __('New Author Name'),
            'menu_name' => __('Authors'),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'authors', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/authors/"
            'hierarchical' => false // This will allow URL's like "/authors/boston/cambridge/"
        ),
    ));
}

add_action('init', 'hostelworld_add_custom_taxonomies', 0);

function authors_add_term_fields($term)
{
    echo '<tr class="form-field">  
    <th scope="row" valign="top">  
        <label for="presenter_id">Email</label>  
    </th>  
    <td>  
        <input type="email" name="email" id="email" value=""><br />  
        <span class="description">Only used to generate avatar.</span>
</td>
</tr>  ';
}

function authors_edit_term_fields($term)
{
    $t_id = $term->term_id; // Get the ID of the term you're editing
    $term_meta = get_term_meta($t_id, "email", true); // Do the check
    echo '<tr class="form-field">  
    <th scope="row" valign="top">  
        <label for="presenter_id">Email</label>  
    </th>  
    <td>  
        <input type="email" name="email" id="email" value="' . $term_meta . '"><br />  
        <span class="description">Only used to generate avatar.</span>
</td>
</tr>  ';
}

add_action('authors_add_form_fields', 'authors_add_term_fields');
add_action('authors_edit_form_fields', 'authors_edit_term_fields');

function save_authors_custom_fields($term_id)
{

    if (isset($_POST['email'])) {
        $t_id = $term_id;
        //save the option array
        update_term_meta($t_id, 'email', sanitize_email($_POST['email']));
    }
}

add_action('edited_authors', 'save_authors_custom_fields', 10, 2);

function hostelworld_display_author_info()
{
    global $post;
    if ($post->post_type != 'post')
        return false;

    $authorArray = get_the_terms($post, 'authors');
    if (is_array($authorArray) && count($authorArray)) {
        $author = $authorArray[0];
    }

    if (!$author)
        return false;

    $user_email = get_term_meta($author->term_id, "email", true);
    $usergravatar = 'http://www.gravatar.com/avatar/' . md5($user_email) . '?s=128';

    echo ' <div id="author-info" class="large-12 columns">
                                
                                    
                                    <div>
            <img alt="" src="' . $usergravatar . '"  alt="' . $author->name . '" />
            <section class="bio left">
            <h3>About The Author</h3>
                <h4 class="author-name"><a href="' . esc_url(get_term_link($author)) . '">' . $author->name . '</a></h4>
                <p class="author-description">' . nl2br($author->description) . ' </p >
            </section >
            </div></div > ';
}

add_action('wpbf_article_close', 'hostelworld_display_author_info');


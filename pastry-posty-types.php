<?php
/**
 * Registers all Custom Post Types and Taxonomies.
 */
function pastry_custom_post_types() {

    // 1. === Register Post Type: EVENT ===
    register_post_type('event', array(
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields')
    ));

    // 2. === Register Post Type: LOCALE ===
    register_post_type('locale', array(
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'locales'),
        'has_archive' => true,
        'public' => true,
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Locales', // Changed to 'Locales' for consistency
            'add_new_item' => 'Add New Locale',
            'edit_item' => 'Edit Locale',
            'all_items' => 'All Locales',
            'singular_name' => 'Locale'
        ),
        'menu_icon' => 'dashicons-admin-site-alt',
        'supports' => array('title', 'editor', 'thumbnail','page-attributes')
    ));

    // 3. === Register Post Type: PROFESSOR (Chef) ===
    register_post_type('professor', array(
        'show_in_rest' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Professors', // Changed to plural for consistency
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => array('title', 'editor', 'thumbnail')
    ));

    // 4. === Register Post Type: PASTRY CASE ===
    register_post_type('pastry_case', array(
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'pastry-case'),
        'public' => true,
        'has_archive' => true,
        'labels' => array(
            'name' => 'Pastry Case',
            'add_new_item' => 'Add New Pastry',
            'edit_item' => 'Edit Pastry',
            'all_items' => 'All Pastries',
            'singular_name' => 'Pastry',

        ),
        'menu_icon' => 'dashicons-food',
        // CRITICAL: Links the CPT to the Taxonomy below
        'taxonomies' => array('pastry_category')
    ));


    // 5. === Register Taxonomy: PASTRY CATEGORY ===
    register_taxonomy( 'pastry_category', array('pastry_case'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Pastry Categories',
            'singular_name' => 'Pastry Category',
            'menu_name' => 'Categories',
            'all_items' => 'All Categories',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'pastry-category' ),
    ));

}
add_action('init', 'pastry_custom_post_types');
?>

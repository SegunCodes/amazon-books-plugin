<?php

if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type for Books
function abi_register_book_post_type() {
    $labels = array(
        'name'               => 'Amazon Books',
        'singular_name'      => 'Amazon Book',
        'menu_name'          => 'Amazon Books',
        'name_admin_bar'     => 'Amazon Book',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Book',
        'new_item'           => 'New Book',
        'edit_item'          => 'Edit Book',
        'view_item'          => 'View Book',
        'all_items'          => 'All Books',
        'search_items'       => 'Search Books',
        'not_found'          => 'No books found.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'amazon-books'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail')
    );

    register_post_type('amazon_books', $args);
}
add_action('init', 'abi_register_book_post_type');
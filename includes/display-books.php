<?php
if (!defined('ABSPATH')) {
    exit;
}

// Shortcode to Display Genre Filter Form
function abi_genre_filter_form() {
    $genres = array('Fiction', 'Science', 'Romance', 'History', 'Fantasy');

    $current_genre = isset($_GET['genre']) ? sanitize_text_field($_GET['genre']) : '';

    $output = '<form method="GET">';
    $output .= '<label for="genre">Select Genre:</label>';
    $output .= '<select name="genre" id="genre" onchange="this.form.submit()">';
    $output .= '<option value="">All Genres</option>';
    
    foreach ($genres as $genre) {
        $selected = ($current_genre === $genre) ? 'selected' : '';
        $output .= "<option value='$genre' $selected>$genre</option>";
    }
    
    $output .= '</select>';
    $output .= '</form>';

    return $output;
}
add_shortcode('amazon_genre_filter', 'abi_genre_filter_form');


// Shortcode to Display Books (With Genre Filtering)
function abi_display_books() {
    $current_genre = isset($_GET['genre']) ? sanitize_text_field($_GET['genre']) : '';

    $meta_query = array();
    if (!empty($current_genre)) {
        $meta_query[] = array(
            'key'     => 'book_genre',
            'value'   => $current_genre,
            'compare' => '='
        );
    }

    $args = array(
        'post_type'      => 'amazon_books',
        'posts_per_page' => 10,
        'meta_query'     => $meta_query,
    );

    $query = new WP_Query($args);

    $output = '<div class="amazon-books">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="book-item">';
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<div class="book-content">' . get_the_content() . '</div>';
            $output .= '</div>';
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No books found.</p>';
    }

    $output .= '</div>';

    return $output;
}
add_shortcode('amazon_books', 'abi_display_books');
?>
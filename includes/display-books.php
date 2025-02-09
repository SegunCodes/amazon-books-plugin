<?php
if (!defined('ABSPATH')) {
    exit;
}

// Display Books on Frontend
function abi_display_books($atts) {
    $args = array(
        'post_type' => 'amazon_books',
        'posts_per_page' => 10,
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $output = '<div class="amazon-books">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="book-item">';
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<div class="book-content">' . get_the_content() . '</div>';
            $output .= '</div>';
        }
        wp_reset_postdata();
        $output .= '</div>';
    } else {
        $output = '<p>No books found.</p>';
    }
    
    return $output;
}
add_shortcode('amazon_books', 'abi_display_books');
?>

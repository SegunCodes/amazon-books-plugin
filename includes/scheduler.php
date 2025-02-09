<?php

if (!defined('ABSPATH')) {
    exit;
}

// Schedule Book Fetching Task
function abi_schedule_cron_job() {
    if (!wp_next_scheduled('abi_fetch_books_cron')) {
        wp_schedule_event(time(), 'daily', 'abi_fetch_books_cron');
    }
}
add_action('abi_fetch_books_cron', 'abi_fetch_books_from_amazon');

function abi_admin_sync_button() {
    if (isset($_POST['abi_manual_sync'])) {
        abi_fetch_books_from_amazon();
        echo '<div class="updated"><p>Books updated successfully!</p></div>';
    }
    echo '<form method="post"><input type="submit" name="abi_manual_sync" class="button button-primary" value="Sync Books Now"></form>';
}

function abi_add_sync_button_to_admin_menu() {
    add_submenu_page('abi-settings', 'Sync Books', 'Sync Books', 'manage_options', 'abi-sync', 'abi_admin_sync_button');
}
add_action('admin_menu', 'abi_add_sync_button_to_admin_menu');
?>
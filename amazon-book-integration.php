<?php
/*
Plugin Name: Amazon Book Integration
Description: Fetch and display books from Amazon by genre with purchase links.
Version: 1.0
Author: Olusegun Joe-Alabi
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

define('ABI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ABI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ABI_API_KEY', get_option('abi_api_key'));
define('ABI_API_SECRET', get_option('abi_api_secret'));
define('ABI_ASSOCIATE_TAG', get_option('abi_associate_tag'));

// Include necessary files
require_once ABI_PLUGIN_PATH . 'includes/book-post-type.php';
require_once ABI_PLUGIN_PATH . 'includes/api-handler.php';
require_once ABI_PLUGIN_PATH . 'includes/admin-settings.php';
require_once ABI_PLUGIN_PATH . 'includes/display-books.php';
require_once ABI_PLUGIN_PATH . 'includes/scheduler.php';

// Activation & Deactivation Hooks
function abi_activate_plugin() {
    flush_rewrite_rules();
    abi_schedule_cron_job();
}
register_activation_hook(__FILE__, 'abi_activate_plugin');

function abi_deactivate_plugin() {
    wp_clear_scheduled_hook('abi_fetch_books_cron');
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'abi_deactivate_plugin');
?>
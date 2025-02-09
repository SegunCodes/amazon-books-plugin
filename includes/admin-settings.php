<?php 

if (!defined('ABSPATH')) {
    exit;
}

function abi_register_settings() {
    add_option('abi_api_key', '');
    add_option('abi_api_secret', '');
    add_option('abi_associate_tag', '');
    add_option('abi_genre', 'fiction');
    register_setting('abi_options_group', 'abi_api_key');
    register_setting('abi_options_group', 'abi_api_secret');
    register_setting('abi_options_group', 'abi_associate_tag');
    register_setting('abi_options_group', 'abi_genre');
}
add_action('admin_init', 'abi_register_settings');

function abi_register_options_page() {
    add_menu_page('Amazon Book Integration', 'Amazon Books', 'manage_options', 'abi-settings', 'abi_options_page');
}
add_action('admin_menu', 'abi_register_options_page');

function abi_options_page() {
    ?>
    <div class="wrap">
        <h1>Amazon Book Integration Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('abi_options_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Amazon API Key</th>
                    <td><input type="text" name="abi_api_key" value="<?php echo get_option('abi_api_key'); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Amazon API Secret</th>
                    <td><input type="text" name="abi_api_secret" value="<?php echo get_option('abi_api_secret'); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Amazon Associate Tag</th>
                    <td><input type="text" name="abi_associate_tag" value="<?php echo get_option('abi_associate_tag'); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Book Genre</th>
                    <td><input type="text" name="abi_genre" value="<?php echo get_option('abi_genre'); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
?>
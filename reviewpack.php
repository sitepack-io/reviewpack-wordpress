<?php

if (!defined('ABSPATH')) {
    http_send_status(403);
    exit;
}

define('REVIEWPACK_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('REVIEWPACK_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin Name: ReviewPack - Company and Product Reviews
 * Plugin URI: https://reviewpack.eu
 * Description: Collect feedback about your company and site with this plugin for ReviewPack. Improve your business quality and sales.
 * Version: 1.0.0
 * Author: Team ReviewPack
 * Author URI: https://reviewpack.eu
 * Text Domain: reviewpack
 * Domain Path: /languages/
 *
 * @package ReviewPack
 */

include("vendor/autoload.php");

if (is_admin()) {
    $api = new \ReviewPack\Resources\Api();
    $settings = new \ReviewPack\Admin\Settings($api);

    reviewpack_admin_init($settings, $api);
}

function reviewpack_admin_init($settings, $api)
{
    $admin = new \ReviewPack\Admin\Admin($settings, $api);
    $admin->registerHooks();
}
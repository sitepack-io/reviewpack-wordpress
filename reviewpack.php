<?php

if (!defined('ABSPATH')) {
    http_send_status(403);
    exit;
}

define('REVIEWPACK_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('REVIEWPACK_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin Name: ReviewPack Reviews
 * Plugin URI: https://reviewpack.eu
 * Description: Collect feedback about your company and site with this plugin for ReviewPack. Improve your business quality and sales.
 * Version: 1.0.3
 * Author: Team ReviewPack
 * Author URI: https://reviewpack.eu/about-us
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
else{
    $api = new \ReviewPack\Resources\Api();
    $settings = new \ReviewPack\Admin\Settings($api);

    reviewpack_frontend_init($settings, $api);
}

$shortcodes = new \ReviewPack\Frontend\Shortcodes($api, $settings);

##########################################
# Get review scores with these functions #
##########################################

/**
 * Get the review score about the configured company. The avg_score value is a 0-50 value, where 50 is the best rating.
 *
 * @return \ReviewPack\Models\CompanyScore
 */
function reviewpack_score()
{
    $cached = get_transient('reviewpack_company_score');
    if (!empty($cached)) {
        return $cached;
    }

    try {
        $api = new \ReviewPack\Resources\Api();
        $company = reviewpack_api_tokens($api);
        $score = $api->getCompanyScore(
            $company['token'],
            $company['secret'],
            $company['company']
        );

        // Cache the data for 10 minutes
        set_transient(
            'reviewpack_company_score',
            $score,
            (60 * 10)
        );

        return $score;
    } catch (\Exception $exception) {
        return null;
    }
}

/**
 * Get the company details form ReviewPack.
 *
 * @return \ReviewPack\Models\Company
 */
function reviewpack_company_details()
{
    $cached = get_transient('reviewpack_company_details');
    if (!empty($cached)) {
        return $cached;
    }

    try {
        $api = new \ReviewPack\Resources\Api();
        $company = reviewpack_api_tokens($api);
        $details = $api->getCompanyDetails(
            $company['token'],
            $company['secret'],
            $company['company']
        );

        // Cache the data for 10 minutes
        set_transient(
            'reviewpack_company_details',
            $details,
            (60 * 10)
        );

        return $details;
    } catch (\Exception $exception) {
        return null;
    }
}

/**
 * Do no call this directly, this is a hook for admin_init
 *
 * @param $settings
 * @param $api
 */
function reviewpack_admin_init($settings, $api)
{
    $admin = new \ReviewPack\Admin\Admin($settings, $api);
    $admin->registerHooks();
}

/**
 * Do no call this directly, this is a hook for frontend_init
 *
 * @param $settings
 * @param $api
 */
function reviewpack_frontend_init($settings, $api)
{
    $frontend = new \ReviewPack\Frontend\Frontend($settings, $api);
    $frontend->registerHooks();
}

/**
 * Get the ReviewPack access tokens
 *
 * @param $api
 * @return array
 */
function reviewpack_api_tokens($api)
{
    $settings = new \ReviewPack\Admin\Settings($api);

    return [
        'token' => $settings->getOption(\ReviewPack\Admin\Settings::SETTING_API_TOKEN),
        'secret' => $settings->getOption(\ReviewPack\Admin\Settings::SETTING_API_SECRET),
        'company' => $settings->getOption(\ReviewPack\Admin\Settings::SETTING_COMPANY_UUID)
    ];
}
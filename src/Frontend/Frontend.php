<?php

namespace ReviewPack\Frontend;

use ReviewPack\Admin\Settings;
use ReviewPack\Resources\Api;

/**
 * Class Frontend
 * @package ReviewPack\Frontend
 */
class Frontend
{

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var Api
     */
    private $api;

    /**
     * Admin constructor.
     * @param Settings $settings
     * @param Api $api
     */
    public function __construct(Settings $settings, Api $api)
    {
        $this->settings = $settings;
        $this->api = $api;
    }

    /**
     * Register all admin hooks in the ReviewPack / WordPress admin area
     */
    public function registerHooks()
    {
        add_action('wp_head', [$this, 'addFrontendHeadCode']);
    }

    /**
     * Hook the frontend widget code
     */
    public function addFrontendHeadCode()
    {
        echo "<!-- ReviewPack reviews widget, more information on: https://reviewpack.eu -->" . PHP_EOL;
        echo '<script type="text/javascript" src="https://reviewpack.eu/js/widget.min.js" async></script>' . PHP_EOL;
//        echo '<script type="text/javascript" src="http://reviewpack.local:8000/js/widget.min.js" async></script>' . PHP_EOL;
        echo "<!-- / ReviewPack -->" . PHP_EOL;
    }

}
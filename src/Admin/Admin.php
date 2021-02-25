<?php

namespace ReviewPack\Admin;

use ReviewPack\Resources\Api;

/**
 * Class Admin
 * @package ReviewPack\Admin
 */
class Admin
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
        add_action('admin_menu', [$this, 'registerSettingsPage']);
        add_action('admin_init', [$this->settings, 'registerSettings']);
        add_action('admin_init', [$this, 'adminInit']);
        add_action('admin_enqueue_scripts', [$this, 'addAdminStyles']);
        add_action('admin_menu', [$this, 'reviewPackAdminPages']);
        add_action('admin_head', [$this, 'registerShortcodeWidget']);
        add_action('wp_dashboard_setup', [$this, 'registerDashboardWidgets']);

        $this->registerInviteHooks();
    }

    /**
     * Add a widget for the ReviewPack status
     */
    public function registerDashboardWidgets()
    {
        wp_add_dashboard_widget('reviewpack_reviews', __('ReviewPack reviews', 'reviewpack'), [$this, 'renderDashboardWidget']);
    }

    /**
     * Render the library for widgets (in admin)
     */
    public function registerShortcodeWidget()
    {
        echo '<script type="text/javascript" src="https://reviewpack.eu/js/widget.min.js" async></script>' . PHP_EOL;
//        echo '<script type="text/javascript" src="http://reviewpack.local:8000/js/widget.min.js" async></script>' . PHP_EOL;
    }

    /**
     * Register the invite hooks
     */
    private function registerInviteHooks()
    {
        $integrationsHandler = new Integrations($this->settings, $this->api);
        $integrations = $this->settings->getOption(Settings::SETTING_INTEGRATIONS);

        if (\is_array($integrations) && \in_array('woocommerce', $integrations)) {
            add_action('woocommerce_order_status_completed', [$integrationsHandler, 'createWooCommerceInvite']);

            // debugging actions:
//            add_action('woocommerce_order_status_processing', [$integrationsHandler, 'createWooCommerceInvite']);
//        add_action( 'woocommerce_order_status_refunded', 'mysite_refunded');
//        add_action( 'woocommerce_order_status_cancelled', 'mysite_cancelled');
        }
    }

    /**
     * Triggered an admin init hook
     */
    public function adminInit()
    {

        if (empty($this->settings->getOption(Settings::SETTING_API_TOKEN)) || empty($this->settings->getOption(Settings::SETTING_API_SECRET))) {
            if (\stripos($_SERVER['REQUEST_URI'], 'reviewpack-settings') === false) {
                add_action('admin_notices', [$this, 'noticeActivateAccount']);
            }
        } elseif ($this->settings->getOption(Settings::SETTING_COMPANY_UUID) !== null) {
            if (\stripos($_SERVER['REQUEST_URI'], 'reviewpack-settings') === false) {
                $integrations = $this->settings->getOption(Settings::SETTING_INTEGRATIONS);

                if ($integrations === null || \count($integrations) === 0) {
                    add_action('admin_notices', [$this, 'noticeIntegration']);
                }
            }
        }
    }

    /**
     * Link reviewpack menu pages
     */
    public function reviewPackAdminPages()
    {
        remove_menu_page('options-general.php?page=reviewpack-settings');

        add_menu_page(__('ReviewPack dashboard', 'reviewpack'), 'ReviewPack', 'manage_options', 'reviewpack', [$this, 'renderDashboardPage'], 'dashicons-star-filled', 2);
//        add_submenu_page('reviewpack', 'Settings', _('Dashboard'), 'manage_options', 'admin.php?page=reviewpack-dashboard');
        add_submenu_page('reviewpack', 'Widgets', __('Widgets', 'reviewpack'), 'manage_options', 'reviewpack-widgets', [$this, 'renderWidgetsPage']);
        add_submenu_page('reviewpack', 'Settings', __('Invite mail', 'reviewpack'), 'manage_options', 'reviewpack-invites', [$this, 'renderInvitesPage']);
    }

    /**
     * Register the menu item in the settings item
     */
    public function registerSettingsPage()
    {
        add_options_page(__('ReviewPack Settings'), __('ReviewPack'), 'manage_options', 'reviewpack-settings', [$this, 'renderSettingsPage']);
    }

    /**
     * Show an integration notice
     */
    public function noticeIntegration()
    {
        echo '<div class="notice notice-warning"><p>';
        echo __('Please activate an integration in the ReviewPack settings. Otherwise we will not be able to send invitations automatically!', 'reviewpack');
        echo ' <a href="' . admin_url('options-general.php?page=reviewpack-settings') . '" class="button">' . __('Configure now', 'reviewpack') . '</a>';
        echo '</p></div>';
    }

    /**
     * Show an activate account notice
     */
    public function noticeActivateAccount()
    {
        echo '<div class="notice notice-warning"><p>';
        echo __('Please validate the ReviewPack plugin settings and connect a (free) ReviewPack account. Otherwise we will not be able to send invitations!', 'reviewpack');
        echo ' <a href="' . admin_url('options-general.php?page=reviewpack-settings') . '" class="button">' . __('Configure now', 'reviewpack') . '</a>';
        echo '</p></div>';
    }

    /**
     * Build the settings page
     */
    public function renderSettingsPage()
    {
        $isConnected = $this->isConnected();

        include(REVIEWPACK_PLUGIN_DIR . '/views/admin_settings.php');
    }

    /**
     * Build the widgets page
     */
    public function renderWidgetsPage()
    {
        $isConnected = $this->isConnected();

        include(REVIEWPACK_PLUGIN_DIR . '/views/admin_widgets.php');
    }

    /**
     * Build the settings page
     */
    public function renderDashboardPage()
    {
        $isConnected = $this->isConnected();

        if ($isConnected === true) {
            $companyDetails = $this->api->getCompanyDetails(
                $this->settings->getOption(Settings::SETTING_API_TOKEN),
                $this->settings->getOption(Settings::SETTING_API_SECRET),
                $this->settings->getOption(Settings::SETTING_COMPANY_UUID)
            );
            $companyScore = $this->api->getCompanyScore(
                $this->settings->getOption(Settings::SETTING_API_TOKEN),
                $this->settings->getOption(Settings::SETTING_API_SECRET),
                $this->settings->getOption(Settings::SETTING_COMPANY_UUID)
            );
            $recentReviews = $this->api->getRecentReviews(
                $this->settings->getOption(Settings::SETTING_API_TOKEN),
                $this->settings->getOption(Settings::SETTING_API_SECRET),
                $this->settings->getOption(Settings::SETTING_COMPANY_UUID)
            );
            $recentInvites = $this->api->getRecentInvites(
                $this->settings->getOption(Settings::SETTING_API_TOKEN),
                $this->settings->getOption(Settings::SETTING_API_SECRET),
                $this->settings->getOption(Settings::SETTING_COMPANY_UUID)
            );
        }

        include(REVIEWPACK_PLUGIN_DIR . '/views/admin_dashboard.php');
    }

    /**
     * Build the settings page
     */
    public function renderInvitesPage()
    {
        $isConnected = $this->isConnected();

        if ($isConnected === true) {
            $inviteTemplate = $this->api->getInviteTemplate(
                $this->settings->getOption(Settings::SETTING_API_TOKEN),
                $this->settings->getOption(Settings::SETTING_API_SECRET),
                $this->settings->getOption(Settings::SETTING_COMPANY_UUID),
                Settings::INVITE_TEMPLATE_FIRST_MAIL
            );
            $company = get_transient(Settings::TRANSIENT_CURRENT_COMPANY);
        }

        include(REVIEWPACK_PLUGIN_DIR . '/views/admin_invites.php');
    }

    /**
     * Render the actual dashboard widget
     */
    public function renderDashboardWidget()
    {
        $isConnected = $this->isConnected();

        if ($isConnected === true) {
            try {
                $company = $this->api->getCompanyScore(
                    $this->settings->getOption(Settings::SETTING_API_TOKEN),
                    $this->settings->getOption(Settings::SETTING_API_SECRET),
                    $this->settings->getOption(Settings::SETTING_COMPANY_UUID)
                );

                $rating = $company->avg_score;
                include(REVIEWPACK_PLUGIN_DIR . '/views/elements/rating.php');
                echo '<br/><br />';


                echo '<strong>' . __('Total reviews', 'reviewpack') .':</strong><br />' . esc_attr($company->total_reviews) . '<br /><br />';

                echo ' <a href="' . esc_url($company->public_url) . '" class="button" target="_blank" rel="noopener">' . __('Open public page', 'reviewpack') . '</a>';
            } catch (\Exception $exception) {
                echo __('Whoops: could not fetch data:', 'reviewpack') . '<br />' . esc_html($exception->getMessage());
            }
        } else {
            echo __('The plugin is not connected with ReviewPack.', 'reviewpack') . '<br /><br />';
            echo ' <a href="' . admin_url('options-general.php?page=reviewpack-settings') . '" class="button">' . __('Configure now', 'reviewpack') . '</a>';
        }
    }

    /**
     * Add admin styles
     */
    public function addAdminStyles()
    {
        wp_enqueue_style('admin-reviewpack-styles', plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) . '/assets/reviewpack_admin.css');
    }

    /**
     * @return bool
     */
    private function isConnected()
    {
        $transient = get_transient(Settings::TRANSIENT_CURRENT_COMPANY);

        if (empty($transient)) {
            if (!empty($this->settings->getOption(Settings::SETTING_API_TOKEN)) && !empty($this->settings->getOption(Settings::SETTING_API_SECRET))) {
                try {
                    $this->api->validateCompany(
                        $this->settings->getOption(Settings::SETTING_API_TOKEN),
                        $this->settings->getOption(Settings::SETTING_API_SECRET)
                    );

                    return true;
                } catch (\Exception $exception) {
                    return false;
                }
            }

            return false;
        }

        if (!empty($transient->uuid)) {
            return true;
        }

        return false;
    }

}
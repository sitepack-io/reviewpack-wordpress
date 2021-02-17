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
        add_action('admin_enqueue_scripts', [$this, 'addAdminStyles']);
        add_action('admin_menu', [$this, 'my_menu_pages']);

    }

    public function my_menu_pages()
    {
        remove_menu_page('options-general.php?page=reviewpack-settings');

        add_menu_page(__('ReviewPack dashboard', 'reviewpack'), 'ReviewPack', 'manage_options', 'reviewpack', [$this, 'renderAdminPage'], 'dashicons-star-filled', 60.43985748);
//        add_submenu_page('reviewpack-dashboard', 'Settings', _('Dashboard'), 'manage_options', 'admin.php?page=reviewpack-dashboard');
        add_submenu_page('reviewpack', 'Settings', __('Invite mail', 'reviewpack'), 'manage_options', 'reviewpack-invites', [$this, 'renderAdminPage']);
    }

    /**
     * Register the menu item in the settings item
     */
    public function registerSettingsPage()
    {
        add_options_page(__('ReviewPack Settings'), __('ReviewPack'), 'manage_options', 'reviewpack-settings', [$this, 'renderSettingsPage']);
    }

    /**
     *
     */
    public function renderAdminPage()
    {
        if (!isset($_GET['page'])) {
            echo '<p>Invalid callback</p>';
            return;
        }

        $page = $_GET['page'];

        switch ($page) {
            case 'reviewpack':
                $this->renderDashboardPage();
                break;
            case 'reviewpack-invites':
                $this->renderInvitesPage();
                break;
        }
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
        }

        include(REVIEWPACK_PLUGIN_DIR . '/views/admin_dashboard.php');
    }

    /**
     * Build the settings page
     */
    public function renderInvitesPage()
    {
        $isConnected = $this->isConnected();

        if($isConnected === true){
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
            if (!empty(Settings::SETTING_API_TOKEN) && !empty(Settings::SETTING_API_SECRET)) {
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

        if (empty($transient->uuid)) {
            return false;
        }

        return true;
    }


}
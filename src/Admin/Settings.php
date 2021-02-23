<?php

namespace ReviewPack\Admin;

use ReviewPack\Models\Company;
use ReviewPack\Resources\Api;

/**
 * Class Settings
 * @package ReviewPack\Admin
 */
class Settings
{

    const OPTIONS_DEFAULT = [
        self::SETTING_API_TOKEN => '',
        self::SETTING_API_SECRET => '',
        self::SETTING_DAYS_INVITE => 4,
        self::SETTING_COMPANY_UUID => '',
    ];

    const SETTING_API_TOKEN = 'api_key';
    const SETTING_API_SECRET = 'api_secret';
    const SETTING_DAYS_INVITE = 'days_invite';
    const SETTING_COMPANY_UUID = 'company_uuid';
    const SETTING_INTEGRATIONS = 'integrations';

    const TRANSIENT_NEWEST_REVIEWS = 'reviewpack_newest_reviews';
    const TRANSIENT_NEWEST_INVITES = 'reviewpack_newest_invites';
    const TRANSIENT_CURRENT_COMPANY = 'reviewpack_transient_data';

    const INVITE_TEMPLATE_FIRST_MAIL = 1;
    const INVITE_TEMPLATE_REMINDER_MAIL = 2;

    /**
     * @var Api
     */
    private $api;

    /**
     * Settings constructor.
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Register the settings for the admin panel.
     */
    public function registerSettings()
    {
        register_setting('reviewpack_plugin_options', 'reviewpack_plugin_options', [$this, 'reviewpack_plugin_options_validate']);
        add_settings_section('api_settings', 'API Settings', [$this, 'reviewpack_section_text'], 'reviewpack_settings');

        add_settings_field('reviewpack_setting_api_key', __('API token', 'reviewpack'), [$this, 'reviewpack_setting_api_key'], 'reviewpack_settings', 'api_settings');
        add_settings_field('reviewpack_setting_api_secret', __('API secret', 'reviewpack'), [$this, 'reviewpack_setting_api_secret'], 'reviewpack_settings', 'api_settings');

        add_settings_section('intergation_settings', __('WordPress integrations', 'reviewpack'), [$this, 'reviewpack_section_text_integrations'], 'reviewpack_settings');
        add_settings_field('reviewpack_setting_integrations', __('Integrations', 'reviewpack'), [$this, 'reviewpack_setting_integrations'], 'reviewpack_settings', 'intergation_settings');

        if (!empty($this->getOption(self::SETTING_API_TOKEN)) && !empty($this->getOption(self::SETTING_API_SECRET))) {
            add_settings_section('company_settings', __('Company settings', 'reviewpack'), [$this, 'reviewpack_section_text_company'], 'reviewpack_settings');
//            add_settings_field('reviewpack_setting_company', __('Company', 'reviewpack'), [$this, 'reviewpack_setting_company'], 'reviewpack_settings', 'company_settings');
        }

    }

    /**
     * @param $input
     * @return array
     */
    public function reviewpack_plugin_options_validate($input)
    {
        $validatedInput[self::SETTING_API_TOKEN] = \trim($input[self::SETTING_API_TOKEN]);
        $validatedInput[self::SETTING_API_SECRET] = \trim($input[self::SETTING_API_SECRET]);
        $validatedInput[self::SETTING_COMPANY_UUID] = '';

        if (!empty($input[self::SETTING_COMPANY_UUID])) {
            $validatedInput[self::SETTING_COMPANY_UUID] = \trim($input[self::SETTING_COMPANY_UUID]);

            try {
                $isValidCompany = $this->api->validateCompany(
                    $validatedInput[self::SETTING_API_TOKEN],
                    $validatedInput[self::SETTING_API_SECRET]
                );
            } catch (\Exception $exception) {
                $isValidCompany = false;
            }

            if ($isValidCompany === false) {
                // reset key
                $validatedInput[self::SETTING_COMPANY_UUID] = '';
                delete_transient(self::TRANSIENT_CURRENT_COMPANY);

                add_action('admin_notices', [$this, 'reviewpack_api_error_notice']);
            }
        } else {
            // try to set first company with credentials
            try {
                $isValidCompany = $this->api->validateCompany(
                    $validatedInput[self::SETTING_API_TOKEN],
                    $validatedInput[self::SETTING_API_SECRET]
                );
                $validatedInput[self::SETTING_COMPANY_UUID] = $isValidCompany->uuid;
            } catch (\Exception $exception) {
                // reset key
                $validatedInput[self::SETTING_COMPANY_UUID] = '';
                delete_transient(self::TRANSIENT_CURRENT_COMPANY);
            }
        }

        $validatedInput[self::SETTING_INTEGRATIONS] = [];

        if (isset($input[self::SETTING_INTEGRATIONS])) {
            foreach ($input[self::SETTING_INTEGRATIONS] as $integration) {
                $validatedInput[self::SETTING_INTEGRATIONS][] = \trim($integration);
            }
        }

        return $validatedInput;
    }

    /**
     * Render the settings text
     */
    public function reviewpack_section_text()
    {
        echo '<p>' . __('Copy and paste the API token and secret here. You can find the tokens in the ReviewPack portal, at the Sales Channel section.', 'reviewpack') . '</p>';
    }

    /**
     * Render the settings text for the company section
     */
    public function reviewpack_section_text_company()
    {
        echo '<p>' . __('This company is linked with your API credentials and will be used on this WordPress site.', 'reviewpack') . '</p>';

        $data = get_transient(self::TRANSIENT_CURRENT_COMPANY);
        if (isset($data->uuid)) {
            echo '<table class="form-table">';
            echo '<tr><th><label>' . __('Name') . ':</label></th><td>' . \esc_html($data->name) . '</td></tr>';
            echo '<tr><th><label>' . __('Public page') . ':</label></th><td><a href="' . \esc_html($data->public_url) . '" class="button" target="_blank" rel="noopener">' . __('View public page', 'reviewpack') . ' </a></td></tr>';
            echo '</table>';
        } else {
            echo '<div class="reviewpack-alert reviewpack-alert-error">' . __('Could not fetch company details, please try again!', 'reviewpack') . '</div>';
        }
    }

    /**
     * Render the settings text for the company section
     */
    public function reviewpack_section_text_integrations()
    {
        echo '<p>' . __('Select the integrations with which you want to automatically send invitations. This invitation will only be processed after the order or reservation has been completed. We automatically send an email to the customer to leave a review behind.', 'reviewpack') . '</p>';
    }

    /**
     * Render the API key block
     */
    public function reviewpack_setting_api_key()
    {
        echo "<input id='reviewpack_setting_api_key' name='reviewpack_plugin_options[" . self::SETTING_API_TOKEN . "]' type='text' value='" . esc_attr($this->getOption(self::SETTING_API_TOKEN)) . "' class='regular-text' />";
    }

    /**
     * Render the API secret block
     */
    public function reviewpack_setting_api_secret()
    {
        echo "<input id='reviewpack_setting_api_key' name='reviewpack_plugin_options[" . self::SETTING_API_SECRET . "]' type='password' value='" . esc_attr($this->getOption(self::SETTING_API_SECRET)) . "' class='regular-text' />";
    }

    /**
     * Render the API secret block
     */
    public function reviewpack_setting_integrations()
    {
        $totalAvailable = 0;
        $activated = $this->getOption(self::SETTING_INTEGRATIONS);

        if (\in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            // Put your plugin code here
            $wooKey = '';
            if (\is_array($activated) && \in_array('woocommerce', $activated)) {
                $wooKey = 'woocommerce';
            }

            echo '<label>';
            echo "<input name='reviewpack_plugin_options[" . self::SETTING_INTEGRATIONS . "][woocommerce]' type='checkbox' value='woocommerce' class='regular-text' " . checked('woocommerce', $wooKey, false) . " /> WooCommerce";
            echo '</label><br />';

            $totalAvailable++;
        }

        if ($totalAvailable === 0) {
            echo "<i>" . __('No compatible integrations activated, please contact us with your wishes! Currently, only WooCommerce is supported.', 'reviewpack') . "</i>";
        }
    }

    public function reviewpack_api_error_notice()
    {
        echo "<div class='error notice'><p>There has been an error. Bummer</p></div>";
    }


    /**
     * @param $optionName
     * @return mixed|string
     */
    public function getOption($optionName)
    {
        $options = get_option('reviewpack_plugin_options');

        if (isset($options[$optionName]) && !empty($options[$optionName])) {
            if (is_array($options[$optionName])) {
                return $options[$optionName];
            }

            return \trim($options[$optionName]);
        }

        return self::OPTIONS_DEFAULT[$optionName];
    }

}
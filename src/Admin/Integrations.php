<?php

namespace ReviewPack\Admin;

use ReviewPack\Resources\Api;

/**
 * Class Integrations
 * @package ReviewPack\Admin
 */
class Integrations
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
     * Integrations constructor.
     * @param Settings $settings
     * @param Api $api
     */
    public function __construct(Settings $settings, Api $api)
    {
        $this->settings = $settings;
        $this->api = $api;
    }

    /**
     * Create an invite, based on a WooCommerce order
     *
     * @param $orderId
     * @throws \Exception
     */
    public function createWooCommerceInvite($orderId)
    {
        $token = $this->settings->getOption(Settings::SETTING_API_TOKEN);
        $secret = $this->settings->getOption(Settings::SETTING_API_SECRET);
        $company = $this->settings->getOption(Settings::SETTING_COMPANY_UUID);

        if (empty($token) || empty($secret) || empty($company)) {
            return;
        }

        global $woocommerce, $post;
        $order = new \WC_Order($orderId);

        if(!empty($order->get_meta('reviewpack_invite'))){
            // already invite planned, prevent an extra API call
            return;
        }

        $plannedDate = new \DateTimeImmutable();
        $plannedDate->modify('+5 days');

        try {
            $result = $this->api->createInvite(
                $token,
                $secret,
                $company,
                $order->get_billing_email(),
                $order->get_billing_first_name(),
                $order->get_billing_last_name(),
                $plannedDate,
                $order->get_currency(),
                ($order->get_total() * 100), // calculate float to cents
                $order->get_order_number(),
                $order->get_order_key(),
                $order->get_customer_id()
            );

            if ($result !== false) {
                // success
                $order->add_order_note(\sprintf(__('ReviewPack invite planned, sent on %s', 'reviewpack'), $plannedDate->format('d M Y H:i')));
                $order->update_meta_data('reviewpack_invite', $result);
                $order->save_meta_data();

                return;
            }
        } catch (\Exception $exception) {
            $order->add_order_note(\sprintf(__('ReviewPack error: %s', 'reviewpack'), $exception->getMessage()));
        }
    }

}
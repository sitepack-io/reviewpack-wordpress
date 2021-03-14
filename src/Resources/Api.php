<?php

namespace ReviewPack\Resources;

use ReviewPack\Admin\Settings;
use ReviewPack\Models\AbstractResource;
use ReviewPack\Models\Company;
use ReviewPack\Models\CompanyScore;
use ReviewPack\Models\CompanyValidated;
use ReviewPack\Models\Invite;
use ReviewPack\Models\InviteTemplate;
use ReviewPack\Models\Review;

/**
 * Class Api
 * @package ReviewPack\Resources
 */
class Api
{

    const REVIEWPACK_API_URL = 'https://api.reviewpack.eu/';
//    const REVIEWPACK_API_URL = 'http://127.0.0.1:8000/api';

    /**
     * @param string $token
     * @param string $secret
     * @return array
     * @throws \Exception
     */
    public function getCompanies(string $token, string $secret)
    {
        $data = $this->callApi('GET', self::REVIEWPACK_API_URL . '/companies', $token, $secret);

        return $this->mapDataToObjectsCollection($data->companies, Company::class);
    }

    /**
     * @param string $token
     * @param string $secret
     * @return mixed
     * @throws \Exception
     */
    public function validateCompany(string $token, string $secret)
    {
        $data = $this->callApi('POST', self::REVIEWPACK_API_URL . '/company/verify-tokens', $token, $secret);

        $object = $this->mapDataToObject($data, CompanyValidated::class);
        $object->uuid = $data->company->uuid;
        $object->name = $data->company->name;
        $object->public_url = $data->company->public_url;
        $object->sales_channel = $data->api->sales_channel;

        set_transient(Settings::TRANSIENT_CURRENT_COMPANY, $object);

        return $object;
    }

    /**
     * @param string $token
     * @param string $secret
     * @param string $company
     * @return mixed
     * @throws \Exception
     */
    public function getCompanyDetails(string $token, string $secret, string $company)
    {
        $data = $this->callApi('POST', self::REVIEWPACK_API_URL . '/company', $token, $secret, [
            'company' => $company,
        ]);

        return $this->mapDataToObject($data->company, Company::class);
    }

    /**
     * @param string $token
     * @param string $secret
     * @param string $company
     * @return mixed
     * @throws \Exception
     */
    public function getCompanyScore(string $token, string $secret, string $company)
    {
        $data = $this->callApi('POST', self::REVIEWPACK_API_URL . '/reviews/score', $token, $secret, [
            'company' => $company,
        ]);

        return $this->mapDataToObject($data, CompanyScore::class);
    }

    /**
     * Get the invite template, used to sent the automated mails to customers.
     *
     * @param string $token
     * @param string $secret
     * @param string $company
     * @param int $type
     * @return InviteTemplate
     * @throws \Exception
     */
    public function getInviteTemplate(string $token, string $secret, string $company, int $type)
    {
        $data = $this->callApi('GET', self::REVIEWPACK_API_URL . '/invites/templates?company=' . $company . '&type=' . $type, $token, $secret);

        /** @var InviteTemplate $object */
        $object = $this->mapDataToObject($data, InviteTemplate::class);
        $object->first_row = $data->template->first_row;
        $object->second_row = $data->template->second_row;
        $object->button_label = $data->template->button_label;
        $object->subject = $data->template->subject;

        return $object;
    }

    /**
     * @param string $token
     * @param string $secret
     * @param string $company
     * @return Review[]
     * @throws \Exception
     */
    public function getRecentReviews(string $token, string $secret, string $company)
    {
        if (!empty(get_transient(Settings::TRANSIENT_NEWEST_REVIEWS))) {
            return get_transient(Settings::TRANSIENT_NEWEST_REVIEWS);
        }

        $data = $this->callApi('POST', self::REVIEWPACK_API_URL . '/reviews/newest', $token, $secret, [
            'company' => $company,
        ]);

        $newest = $this->mapDataToObjectsCollection($data->reviews, Review::class);

        set_transient(
            Settings::TRANSIENT_NEWEST_REVIEWS,
            $newest,
            (60 * 10)
        );

        return get_transient(Settings::TRANSIENT_NEWEST_REVIEWS);
    }

    /**
     * @param string $token
     * @param string $secret
     * @param string $company
     * @return mixed
     * @throws \Exception
     */
    public function getRecentInvites(string $token, string $secret, string $company)
    {
        if (!empty(get_transient(Settings::TRANSIENT_NEWEST_INVITES))) {
            return get_transient(Settings::TRANSIENT_NEWEST_INVITES);
        }

        $data = $this->callApi('GET', self::REVIEWPACK_API_URL . '/invites/newest?company=' . $company, $token, $secret);
        $newest = $this->mapDataToObjectsCollection($data->invites, Invite::class);

        set_transient(
            Settings::TRANSIENT_NEWEST_INVITES,
            $newest,
            (60 * 10)
        );

        return get_transient(Settings::TRANSIENT_NEWEST_INVITES);
    }

    /**
     * Create/plan an invite in the API
     *
     * @param $token
     * @param $secret
     * @param $company
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param \DateTimeImmutable $date
     * @param $referenceOne
     * @param $referenceTwo
     * @param $referenceThree
     * @throws \Exception
     */
    public function createInvite($token, $secret, $company, $email, $firstName, $lastName, \DateTimeImmutable $date, $currency, $totalOrderAmountCents, $referenceOne, $referenceTwo, $referenceThree)
    {
        $postFields = [
            'company' => $company,
            'email' => \trim(\strtolower($email)),
            'first_name' => \trim(\ucfirst($firstName)),
            'date' => $date->format('Y-m-d H:i:s'),
        ];

        if (!empty($lastName)) {
            $postFields['last_name'] = $lastName;
        }
        if (!empty($referenceOne)) {
            $postFields['reference_one'] = $referenceOne;
        }
        if (!empty($referenceTwo)) {
            $postFields['reference_two'] = $referenceTwo;
        }
        if (!empty($referenceThree)) {
            $postFields['reference_three'] = $referenceThree;
        }
        if (!empty($currency)) {
            $postFields['currency'] = $currency;
        }
        if (!empty($totalOrderAmountCents)) {
            $postFields['total_order_cents'] = $totalOrderAmountCents;
        }

        $data = $this->callApi('POST', self::REVIEWPACK_API_URL . '/invites/add', $token, $secret, $postFields);

        if (isset($data->uuid)) {
            return $data->uuid;
        }

        return false;
    }

    /**
     * @param $type
     * @param $url
     * @param $token
     * @param $secret
     * @param array $postData
     * @return mixed
     * @throws \Exception
     */
    private function callApi($type, $url, $token, $secret, $postData = [])
    {
        $args = [
            'method' => $type,
            'headers' => [
                'Authorization' => 'Basic ' . \base64_encode($token . ':' . $secret)
            ],
        ];

        if (\count($postData) >= 1) {
            $args['body'] = \json_encode($postData);
        }

        $result = wp_remote_request(
            $url,
            $args
        );

        if (!isset($result['body'])) {
            throw new \Exception(__('Wrong API response, please try again.', 'reviewpack'));
        }

        $data = \json_decode($result['body']);
        if ($data->status === 'error') {
            throw new \Exception(__('Wrong API response: ', 'reviewpack') . $data->message);
        }

        return $data;
    }

    /**
     * @param array $results
     * @param string $class
     * @return AbstractResource[]
     */
    private function mapDataToObjectsCollection(array $results, string $class)
    {
        $collection = [];
        foreach ($results as $result) {
            $collection[] = $this->mapDataToObject($result, $class);
        }

        return $collection;
    }

    /**
     * @param object $result
     * @param string $class
     * @return mixed
     */
    protected function mapDataToObject($result, string $class)
    {
        $object = new $class;
        foreach ($result as $property => $value) {
            if (\property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
    }


}
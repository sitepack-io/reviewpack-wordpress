<?php

namespace ReviewPack\Models;

/**
 * Class Company
 * @package ReviewPack\Models
 */
class Company extends AbstractResource
{

    public $uuid;
    public $name;
    public $address;
    public $postal_code;
    public $city;
    public $country_code;
    public $website;
    public $invite_sent;
    public $invite_reset_date;
    public $subscription_name;
    public $subscription_limit;

}
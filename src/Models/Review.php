<?php

namespace ReviewPack\Models;

/**
 * Class Review
 * @package ReviewPack\Models
 */
class Review extends AbstractResource
{

    public $uuid;
    public $name;
    public $title;
    public $stars_total;
    public $manage_url;
    public $created;

}
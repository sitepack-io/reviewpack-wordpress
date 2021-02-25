<?php

namespace ReviewPack\Models;

/**
 * Class CompanyScore
 * @package ReviewPack\Models
 */
class CompanyScore extends AbstractResource
{

    public $uuid;
    public $slug;
    public $total_reviews;
    public $avg_score;
    public $start_date;
    public $public_url;

}
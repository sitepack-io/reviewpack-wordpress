<?php

namespace ReviewPack\Frontend;

use ReviewPack\Admin\Settings;
use ReviewPack\Models\CompanyScore;
use ReviewPack\Resources\Api;

/**
 * Class Shortcodes
 * @package ReviewPack\Frontend
 */
class Shortcodes
{

    /**
     * @var Api
     */
    private $api;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * Shortcodes constructor.
     * @param Api $api
     * @param Settings $settings
     */
    public function __construct(Api $api, Settings $settings)
    {
        $this->api = $api;
        $this->settings = $settings;

        add_shortcode('reviewpack', [$this, 'renderReviewpackShortcode']);
    }

    /**
     * Render the reviewpack shortcode
     *
     * @param $args
     */
    public function renderReviewpackShortcode($args)
    {
        if (!isset($args['type'])) {
            // set default value
            $args['type'] = 'slim_score';
        }

        $args['type'] = sanitize_key($args['type']);
        switch ($args['type']) {
            case 'slim_score':
                return $this->renderSlimScore();
            case 'score':
                return $this->renderScore();
            case 'score_title':
                return $this->renderScoreTitle();
            case 'carrousel':
                return $this->renderCarrousel();
        }

        $this->renderError(__('Please define a valid widget type', 'reviewpack'));
    }

    /**
     * @param $message
     */
    private function renderError($message)
    {
        echo "<span>" . $message . "</span>";
    }

    /**
     * Render the slim score widget
     */
    private function renderSlimScore()
    {
        $score = $this->getCompanyScore();

        $html = '<div class="reviewpack-box" data-company-uuid="' . esc_attr($this->settings->getOption(Settings::SETTING_COMPANY_UUID)) . '" data-company-slug="' . $score->slug . '" data-widget-type="slim_score" data-theme="light" data-locale="' . esc_attr(get_locale()) . '">';
        $html .= '<a href="' . esc_url($score->public_url) . '" target="_blank" rel="noopener">ReviewPack</a>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render the score
     */
    private function renderScore()
    {
        $score = $this->getCompanyScore();

        $html = '<div class="reviewpack-box" data-company-uuid="' . esc_attr($this->settings->getOption(Settings::SETTING_COMPANY_UUID)) . '" data-company-slug="' . $score->slug . '" data-widget-type="score" data-theme="light" data-locale="' . esc_attr(get_locale()) . '">';
        $html .= '<a href="' . esc_url($score->public_url) . '" target="_blank" rel="noopener">ReviewPack</a>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render the score with a title
     */
    private function renderScoreTitle()
    {
        $score = $this->getCompanyScore();

        $html = '<div class="reviewpack-box" data-company-uuid="' . esc_attr($this->settings->getOption(Settings::SETTING_COMPANY_UUID)) . '" data-company-slug="' . $score->slug . '" data-widget-type="score_title" data-theme="light" data-locale="' . esc_attr(get_locale()) . '">';
        $html .= '<a href="' . esc_url($score->public_url) . '" target="_blank" rel="noopener">ReviewPack</a>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render the score carrousel
     */
    private function renderCarrousel()
    {
        $score = $this->getCompanyScore();

        $html = '<div class="reviewpack-box" data-company-uuid="' . esc_attr($this->settings->getOption(Settings::SETTING_COMPANY_UUID)) . '" data-company-slug="' . $score->slug . '" data-widget-type="carrousel" data-theme="light" data-locale="' . esc_attr(get_locale()) . '">';
        $html .= '<a href="' . esc_url($score->public_url) . '" target="_blank" rel="noopener">ReviewPack</a>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Get the company score on a safe way
     *
     * @return CompanyScore
     */
    private function getCompanyScore()
    {
        $cached = get_transient('reviewpack_score_cache');

        if ($cached instanceof CompanyScore) {
            return $cached;
        }

        try {
            $score = $this->api->getCompanyScore(
                $this->settings->getOption(Settings::SETTING_API_TOKEN),
                $this->settings->getOption(Settings::SETTING_API_SECRET),
                $this->settings->getOption(Settings::SETTING_COMPANY_UUID)
            );
        } catch (\Exception $exception) {
            $score = new CompanyScore();
            $score->avg_score = 0;
            $score->total_reviews = 0;
        }

        set_transient('reviewpack_score_cache', $score, (10 * 60));

        return $score;
    }


}
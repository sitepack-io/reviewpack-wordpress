<div class="reviewpack-container">
    <div class="reviewpack-heading">
        <a href="https://reviewpack.eu" target="_blank">
            <img src="<?= plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/icon_64.png" alt="ReviewPack icon"
                 class="reviewpack-icon" align="left"/>
        </a>
        <h1><?= __('ReviewPack dashboard', 'reviewpack') ?></h1>

        <a href="https://reviewpack.eu/login?utm_source=wordpress&utm_medium=button_right_top&utm_campaign=wp_plugin"
           target="_blank" rel="noopener"
           class="reviewpack-pull-right reviewpack-admin-login button">
            <?= __('Open ReviewPack', 'reviewpack') ?>
            <span class="dashicons dashicons-external"></span>
        </a>
    </div>
    <div class="reviewpack-menu">
        <ul>
            <li><a href="<?= admin_url('admin.php?page=reviewpack') ?>"
                   class="active"><?= __('Dashboard', 'reviewpack') ?></a></li>
            <li>
                <a href="<?= admin_url('options-general.php?page=reviewpack-settings') ?>"><?= __('Settings', 'reviewpack') ?></a>
            </li>
            <li>
                <a href="<?= admin_url('admin.php?page=reviewpack-invites') ?>"><?= __('Invite mail', 'reviewpack') ?></a>
            </li>
        </ul>

        <?php if ($isConnected === false): ?>
            <a href="<?= admin_url('options-general.php?page=reviewpack-settings') ?>"
               class="reviewpack-status reviewpack-status-not-connected">
                <?= __('Not connected', 'reviewpack') ?>
            </a>
        <?php else: ?>
            <a href="<?= admin_url('options-general.php?page=reviewpack-settings') ?>"
               class="reviewpack-status reviewpack-status-connected">
                <?= __('Connected', 'reviewpack') ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="reviewpack-body">
        <div class="wrap">
            <?php if ($isConnected === false): ?>
                <?php
                include('not_connected_body.php');
                ?>
            <?php else: ?>
                <div class="reviewpack-dashboard">
                    <div class="reviewpack-block">
                        <h3>
                            <?= __('About your company', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?= __('Edit details', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <p>
                            <strong><?= esc_attr($companyDetails->name) ?></strong><br/>
                            <?= esc_attr($companyDetails->address) ?><br/>
                            <?= esc_attr($companyDetails->postal_code) ?> <?= esc_attr($companyDetails->city) ?><br/>
                        </p>

                        <hr>

                        <h3><?= __('Company rating', 'reviewpack') ?></h3>

                        <?php if ($companyScore->total_reviews === 0): ?>
                            <p><i><?= __('No review data yet, please invite more customers.', 'reviewpack') ?></i></p>
                        <?php else: ?>
                            <p>
                        <span class="reviewpack-stars">
                        <?php if ($companyScore->avg_score >= 10): ?>
                            <span class="dashicons dashicons-star-filled"></span>
                        <?php elseif ($companyScore->avg_score >= 5): ?>
                            <span class="dashicons dashicons-star-half"></span>
                        <?php else: ?>
                            <span class="dashicons dashicons-star-empty"></span>
                        <?php endif; ?>
                            <?php if ($companyScore->avg_score >= 20): ?>
                                <span class="dashicons dashicons-star-filled"></span>
                            <?php elseif ($companyScore->avg_score >= 15): ?>
                                <span class="dashicons dashicons-star-half"></span>
                            <?php else: ?>
                                <span class="dashicons dashicons-star-empty"></span>
                            <?php endif; ?>
                            <?php if ($companyScore->avg_score >= 30): ?>
                                <span class="dashicons dashicons-star-filled"></span>
                            <?php elseif ($companyScore->avg_score >= 25): ?>
                                <span class="dashicons dashicons-star-half"></span>
                            <?php else: ?>
                                <span class="dashicons dashicons-star-empty"></span>
                            <?php endif; ?>
                            <?php if ($companyScore->avg_score >= 40): ?>
                                <span class="dashicons dashicons-star-filled"></span>
                            <?php elseif ($companyScore->avg_score >= 35): ?>
                                <span class="dashicons dashicons-star-half"></span>
                            <?php else: ?>
                                <span class="dashicons dashicons-star-empty"></span>
                            <?php endif; ?>
                            <?php if ($companyScore->avg_score >= 50): ?>
                                <span class="dashicons dashicons-star-filled"></span>
                            <?php elseif ($companyScore->avg_score >= 45): ?>
                                <span class="dashicons dashicons-star-half"></span>
                            <?php else: ?>
                                <span class="dashicons dashicons-star-empty"></span>
                            <?php endif; ?>
                        </span>
                            </p>
                            <p>
                                <?= sprintf(__('Based on %d reviews', 'reviewpack'), $companyScore->total_reviews) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="reviewpack-block">
                        <h3>
                            <?= __('Your subscription', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?= __('Upgrade plan', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <p>
                            <strong><?= __('Subscription', 'reviewpack') ?>:</strong><br/>
                            <?= esc_attr($companyDetails->subscription_name) ?><br/>
                        </p>

                        <p>
                            <strong><?= __('Invites', 'reviewpack') ?>:</strong><br/>
                            <?= number_format_i18n($companyDetails->invite_sent, 0) ?>
                            / <?= number_format_i18n($companyDetails->subscription_limit, 0) ?><br/>
                        </p>

                        <p>
                            <strong><?= __('Invite reset date', 'reviewpack') ?>:</strong><br/>
                            <?= \date(get_option('date_format'), \strtotime($companyDetails->invite_reset_date)) ?><br/>
                        </p>
                    </div>
                </div>

                <div class="reviewpack-dashboard">
                    <div class="reviewpack-block">
                        <h3>
                            <?= __('Recent reviews', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?= __('Manage reviews', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <table width="100%">
                            <thead>
                            <th width="140"><?= __('Rating', 'reviewpack') ?></th>
                            <th><?= __('Customer', 'reviewpack') ?></th>
                            <th width="120"><?= __('Date', 'reviewpack') ?></th>
                            </thead>
                            <tbody>
                            <?php foreach ($recentReviews as $review): ?>
                                <tr>
                                    <td>
                                        <p>
                                            <span class="reviewpack-stars">
                                            <?php if ($review->stars_total >= 10): ?>
                                                <span class="dashicons dashicons-star-filled"></span>
                                            <?php elseif ($review->stars_total >= 5): ?>
                                                <span class="dashicons dashicons-star-half"></span>
                                            <?php else: ?>
                                                <span class="dashicons dashicons-star-empty"></span>
                                            <?php endif; ?>
                                                <?php if ($review->stars_total >= 20): ?>
                                                    <span class="dashicons dashicons-star-filled"></span>
                                                <?php elseif ($review->stars_total >= 15): ?>
                                                    <span class="dashicons dashicons-star-half"></span>
                                                <?php else: ?>
                                                    <span class="dashicons dashicons-star-empty"></span>
                                                <?php endif; ?>
                                                <?php if ($review->stars_total >= 30): ?>
                                                    <span class="dashicons dashicons-star-filled"></span>
                                                <?php elseif ($review->stars_total >= 25): ?>
                                                    <span class="dashicons dashicons-star-half"></span>
                                                <?php else: ?>
                                                    <span class="dashicons dashicons-star-empty"></span>
                                                <?php endif; ?>
                                                <?php if ($review->stars_total >= 40): ?>
                                                    <span class="dashicons dashicons-star-filled"></span>
                                                <?php elseif ($review->stars_total >= 35): ?>
                                                    <span class="dashicons dashicons-star-half"></span>
                                                <?php else: ?>
                                                    <span class="dashicons dashicons-star-empty"></span>
                                                <?php endif; ?>
                                                <?php if ($review->stars_total >= 50): ?>
                                                    <span class="dashicons dashicons-star-filled"></span>
                                                <?php elseif ($review->stars_total >= 45): ?>
                                                    <span class="dashicons dashicons-star-half"></span>
                                                <?php else: ?>
                                                    <span class="dashicons dashicons-star-empty"></span>
                                                <?php endif; ?>
                                            </span>
                                        </p>
                                    </td>
                                    <td><?php if(empty($review->title)){
                                        echo __('No title', 'reviewpack');
                                    }else{
                                        echo esc_attr($review->title);
                                    } ?></td>
                                    <td><?= \date(get_option('date_format'), \strtotime($review->created)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="reviewpack-block">
                        <h3>
                            <?= __('Recent invites', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?= __('Manage invites', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <table width="100%">
                            <thead>
                            <th><?= __('Customer', 'reviewpack') ?></th>
                            <th width="120"><?= __('Created', 'reviewpack') ?></th>
                            <th width="120"><?= __('Planned', 'reviewpack') ?></th>
                            </thead>
                            <tbody>
                            <?php foreach ($recentInvites as $invite): ?>
                                <tr>
                                    <td><?= \esc_attr($invite->first_name) ?> <?= \esc_attr($invite->last_name) ?></td>
                                    <td><?= \date(get_option('date_format'), \strtotime($invite->created)) ?></td>
                                    <td><?= \date(get_option('date_format'), \strtotime($invite->planned)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
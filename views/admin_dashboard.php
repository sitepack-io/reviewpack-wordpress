<div class="reviewpack-container">
    <div class="reviewpack-heading">
        <a href="https://reviewpack.eu" target="_blank">
            <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/icon_64.png" alt="ReviewPack icon"
                 class="reviewpack-icon" align="left"/>
        </a>
        <h1><?php echo __('ReviewPack dashboard', 'reviewpack') ?></h1>

        <a href="https://reviewpack.eu/login?utm_source=wordpress&utm_medium=button_right_top&utm_campaign=wp_plugin"
           target="_blank" rel="noopener"
           class="reviewpack-pull-right reviewpack-admin-login button">
            <?php echo __('Open ReviewPack', 'reviewpack') ?>
            <span class="dashicons dashicons-external"></span>
        </a>
    </div>
    <div class="reviewpack-menu">
        <ul>
            <li><a href="<?php echo admin_url('admin.php?page=reviewpack') ?>"
                   class="active"><?php echo __('Dashboard', 'reviewpack') ?></a></li>
            <li>
                <a href="<?php echo admin_url('options-general.php?page=reviewpack-settings') ?>"><?php echo __('Settings', 'reviewpack') ?></a>
            </li>
            <li>
                <a href="<?php echo admin_url('admin.php?page=reviewpack-invites') ?>"><?php echo __('Invite mail', 'reviewpack') ?></a>
            </li>
            <li>
                <a href="<?php echo admin_url('admin.php?page=reviewpack-widgets') ?>"><?php echo __('Widgets', 'reviewpack') ?></a>
            </li>
        </ul>

        <?php if ($isConnected === false): ?>
            <a href="<?php echo admin_url('options-general.php?page=reviewpack-settings') ?>"
               class="reviewpack-status reviewpack-status-not-connected">
                <?php echo __('Not connected', 'reviewpack') ?>
            </a>
        <?php else: ?>
            <a href="<?php echo admin_url('options-general.php?page=reviewpack-settings') ?>"
               class="reviewpack-status reviewpack-status-connected">
                <?php echo __('Connected', 'reviewpack') ?>
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
                            <?php echo __('About your company', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?php echo __('Edit details', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <p>
                            <strong><?php echo esc_attr($companyDetails->name) ?></strong><br/>
                            <?php echo esc_attr($companyDetails->address) ?><br/>
                            <?php echo esc_attr($companyDetails->postal_code) ?> <?php echo esc_attr($companyDetails->city) ?><br/>
                        </p>

                        <hr>

                        <h3><?php echo __('Company rating', 'reviewpack') ?></h3>

                        <?php if ($companyScore->total_reviews === 0): ?>
                            <p><i><?php echo __('No review data yet, please invite more customers.', 'reviewpack') ?></i></p>
                        <?php else: ?>
                            <p>
                        <span class="reviewpack-stars">
                        <?php
                        $rating = $companyScore->avg_score;
                        include(REVIEWPACK_PLUGIN_DIR . '/views/elements/rating.php');
                        ?>
                        </span>
                            </p>
                            <p>
                                <?php echo sprintf(__('Based on %d reviews', 'reviewpack'), $companyScore->total_reviews) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="reviewpack-block">
                        <h3>
                            <?php echo __('Your subscription', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?php echo __('Upgrade plan', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <p>
                            <strong><?php echo __('Subscription', 'reviewpack') ?>:</strong><br/>
                            <?php echo esc_attr($companyDetails->subscription_name) ?><br/>
                        </p>

                        <p>
                            <strong><?php echo __('Invites', 'reviewpack') ?>:</strong><br/>
                            <?php echo number_format_i18n($companyDetails->invite_sent, 0) ?>
                            / <?php echo number_format_i18n($companyDetails->subscription_limit, 0) ?><br/>
                        </p>

                        <p>
                            <strong><?php echo __('Invite reset date', 'reviewpack') ?>:</strong><br/>
                            <?php echo \date(get_option('date_format'), \strtotime($companyDetails->invite_reset_date)) ?><br/>
                        </p>
                    </div>
                </div>

                <div class="reviewpack-dashboard">
                    <div class="reviewpack-block">
                        <h3>
                            <?php echo __('Recent reviews', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?php echo __('Manage reviews', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <table width="100%">
                            <thead>
                            <th width="140"><?php echo __('Rating', 'reviewpack') ?></th>
                            <th><?php echo __('Title', 'reviewpack') ?></th>
                            <th width="120"><?php echo __('Date', 'reviewpack') ?></th>
                            </thead>
                            <tbody>
                            <?php foreach ($recentReviews as $review): ?>
                                <tr>
                                    <td>
                                            <span class="reviewpack-stars">
                                                <?php
                                                $rating = $review->stars_total;
                                                include(REVIEWPACK_PLUGIN_DIR . '/views/elements/rating.php');
                                                ?>
                                            </span>
                                    </td>
                                    <td><?php if(empty($review->title)){
                                        echo __('No title', 'reviewpack');
                                    }else{
                                        echo esc_attr($review->title);
                                    } ?></td>
                                    <td><?php echo \date(get_option('date_format'), \strtotime($review->created)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="reviewpack-block">
                        <h3>
                            <?php echo __('Recent invites', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener"
                               class="button reviewpack-button-right">
                                <?php echo __('Manage invites', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <table width="100%">
                            <thead>
                            <th><?php echo __('Customer', 'reviewpack') ?></th>
                            <th width="120"><?php echo __('Created', 'reviewpack') ?></th>
                            <th width="120"><?php echo __('Planned', 'reviewpack') ?></th>
                            </thead>
                            <tbody>
                            <?php foreach ($recentInvites as $invite): ?>
                                <tr>
                                    <td><?php echo \esc_attr($invite->first_name) ?> <?php echo \esc_attr($invite->last_name) ?></td>
                                    <td><?php echo \date(get_option('date_format'), \strtotime($invite->created)) ?></td>
                                    <td><?php echo \date(get_option('date_format'), \strtotime($invite->planned)) ?></td>
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
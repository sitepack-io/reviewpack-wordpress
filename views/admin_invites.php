<div class="reviewpack-container">
    <div class="reviewpack-heading">
        <a href="https://reviewpack.eu" target="_blank">
            <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/icon_64.png" alt="ReviewPack icon"
                 class="reviewpack-icon" align="left"/>
        </a>
        <h1><?php echo __('ReviewPack settings', 'reviewpack') ?></h1>

        <a href="https://reviewpack.eu/login?utm_source=wordpress&utm_medium=button_right_top&utm_campaign=wp_plugin"
           target="_blank" rel="noopener"
           class="reviewpack-pull-right reviewpack-admin-login button">
            <?php echo __('Open ReviewPack', 'reviewpack') ?>
            <span class="dashicons dashicons-external"></span>
        </a>
    </div>
    <div class="reviewpack-menu">
        <ul>
            <li><a href="<?php echo admin_url('admin.php?page=reviewpack') ?>"><?php echo __('Dashboard', 'reviewpack') ?></a></li>
            <li>
                <a href="<?php echo admin_url('options-general.php?page=reviewpack-settings') ?>"><?php echo __('Settings', 'reviewpack') ?></a>
            </li>
            <li><a href="<?php echo admin_url('admin.php?page=reviewpack-invites') ?>"
                   class="active"><?php echo __('Invite mail', 'reviewpack') ?></a></li>
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
                            <?php echo __('First invitation mail to customer', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener" class="button reviewpack-button-right">
                                <?php echo __('Edit mail', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <p class="reviewpack-lead">
                            <strong><?php echo __('Subject', 'reviewpack') ?>:</strong><br/>
                            <?php echo \esc_html($inviteTemplate->subject) ?>
                        </p>

                        <hr>

                        <div class="reviewpack-mail-preview">
                            <h3><?php echo __('Hi [name],', 'reviewpack') ?></h3>

                            <p><?php echo \esc_html($inviteTemplate->first_row) ?></p>
                            <p><?php echo \esc_html($inviteTemplate->second_row) ?></p>

                            <p align="center">
                                <strong><?php echo __('Click on the desired number of stars:', 'reviewpack') ?></strong><br/>
                                <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/stars.png"
                                     alt="stars"/>
                            </p>

                            <p>
                                <a href="#" class="reviewpack-cta-button">
                                    <?php echo \esc_html($inviteTemplate->button_label) ?>
                                </a>
                            </p>

                            <p><?php echo __('Sincerely,', 'reviewpack') ?><br/>
                                Team <?php echo \esc_html($company->name) ?></p>

                            <p align="center" class="reviewpack-mail-powered">
                                <?php echo __('Powered by:') ?><br />
                                <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/logo_full_40.png" />
                            </p>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
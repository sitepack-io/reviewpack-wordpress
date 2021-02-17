<div class="reviewpack-container">
    <div class="reviewpack-heading">
        <a href="https://reviewpack.eu" target="_blank">
            <img src="<?= plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/icon_64.png" alt="ReviewPack icon"
                 class="reviewpack-icon" align="left"/>
        </a>
        <h1><?= __('ReviewPack settings', 'reviewpack') ?></h1>

        <a href="https://reviewpack.eu/login?utm_source=wordpress&utm_medium=button_right_top&utm_campaign=wp_plugin"
           target="_blank" rel="noopener"
           class="reviewpack-pull-right reviewpack-admin-login button">
            <?= __('Open ReviewPack', 'reviewpack') ?>
            <span class="dashicons dashicons-external"></span>
        </a>
    </div>
    <div class="reviewpack-menu">
        <ul>
            <li><a href="<?= admin_url('admin.php?page=reviewpack') ?>"><?= __('Dashboard', 'reviewpack') ?></a></li>
            <li>
                <a href="<?= admin_url('options-general.php?page=reviewpack-settings') ?>"><?= __('Settings', 'reviewpack') ?></a>
            </li>
            <li><a href="<?= admin_url('admin.php?page=reviewpack-invites') ?>"
                   class="active"><?= __('Invite mail', 'reviewpack') ?></a></li>
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
                            <?= __('First invitation mail to customer', 'reviewpack') ?>
                            <a href="https://reviewpack.eu/portal" target="_blank" rel="noopener" class="button reviewpack-button-right">
                                <?= __('Edit mail', 'reviewpack') ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </h3>

                        <p class="reviewpack-lead">
                            <strong><?= __('Subject', 'reviewpack') ?>:</strong><br/>
                            <?= \esc_html($inviteTemplate->subject) ?>
                        </p>

                        <hr>

                        <div class="reviewpack-mail-preview">
                            <h3><?= __('Hi [name],', 'reviewpack') ?></h3>

                            <p><?= \esc_html($inviteTemplate->first_row) ?></p>
                            <p><?= \esc_html($inviteTemplate->second_row) ?></p>

                            <p align="center">
                                <strong><?= __('Click on the desired number of stars:', 'reviewpack') ?></strong><br/>
                                <img src="<?= plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/stars.png"
                                     alt="stars"/>
                            </p>

                            <p>
                                <a href="#" class="reviewpack-cta-button">
                                    <?= \esc_html($inviteTemplate->button_label) ?>
                                </a>
                            </p>

                            <p><?= __('Sincerely,', 'reviewpack') ?><br/>
                                Team <?= \esc_html($company->name) ?></p>

                            <p align="center" class="reviewpack-mail-powered">
                                <?= __('Powered by:') ?><br />
                                <img src="<?= plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/logo_full_40.png" />
                            </p>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
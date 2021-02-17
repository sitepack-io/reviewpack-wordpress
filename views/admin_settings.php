<div class="reviewpack-container">
    <div class="reviewpack-heading">
        <a href="https://reviewpack.eu" target="_blank">
        <img src="<?= plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/icon_64.png" alt="ReviewPack icon"
             class="reviewpack-icon" align="left"/>
        </a>
        <h1><?= __('ReviewPack settings', 'reviewpack') ?></h1>

        <a href="https://reviewpack.eu/login?utm_source=wordpress&utm_medium=button_right_top&utm_campaign=wp_plugin" target="_blank" rel="noopener"
           class="reviewpack-pull-right reviewpack-admin-login button">
            <?= __('Open ReviewPack', 'reviewpack') ?>
            <span class="dashicons dashicons-external"></span>
        </a>
    </div>
    <div class="reviewpack-menu">
        <ul>
            <li><a href="<?= admin_url( 'admin.php?page=reviewpack') ?>"><?= __('Dashboard', 'reviewpack') ?></a></li>
            <li><a href="<?= admin_url( 'options-general.php?page=reviewpack-settings') ?>" class="active"><?= __('Settings', 'reviewpack') ?></a></li>
            <li><a href="<?= admin_url( 'admin.php?page=reviewpack-invites') ?>"><?= __('Invite mail', 'reviewpack') ?></a></li>
        </ul>

        <?php if($isConnected === false): ?>
        <a href="<?= admin_url( 'options-general.php?page=reviewpack-settings') ?>" class="reviewpack-status reviewpack-status-not-connected">
            <?= __('Not connected', 'reviewpack') ?>
        </a>
        <?php else: ?>
        <a href="<?= admin_url( 'options-general.php?page=reviewpack-settings') ?>" class="reviewpack-status reviewpack-status-connected">
            <?= __('Connected', 'reviewpack') ?>
        </a>
        <?php endif; ?>
    </div>

    <div class="reviewpack-body">
        <div class="wrap">
            <form action="options.php" method="post">
                <?php
                settings_fields('reviewpack_plugin_options');
                do_settings_sections('reviewpack_settings'); ?>
                <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>"/>
            </form>
        </div>
    </div>
</div>
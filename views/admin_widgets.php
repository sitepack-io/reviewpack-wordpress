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
            <li><a href="<?php echo admin_url('admin.php?page=reviewpack-invites') ?>"><?php echo __('Invite mail', 'reviewpack') ?></a></li>
            <li>
                <a href="<?php echo admin_url('admin.php?page=reviewpack-widgets') ?>"
                   class="active"><?php echo __('Widgets', 'reviewpack') ?></a>
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
                            <?php echo __('Small review counter', 'reviewpack') ?>
                        </h3>

                        <div class="reviewpack-widget-preview">
                            <?php
                            echo do_shortcode('[reviewpack type="slim_score"]');
                            ?>
                        </div>
                        <hr>
                        <p class="reviewpack-shortcode-line">
                            <strong><?php echo __('Use this shortcode:', 'reviewpack'); ?></strong><br />
                            <code>[reviewpack type="slim_score"]</code>
                        </p>
                    </div>
                    <div class="reviewpack-block">
                        <h3>
                            <?php echo __('Score widget', 'reviewpack') ?>
                        </h3>

                        <div class="reviewpack-widget-preview">
                            <?php
                            echo do_shortcode('[reviewpack type="score"]');
                            ?>
                        </div>
                        <hr>
                        <p class="reviewpack-shortcode-line">
                            <strong><?php echo __('Use this shortcode:', 'reviewpack'); ?></strong><br />
                            <code>[reviewpack type="score"]</code>
                        </p>
                    </div>
                    <div class="reviewpack-block">
                        <h3>
                            <?php echo __('Score widget with title', 'reviewpack') ?>
                        </h3>

                        <div class="reviewpack-widget-preview">
                            <?php
                            echo do_shortcode('[reviewpack type="score_title"]');
                            ?>
                        </div>
                        <hr>
                        <p class="reviewpack-shortcode-line">
                            <strong><?php echo __('Use this shortcode:', 'reviewpack'); ?></strong><br />
                            <code>[reviewpack type="score_title"]</code>
                        </p>
                    </div>
                </div>

            <div class="reviewpack-dashboard">
                <div class="reviewpack-block reviewpack-block-full">
                    <h3>
                        <?php echo __('Big review Carrousel', 'reviewpack') ?>
                    </h3>

                    <div class="reviewpack-widget-preview">
                        <?php
                        echo do_shortcode('[reviewpack type="carrousel"]');
                        ?>
                    </div>
                    <hr>
                    <p class="reviewpack-shortcode-line">
                        <strong><?php echo __('Use this shortcode:', 'reviewpack'); ?></strong><br />
                        <code>[reviewpack type="carrousel"]</code>
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($rating == 50): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_50_5.png" alt="Stars 5"/>
<?php elseif ($rating == 40): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_40_5.png" alt="Stars 4"/>
<?php elseif ($rating == 30): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_30_5.png" alt="Stars 3"/>
<?php elseif ($rating == 20): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_20_5.png" alt="Stars 2"/>
<?php elseif ($rating == 10): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_10_5.png" alt="Stars 1"/>
<?php elseif ($rating <= 50 && $rating >= 45): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_45_5.png" alt="Stars 5"/>
<?php elseif ($rating <= 44 && $rating >= 40): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_40_5.png" alt="Stars 5"/>
<?php elseif ($rating <= 40 && $rating >= 34): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_35_5.png" alt="Stars 5"/>
<?php elseif ($rating <= 34 && $rating >= 30): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_30_5.png" alt="Stars 5"/>
<?php elseif ($rating <= 30 && $rating >= 24): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_25_5.png" alt="Stars 5"/>
<?php elseif ($rating <= 24 && $rating >= 20): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_20_5.png" alt="Stars 5"/>
<?php elseif ($rating <= 20 && $rating >= 15): ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_15_5.png" alt="Stars 5"/>
<?php else: ?>
    <img src="<?php echo plugin_dir_url(REVIEWPACK_PLUGIN_BASENAME) ?>/images/rating_10_5.png" alt="Stars 5"/>
<?php endif; ?>
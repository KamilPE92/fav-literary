<?php
/**
 * "The template for displaying all posts from individual page created by plugin "Ulubione"
 *
 *  Ulubione plugin ulubione.php
 */
get_header(); ?>
<div class="ulubione-page">
    <h1>
        <?php
        $current_user = wp_get_current_user();
        printf(__('Ulubione: %s', 'textdomain'), esc_html($current_user->display_name)) . '<br />'; ?>

    </h1>

</div>
<?php get_footer() ?>
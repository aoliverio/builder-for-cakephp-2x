<?php
/**
 * Panel
 */
$panel_style = 'panel-default';

/**
 * Panel heading
 */
$panel_header = array_key_exists('header', $panel) ? $panel['header'] : '';

/**
 * Panel body
 */
$panel_body = array_key_exists('body', $panel) ? $panel['body'] : '';


/**
 * Panel footer
 */
$panel_footer = array_key_exists('footer', $panel) ? $panel['footer'] : NULL;
?>

<!-- Display element panel -->
<div class="panel <?php echo $panel_style; ?>">
    <?php if (isset($panel_header)) : ?>
        <div class="panel-heading"><?php echo $panel_header; ?></div>
    <?php endif; ?>
    <div class="panel-body">
        <?php echo $panel_body; ?>
    </div>
    <?php if (isset($panel_footer)) : ?>
        <div class="panel-footer"><?php echo $panel_footer; ?></div>
    <?php endif; ?>
</div>
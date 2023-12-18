<?php
function talent_grid_shortcode($atts, $content = null) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'columns' => 4,
            'talents' => ''
        ),
        $atts
    );

    // Prepare talents array
    $talents = !empty($atts['talents']) ? explode(',', $atts['talents']) : array();

    // Start output buffering
    ob_start();

    // Display talent grid
    ?>
    <div class="talent-grid">
        <?php foreach ($talents as $index => $talent): ?>
            <div class="talent-item" style="width: <?php echo (100 / $atts['columns']); ?>%;">
                <?php echo esc_html($talent); ?>
            </div>
            <?php if (($index + 1) % $atts['columns'] === 0): ?>
                <div class="clear"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php

    // Return the buffered output
    return ob_get_clean();
}

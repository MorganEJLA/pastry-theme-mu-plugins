<?php
/* Function to render the Pastry Case List */
function render_pastry_case_list($atts) {

    // Safety Check: Only run on single locale pages
    if (!is_singular('locale')) {
        return '';
    }

    $current_locale_id = get_the_ID();

    // Query ALL Pastry Case items related to the current Locale
    $args = array(
        'post_type'      => 'pastry_case', // <-- CHANGED TO UNDERSCORE
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => 'related_locales',
                'compare' => 'LIKE',
                'value'   => '"' . $current_locale_id . '"',
            ),
        ),
        'orderby' => 'title',
        'order'   => 'ASC'
    );
    $list_query = new WP_Query($args);

    if (!$list_query->have_posts()) {
        return '';
    }

    ob_start();

    // Output the Structure
    ?>
    <hr class="section-break">
    <h2 class="headline headline--small">More in the Pastry Case from <?php the_title(); ?></h2>

    <ul class="min-list link-list">
        <?php while ($list_query->have_posts()) : $list_query->the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('pastry_case_list', 'render_pastry_case_list');
?>

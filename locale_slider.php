<?php
/*
Plugin Name: Locale Single Hero Slider
Description: Creates a single background image slider with centered content. Optimized for large galleries.
*/

function render_locale_slider($atts) {

    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
    );
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        null,
        true
    );

    echo '<style>
        .swiper-slide img { width:100%; height:100%; object-fit:cover; display:block; }
        .bg-overlay {
            position:absolute; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.35); z-index:2; pointer-events:none;
        }
        .locale-center-content {
            z-index:10;
            position:absolute;
            top:50%; left:50%;
            transform:translate(-50%,-50%);
            text-align:center;
            color:white;
        }
        .locale-info-slide {
            position:absolute;
            inset:0;
            opacity:0;
            transition:opacity .5s;
        }
        .swiper-button-next, .swiper-button-prev { z-index:11; }
    </style>';

    ob_start();

    // Read query params on the PHP side
    $start_id    = isset($_GET['start'])  ? intval($_GET['start'])       : 0;
    $start_slug  = isset($_GET['locale']) ? sanitize_title($_GET['locale']) : '';

    $slider_query = new WP_Query(array(
        'post_type'      => 'locale',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'post_parent'    => 0
    ));

    // This will be the index we pass to Swiper
    $initial_index = 0;

    if ($slider_query->have_posts()) {
        ?>

        <div class="locale-hero" id="locale-hero-top">

            <!-- IMAGE SLIDER -->
            <div class="swiper bg-slider">
                <div class="swiper-wrapper">

                    <?php
                    $slide_index = 0;

                    while ($slider_query->have_posts()) : $slider_query->the_post();
                        $bg_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        if (!$bg_url) {
                            $bg_url = get_theme_file_uri('/images/ocean.jpg');
                        }

                        $slug      = get_post_field('post_name', get_the_ID());
                        $locale_id = get_the_ID();

                        // If start_id or start_slug match this slide, record its index
                        if ($start_id && $locale_id === $start_id) {
                            $initial_index = $slide_index;
                        } elseif (!$start_id && $start_slug && $slug === $start_slug) {
                            $initial_index = $slide_index;
                        }
                        ?>

                        <div class="swiper-slide"
                             data-locale-slug="<?php echo esc_attr($slug); ?>"
                             data-locale-id="<?php echo esc_attr($locale_id); ?>">
                            <img src="<?php echo esc_url($bg_url); ?>" alt="">
                        </div>

                        <?php
                        $slide_index++;
                    endwhile;
                    ?>

                </div>

                <div class="bg-overlay"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <!-- OVERLAY TEXT -->
            <div class="locale-center-content">
                <?php
                // Rewind and output overlay content in same order
                $slider_query->rewind_posts();
                while ($slider_query->have_posts()) : $slider_query->the_post();
                    $slug      = get_post_field('post_name', get_the_ID());
                    $locale_id = get_the_ID();
                    ?>
                    <div class="locale-info-slide"
                         data-locale-slug="<?php echo esc_attr($slug); ?>"
                         data-locale-id="<?php echo esc_attr($locale_id); ?>">
                        <h1><?php the_title(); ?></h1>

                        <p class="subtitle">
                            <?php
                            $subtitle = get_field('page_banner_subtitle');
                            echo $subtitle ? esc_html($subtitle) : 'A beautiful destination awaits you.';
                            ?>
                        </p>

                        <div class="locale-info-slide__actions">
                            <a href="<?php the_permalink(); ?>" class="btn visit-link">
                                Explore Locale
                            </a>
                            <a href="#browse-locales" class="btn btn--outline locales-browse-link">
                                Browse all locales
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        </div>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            const infoSlides = document.querySelectorAll(".locale-info-slide");

            function updateContent(i) {
                infoSlides.forEach((slide, index) => {
                    slide.style.opacity = index === i ? 1 : 0;
                    slide.style.zIndex = index === i ? 5 : 0;
                });
            }

            function initSwiper() {
                if (typeof Swiper === "undefined") {
                    return setTimeout(initSwiper, 50);
                }

                // initial index is calculated in PHP and printed here
                const initialIndex = <?php echo intval($initial_index); ?>;

                const swiper = new Swiper(".bg-slider", {
                    effect: "fade",
                    fadeEffect: { crossFade: true },
                    speed: 900,
                    initialSlide: initialIndex,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev"
                    },
                    on: {
                        init() { updateContent(this.realIndex); },
                        slideChange() { updateContent(this.realIndex); }
                    }
                });

                // belt + suspenders: force the position just in case
                swiper.slideTo(initialIndex, 0);
                window.localeSwiper = swiper;
            }

            initSwiper();
        });
        </script>

        <?php
    }

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('locale_slider', 'render_locale_slider');

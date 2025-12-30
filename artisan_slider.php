<?php
/*
Plugin Name: Artisan Slider
Description: Artisan / Pastry Professor slider showing cards in a 3x2 grid per slide.
*/

function render_artisan_slider( $atts ) {
    // Swiper assets via CDN
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

    // IMPORTANT: use your real CPT slug (it's "professor" in your template)
    $query = new WP_Query(array(
        'post_type'      => 'professor',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));

    ob_start();

    if ( $query->have_posts() ) : ?>
      <section class="artisan-swiper-section">
        <div class="swiper professorSwiper">
          <div class="swiper-wrapper">
            <?php
            while ( $query->have_posts() ) :
              $query->the_post();
              $photo = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' )
                       ?: 'https://via.placeholder.com/400';
              ?>
              <div class="swiper-slide">
                <article class="artisan-card">
                  <div class="artisan-card__image"
                       style="background-image:url('<?php echo esc_url( $photo ); ?>');"></div>

                  <div class="artisan-card__body">
                    <h3 class="artisan-card__title"><?php the_title(); ?></h3>
                    <a href="<?php the_permalink(); ?>" class="artisan-card__button">View</a>
                  </div>
                </article>
              </div>
              <?php
            endwhile;
            wp_reset_postdata();
            ?>
          </div>

          <div class="swiper-button-prev professor-nav professor-nav--prev"></div>
          <div class="swiper-button-next professor-nav professor-nav--next"></div>
        </div>
      </section>

      <script>
        document.addEventListener("DOMContentLoaded", function () {
          new Swiper(".professorSwiper", {
            spaceBetween: 30,

            // GRID: 2 rows × 3 columns = 6 cards per "page"
            grid: {
              rows: 2,
              fill: "row"
            },

            slidesPerView: 3,
            slidesPerGroup: 6, // move a whole "page"

            navigation: {
              nextEl: ".professor-nav--next",
              prevEl: ".professor-nav--prev"
            },

            breakpoints: {
              // Phones
              0: {
                slidesPerView: 1,
                slidesPerGroup: 1,
                grid: {
                  rows: 1,
                  fill: "row"
                }
              },
              // Tablets
              768: {
                slidesPerView: 2,
                slidesPerGroup: 4,
                grid: {
                  rows: 2,
                  fill: "row"
                }
              },
              // Desktops
              1024: {
                slidesPerView: 3,
                slidesPerGroup: 6,
                grid: {
                  rows: 2,
                  fill: "row"
                }
              }
            }
          });
        });
      </script>

    <?php
    endif;

    return ob_get_clean();
}
add_shortcode( 'artisan_slider', 'render_artisan_slider' );

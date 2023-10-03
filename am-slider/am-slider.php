<?php
/*
Plugin Name: AM Slider
Plugin URI: https://everymerchant.com/
Description: A custom swiper slider plugin for WordPress named AM Slider.
Version: 1.11
Author: EveryMerchant
Author URI: https://everymerchant.com/
License: GPL2
*/

function am_enqueue_swiper_scripts() {
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, false);
}
add_action('wp_enqueue_scripts', 'am_enqueue_swiper_scripts');

function am_swiper_slider_shortcode($atts) {
    // Define the images along with their URLs
    $slides = array(
        '1' => array('type' => 'image', 'url' => 'https://njarthurmurray.com/wp-content/uploads/2023/09/AM-Fall-Banner-2023--1920x600.jpeg', 'link' => '/dance-lessons'),
        '2' => array('type' => 'image', 'url' => 'https://njarthurmurray.com/wp-content/uploads/2019/12/Wedding-PAckage-2019-2-wine-compressor-1920x600.jpg', 'link' => '/wedding-dance-lessons'),
        '3' => array('type' => 'image', 'url' => 'https://njarthurmurray.com/wp-content/uploads/2019/12/NSO-1940x600-2019_red-compressor-1920x600.jpg', 'link' => 'https://example.com/link3'),
        // ... add more slides as needed
    );

    // Get the slides parameter
    $atts = shortcode_atts(array(
        'slides' => implode(',', array_keys($slides)),  // Default is to show all slides
    ), $atts, 'am_swiper_slider');
    
    $slides_to_show = array();
    foreach (explode(',', $atts['slides']) as $slide_spec) {
        if (strpos($slide_spec, '-') !== false) {
            list($start, $end) = explode('-', $slide_spec);
            $slides_to_show = array_merge($slides_to_show, range($start, $end));
        } else {
            $slides_to_show[] = $slide_spec;
        }
    }

    ob_start();  // Buffer output to prevent it from appearing in wrong places
    ?>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <!-- Slides -->
            <?php foreach ($slides_to_show as $slide_number) : ?>
                <?php if (isset($slides[$slide_number])) : ?>
                    <div class="swiper-slide">
                        <a href="<?php echo esc_url($slides[$slide_number]['link']); ?>" target="_self">
                            <img src="<?php echo esc_url($slides[$slide_number]['url']); ?>" alt="Slide <?php echo esc_attr($slide_number); ?>">
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <script>
        var mySwiper = new Swiper('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            // Autoplay
            autoplay: {
                delay: 5000,  // 5 seconds
            },
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
    <?php
    return ob_get_clean();  // Return buffered output
}
add_shortcode('am_swiper_slider', 'am_swiper_slider_shortcode');
?>

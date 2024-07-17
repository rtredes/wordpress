<?php

function enqueue_slick_scripts()
{
    // Enqueue Slick JavaScript
    wp_enqueue_script(
        'slick-js', // Handle for Slick Carousel
        'https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js', // URL to the minified Slick Carousel JavaScript file
        array('jquery'), // Dependencies: Slick Carousel requires jQuery
        '1.8.1', // Version number of the Slick Carousel script
        true // Load script in the footer
    );
}


function enqueue_slick_styles()
{
    // Enqueue Slick CSS
    wp_enqueue_style(
        'slick-css', // Handle for Slick Carousel CSS
        'https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css', // URL to the Slick Carousel CSS file
        array(), // No dependencies
        '1.8.1', // Version number of the Slick Carousel CSS
        'all' // Stylesheet for all media types
    );
}

add_action('wp_enqueue_scripts', 'enqueue_slick_scripts');
add_action('wp_enqueue_scripts', 'enqueue_slick_styles');
